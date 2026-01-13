<?php
/**
 * Testimonials Custom Post Type
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Testimonial custom post type
 */
function ihowz_register_testimonial_cpt() {
    $labels = array(
        'name'                  => _x('Testimonials', 'Post type general name', 'ihowz-theme'),
        'singular_name'         => _x('Testimonial', 'Post type singular name', 'ihowz-theme'),
        'menu_name'             => _x('Testimonials', 'Admin Menu text', 'ihowz-theme'),
        'name_admin_bar'        => _x('Testimonial', 'Add New on Toolbar', 'ihowz-theme'),
        'add_new'               => __('Add New', 'ihowz-theme'),
        'add_new_item'          => __('Add New Testimonial', 'ihowz-theme'),
        'new_item'              => __('New Testimonial', 'ihowz-theme'),
        'edit_item'             => __('Edit Testimonial', 'ihowz-theme'),
        'view_item'             => __('View Testimonial', 'ihowz-theme'),
        'all_items'             => __('All Testimonials', 'ihowz-theme'),
        'search_items'          => __('Search Testimonials', 'ihowz-theme'),
        'parent_item_colon'     => __('Parent Testimonials:', 'ihowz-theme'),
        'not_found'             => __('No testimonials found.', 'ihowz-theme'),
        'not_found_in_trash'    => __('No testimonials found in Trash.', 'ihowz-theme'),
        'featured_image'        => _x('Author Photo', 'Overrides the "Featured Image" phrase', 'ihowz-theme'),
        'set_featured_image'    => _x('Set author photo', 'Overrides the "Set featured image" phrase', 'ihowz-theme'),
        'remove_featured_image' => _x('Remove author photo', 'Overrides the "Remove featured image" phrase', 'ihowz-theme'),
        'use_featured_image'    => _x('Use as author photo', 'Overrides the "Use as featured image" phrase', 'ihowz-theme'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('ihowz_testimonial', $args);
}
add_action('init', 'ihowz_register_testimonial_cpt');

/**
 * Add meta box for testimonial details
 */
function ihowz_add_testimonial_meta_boxes() {
    add_meta_box(
        'ihowz_testimonial_details',
        __('Testimonial Details', 'ihowz-theme'),
        'ihowz_testimonial_meta_box_callback',
        'ihowz_testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ihowz_add_testimonial_meta_boxes');

/**
 * Render the testimonial meta box
 */
function ihowz_testimonial_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('ihowz_testimonial_meta_box', 'ihowz_testimonial_meta_box_nonce');

    // Get existing values
    $quote = get_post_meta($post->ID, '_ihowz_testimonial_quote', true);
    $role = get_post_meta($post->ID, '_ihowz_testimonial_role', true);
    ?>
    <style>
        .ihowz-meta-field { margin-bottom: 20px; }
        .ihowz-meta-field label { display: block; font-weight: 600; margin-bottom: 8px; }
        .ihowz-meta-field textarea { width: 100%; }
        .ihowz-meta-field input[type="text"] { width: 100%; }
        .ihowz-meta-field .description { color: #646970; font-style: italic; margin-top: 4px; }
    </style>

    <div class="ihowz-meta-field">
        <label for="ihowz_testimonial_quote"><?php _e('Quote', 'ihowz-theme'); ?> <span style="color: #d63638;">*</span></label>
        <textarea
            id="ihowz_testimonial_quote"
            name="ihowz_testimonial_quote"
            rows="5"
            required
        ><?php echo esc_textarea($quote); ?></textarea>
        <p class="description"><?php _e('Enter the testimonial quote text.', 'ihowz-theme'); ?></p>
    </div>

    <div class="ihowz-meta-field">
        <label for="ihowz_testimonial_role"><?php _e('Role / Company', 'ihowz-theme'); ?></label>
        <input
            type="text"
            id="ihowz_testimonial_role"
            name="ihowz_testimonial_role"
            value="<?php echo esc_attr($role); ?>"
        >
        <p class="description"><?php _e('e.g., "Landlord" or "CEO, ABC Company"', 'ihowz-theme'); ?></p>
    </div>

    <p><strong><?php _e('Note:', 'ihowz-theme'); ?></strong> <?php _e('The post title is used as the author name. Use the "Author Photo" in the sidebar to add a profile picture.', 'ihowz-theme'); ?></p>
    <?php
}

/**
 * Save testimonial meta box data
 */
function ihowz_save_testimonial_meta($post_id) {
    // Check nonce
    if (!isset($_POST['ihowz_testimonial_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['ihowz_testimonial_meta_box_nonce'], 'ihowz_testimonial_meta_box')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save quote
    if (isset($_POST['ihowz_testimonial_quote'])) {
        update_post_meta(
            $post_id,
            '_ihowz_testimonial_quote',
            sanitize_textarea_field($_POST['ihowz_testimonial_quote'])
        );
    }

    // Save role
    if (isset($_POST['ihowz_testimonial_role'])) {
        update_post_meta(
            $post_id,
            '_ihowz_testimonial_role',
            sanitize_text_field($_POST['ihowz_testimonial_role'])
        );
    }
}
add_action('save_post_ihowz_testimonial', 'ihowz_save_testimonial_meta');

/**
 * Add custom columns to testimonials admin list
 */
function ihowz_testimonial_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __('Author Name', 'ihowz-theme');
    $new_columns['testimonial_role'] = __('Role/Company', 'ihowz-theme');
    $new_columns['testimonial_quote'] = __('Quote Preview', 'ihowz-theme');
    $new_columns['testimonial_photo'] = __('Photo', 'ihowz-theme');
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_ihowz_testimonial_posts_columns', 'ihowz_testimonial_columns');

/**
 * Populate custom columns in testimonials admin list
 */
function ihowz_testimonial_column_content($column, $post_id) {
    switch ($column) {
        case 'testimonial_role':
            $role = get_post_meta($post_id, '_ihowz_testimonial_role', true);
            echo $role ? esc_html($role) : '&mdash;';
            break;

        case 'testimonial_quote':
            $quote = get_post_meta($post_id, '_ihowz_testimonial_quote', true);
            if ($quote) {
                echo esc_html(wp_trim_words($quote, 15, '...'));
            } else {
                echo '&mdash;';
            }
            break;

        case 'testimonial_photo':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(40, 40), array('style' => 'border-radius: 50%;'));
            } else {
                echo '&mdash;';
            }
            break;
    }
}
add_action('manage_ihowz_testimonial_posts_custom_column', 'ihowz_testimonial_column_content', 10, 2);

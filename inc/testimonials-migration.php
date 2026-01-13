<?php
/**
 * Testimonials Migration Tool
 *
 * One-time migration tool for converting block attribute testimonials to CPT entries.
 * Access via: WordPress Admin > Tools > Migrate Testimonials
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add migration page under Tools menu
 */
function ihowz_add_testimonial_migration_menu() {
    add_management_page(
        __('Migrate Testimonials', 'ihowz-theme'),
        __('Migrate Testimonials', 'ihowz-theme'),
        'manage_options',
        'ihowz-migrate-testimonials',
        'ihowz_testimonial_migration_page'
    );
}
add_action('admin_menu', 'ihowz_add_testimonial_migration_menu');

/**
 * Render the migration admin page
 */
function ihowz_testimonial_migration_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to access this page.', 'ihowz-theme'));
    }

    $migration_results = null;

    // Handle migration action
    if (isset($_POST['ihowz_migrate_testimonials']) && check_admin_referer('ihowz_migrate_testimonials_nonce')) {
        $migration_results = ihowz_perform_testimonial_migration();
    }

    // Handle cleanup action (remove menu after migration)
    if (isset($_POST['ihowz_hide_migration']) && check_admin_referer('ihowz_hide_migration_nonce')) {
        update_option('ihowz_testimonials_migrated', true);
        echo '<div class="notice notice-success"><p>' . __('Migration tool hidden. You can re-enable it by deleting the "ihowz_testimonials_migrated" option.', 'ihowz-theme') . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php _e('Migrate Testimonials to Database', 'ihowz-theme'); ?></h1>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('What this tool does', 'ihowz-theme'); ?></h2>
            <p><?php _e('This tool scans all your pages and posts for Feedback/Testimonials blocks that contain embedded testimonial data. It will:', 'ihowz-theme'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('Extract testimonials from existing blocks', 'ihowz-theme'); ?></li>
                <li><?php _e('Create new Testimonial entries in the database', 'ihowz-theme'); ?></li>
                <li><?php _e('Link existing media library images where possible', 'ihowz-theme'); ?></li>
                <li><?php _e('Skip duplicate testimonials (matched by author name)', 'ihowz-theme'); ?></li>
            </ul>
            <p><strong><?php _e('Note:', 'ihowz-theme'); ?></strong> <?php _e('This will not modify your existing blocks. After migration, you should update each block to use the new row-based display.', 'ihowz-theme'); ?></p>
        </div>

        <?php if ($migration_results): ?>
            <div class="notice notice-<?php echo $migration_results['migrated'] > 0 ? 'success' : 'info'; ?>" style="margin-top: 20px;">
                <p><strong><?php _e('Migration Complete!', 'ihowz-theme'); ?></strong></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><?php printf(__('Pages/posts scanned: %d', 'ihowz-theme'), $migration_results['posts_scanned']); ?></li>
                    <li><?php printf(__('Feedback blocks found: %d', 'ihowz-theme'), $migration_results['blocks_found']); ?></li>
                    <li><?php printf(__('Testimonials migrated: %d', 'ihowz-theme'), $migration_results['migrated']); ?></li>
                    <li><?php printf(__('Duplicates skipped: %d', 'ihowz-theme'), $migration_results['skipped']); ?></li>
                    <?php if ($migration_results['images_linked'] > 0): ?>
                        <li><?php printf(__('Images linked: %d', 'ihowz-theme'), $migration_results['images_linked']); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($migration_results['migrated'] > 0): ?>
                <p>
                    <a href="<?php echo admin_url('edit.php?post_type=ihowz_testimonial'); ?>" class="button button-primary">
                        <?php _e('View Migrated Testimonials', 'ihowz-theme'); ?>
                    </a>
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post" style="margin-top: 20px;">
            <?php wp_nonce_field('ihowz_migrate_testimonials_nonce'); ?>
            <p>
                <input type="submit" name="ihowz_migrate_testimonials" class="button button-primary" value="<?php _e('Start Migration', 'ihowz-theme'); ?>">
            </p>
        </form>

        <hr style="margin-top: 40px;">

        <h3><?php _e('Hide Migration Tool', 'ihowz-theme'); ?></h3>
        <p><?php _e('Once you have completed the migration, you can hide this tool from the menu.', 'ihowz-theme'); ?></p>
        <form method="post">
            <?php wp_nonce_field('ihowz_hide_migration_nonce'); ?>
            <p>
                <input type="submit" name="ihowz_hide_migration" class="button" value="<?php _e('Hide Migration Tool', 'ihowz-theme'); ?>">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Perform the testimonial migration
 *
 * @return array Migration results
 */
function ihowz_perform_testimonial_migration() {
    global $wpdb;

    $results = array(
        'posts_scanned'  => 0,
        'blocks_found'   => 0,
        'migrated'       => 0,
        'skipped'        => 0,
        'images_linked'  => 0,
    );

    // Track migrated author names to avoid duplicates
    $existing_names = array();

    // Get all existing testimonial names first
    $existing_testimonials = get_posts(array(
        'post_type'      => 'ihowz_testimonial',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ));

    foreach ($existing_testimonials as $testimonial_id) {
        $existing_names[] = strtolower(get_the_title($testimonial_id));
    }

    // Find all posts/pages with the feedback block
    $posts_with_blocks = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_content, post_type
             FROM {$wpdb->posts}
             WHERE post_content LIKE %s
             AND post_status IN ('publish', 'draft', 'pending', 'private')
             AND post_type IN ('post', 'page')",
            '%wp:ihowz/feedback%'
        )
    );

    $results['posts_scanned'] = count($posts_with_blocks);

    foreach ($posts_with_blocks as $post) {
        // Parse the block content
        $blocks = parse_blocks($post->post_content);
        $feedback_blocks = ihowz_find_feedback_blocks($blocks);

        foreach ($feedback_blocks as $block) {
            $results['blocks_found']++;

            if (empty($block['attrs']['testimonials'])) {
                continue;
            }

            foreach ($block['attrs']['testimonials'] as $testimonial) {
                $author_name = isset($testimonial['authorName']) ? trim($testimonial['authorName']) : '';

                // Skip if no author name
                if (empty($author_name)) {
                    continue;
                }

                // Skip if already exists (case-insensitive)
                if (in_array(strtolower($author_name), $existing_names)) {
                    $results['skipped']++;
                    continue;
                }

                // Create new testimonial CPT entry
                $post_id = wp_insert_post(array(
                    'post_type'   => 'ihowz_testimonial',
                    'post_title'  => sanitize_text_field($author_name),
                    'post_status' => 'publish',
                ));

                if ($post_id && !is_wp_error($post_id)) {
                    // Save quote field
                    if (!empty($testimonial['quote'])) {
                        update_post_meta($post_id, '_ihowz_testimonial_quote', sanitize_textarea_field($testimonial['quote']));
                    }

                    // Save role field
                    if (!empty($testimonial['authorRole'])) {
                        update_post_meta($post_id, '_ihowz_testimonial_role', sanitize_text_field($testimonial['authorRole']));
                    }

                    // Try to link existing media library image
                    if (!empty($testimonial['authorImage'])) {
                        $image_url = $testimonial['authorImage'];
                        $attachment_id = attachment_url_to_postid($image_url);

                        if ($attachment_id) {
                            set_post_thumbnail($post_id, $attachment_id);
                            $results['images_linked']++;
                        }
                    }

                    $results['migrated']++;
                    $existing_names[] = strtolower($author_name);
                }
            }
        }
    }

    return $results;
}

/**
 * Recursively find all feedback blocks (including nested in groups/columns)
 *
 * @param array $blocks Array of parsed blocks
 * @return array Array of feedback blocks found
 */
function ihowz_find_feedback_blocks($blocks) {
    $feedback_blocks = array();

    foreach ($blocks as $block) {
        if ($block['blockName'] === 'ihowz/feedback') {
            $feedback_blocks[] = $block;
        }

        // Check for nested blocks (in groups, columns, etc.)
        if (!empty($block['innerBlocks'])) {
            $nested = ihowz_find_feedback_blocks($block['innerBlocks']);
            $feedback_blocks = array_merge($feedback_blocks, $nested);
        }
    }

    return $feedback_blocks;
}

/**
 * Conditionally show the migration menu
 */
function ihowz_maybe_hide_migration_menu() {
    if (get_option('ihowz_testimonials_migrated', false)) {
        remove_submenu_page('tools.php', 'ihowz-migrate-testimonials');
    }
}
add_action('admin_menu', 'ihowz_maybe_hide_migration_menu', 999);

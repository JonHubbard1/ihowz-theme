<?php
/**
 * iHowz Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load MegaMenu Walker
 */
require_once get_template_directory() . '/inc/class-megamenu-walker.php';

/**
 * Theme setup
 */
function ihowz_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('automatic-feed-links');
    add_theme_support('widgets');
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'ihowz-theme'),
        'footer' => esc_html__('Footer Menu', 'ihowz-theme'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 800;
    }
}
add_action('after_setup_theme', 'ihowz_theme_setup');

/**
 * Enqueue scripts and styles
 */
function ihowz_theme_scripts() {
    // Main stylesheet
    wp_enqueue_style('ihowz-style', get_stylesheet_uri(), array(), '1.0.9');

    // Custom JavaScript
    wp_enqueue_script('ihowz-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.8', true);

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'ihowz_theme_scripts');

/**
 * Register widget areas
 */
function ihowz_theme_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'ihowz-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'ihowz-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'ihowz-theme'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Footer widget area 1.', 'ihowz-theme'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'ihowz-theme'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Footer widget area 2.', 'ihowz-theme'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'ihowz-theme'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Footer widget area 3.', 'ihowz-theme'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    // Advertising Bar Widget Area
    register_sidebar(array(
        'name'          => esc_html__('Advertising Bar', 'ihowz-theme'),
        'id'            => 'advertising-bar',
        'description'   => esc_html__('Advertising banner that appears above the footer on all pages.', 'ihowz-theme'),
        'before_widget' => '<div class="advertising-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="advertising-widget-title screen-reader-text">',
        'after_title'   => '</h3>',
    ));

    // Page Sidebar Widget Area
    register_sidebar(array(
        'name'          => esc_html__('Page Sidebar', 'ihowz-theme'),
        'id'            => 'page-sidebar',
        'description'   => esc_html__('Sidebar that appears on the right side of pages.', 'ihowz-theme'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ihowz_theme_widgets_init');

/**
 * Custom excerpt length
 */
function ihowz_theme_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'ihowz_theme_excerpt_length');

/**
 * Custom excerpt more
 */
function ihowz_theme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'ihowz_theme_excerpt_more');

/**
 * Customize the read more link
 */
function ihowz_theme_read_more_link() {
    return '<a class="read-more" href="' . get_permalink() . '">' . esc_html__('Read More', 'ihowz-theme') . '</a>';
}

/**
 * Add custom body classes
 */
function ihowz_theme_body_classes($classes) {
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    if (is_page_template('page-news.php')) {
        $classes[] = 'news-page';
    }

    return $classes;
}
add_filter('body_class', 'ihowz_theme_body_classes');

/**
 * Custom pagination
 */
function ihowz_theme_pagination() {
    global $wp_query;

    $big = 999999999;

    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => esc_html__('« Previous', 'ihowz-theme'),
        'next_text' => esc_html__('Next »', 'ihowz-theme'),
        'mid_size' => 2,
        'end_size' => 1,
        'type' => 'list',
    ));
}

/**
 * Custom post meta display
 */
function ihowz_theme_post_meta() {
    echo '<div class="post-meta">';
    echo '<span class="post-date">' . get_the_date() . '</span>';
    if (get_the_author()) {
        echo ' <span class="post-author">by ' . get_the_author() . '</span>';
    }
    if (get_the_category_list(', ')) {
        echo ' <span class="post-categories">in ' . get_the_category_list(', ') . '</span>';
    }
    echo '</div>';
}

/**
 * Custom logo display
 */
function ihowz_theme_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title">' . get_bloginfo('name') . '</a>';
    }
}

/**
 * News page custom query
 */
function ihowz_theme_news_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_page('news')) {
            $query->set('post_type', 'post');
            $query->set('posts_per_page', 9);
        }
    }
}
add_action('pre_get_posts', 'ihowz_theme_news_query');

/**
 * Add support for iHowz plugin integration
 */
function ihowz_theme_plugin_integration() {
    // Check if iHowz plugin is active
    if (function_exists('IHowzManagementSuite')) {
        // Add any theme-specific plugin integrations here
        add_action('wp_head', 'ihowz_theme_plugin_styles');
    }
}
add_action('init', 'ihowz_theme_plugin_integration');

/**
 * Plugin-specific styles in head
 */
function ihowz_theme_plugin_styles() {
    echo '<style>
    .ihowz-ad-block {
        margin: 2rem 0;
        padding: 1.5rem;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        background: #f8f9fa;
    }
    .ihowz-member-dashboard {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    </style>';
}

/**
 * Disable block-based widgets editor (use classic widgets)
 */
add_filter('use_widgets_block_editor', '__return_false');

/**
 * Breadcrumb function
 */
function ihowz_theme_breadcrumb() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumb">';
        echo '<a href="' . home_url() . '">Home</a>';

        if (is_category() || is_single()) {
            echo ' / ';
            if (is_single()) {
                $category = get_the_category();
                if ($category) {
                    echo '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a> / ';
                }
                echo get_the_title();
            } else {
                echo single_cat_title();
            }
        } elseif (is_page()) {
            echo ' / ' . get_the_title();
        }

        echo '</nav>';
    }
}

/**
 * Customizer - Hero Section Settings
 */
function ihowz_theme_customize_register($wp_customize) {
    // Add Hero Section
    $wp_customize->add_section('ihowz_hero_section', array(
        'title'    => __('Hero Section', 'ihowz-theme'),
        'priority' => 30,
    ));

    // Hero Title
    $wp_customize->add_setting('ihowz_hero_title', array(
        'default'           => 'Welcome to iHowz',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_title', array(
        'label'    => __('Hero Title', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'text',
    ));

    // Hero Subtitle
    $wp_customize->add_setting('ihowz_hero_subtitle', array(
        'default'           => 'Connecting landlords and tenants for better housing solutions',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'textarea',
    ));

    // Primary Button Text
    $wp_customize->add_setting('ihowz_hero_button_primary_text', array(
        'default'           => 'Join Today',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_button_primary_text', array(
        'label'    => __('Primary Button Text', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'text',
    ));

    // Primary Button URL
    $wp_customize->add_setting('ihowz_hero_button_primary_url', array(
        'default'           => '/join',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_button_primary_url', array(
        'label'    => __('Primary Button URL', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'url',
    ));

    // Secondary Button Text
    $wp_customize->add_setting('ihowz_hero_button_secondary_text', array(
        'default'           => 'Learn More',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_button_secondary_text', array(
        'label'    => __('Secondary Button Text', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'text',
    ));

    // Secondary Button URL
    $wp_customize->add_setting('ihowz_hero_button_secondary_url', array(
        'default'           => '/about',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_button_secondary_url', array(
        'label'    => __('Secondary Button URL', 'ihowz-theme'),
        'section'  => 'ihowz_hero_section',
        'type'     => 'url',
    ));

    // Hero Description
    $wp_customize->add_setting('ihowz_hero_description', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_description', array(
        'label'       => __('Hero Description', 'ihowz-theme'),
        'description' => __('Additional description text that appears below the buttons', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'textarea',
    ));

    // Title Font Size
    $wp_customize->add_setting('ihowz_hero_title_font_size', array(
        'default'           => '5',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_title_font_size', array(
        'label'       => __('Title Font Size (rem)', 'ihowz-theme'),
        'description' => __('Default: 5rem', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 10,
            'step' => 0.5,
        ),
    ));

    // Title Font Weight
    $wp_customize->add_setting('ihowz_hero_title_font_weight', array(
        'default'           => '200',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_title_font_weight', array(
        'label'       => __('Title Font Weight', 'ihowz-theme'),
        'description' => __('100=Thin, 300=Light, 400=Regular, 700=Bold', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'select',
        'choices'     => array(
            '100' => '100 - Thin',
            '200' => '200 - Extra Light',
            '300' => '300 - Light',
            '400' => '400 - Regular',
            '500' => '500 - Medium',
            '600' => '600 - Semi Bold',
            '700' => '700 - Bold',
        ),
    ));

    // Subtitle Font Size
    $wp_customize->add_setting('ihowz_hero_subtitle_font_size', array(
        'default'           => '2.5',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_subtitle_font_size', array(
        'label'       => __('Subtitle Font Size (rem)', 'ihowz-theme'),
        'description' => __('Default: 2.5rem', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 8,
            'step' => 0.5,
        ),
    ));

    // Subtitle Font Weight
    $wp_customize->add_setting('ihowz_hero_subtitle_font_weight', array(
        'default'           => '300',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_subtitle_font_weight', array(
        'label'       => __('Subtitle Font Weight', 'ihowz-theme'),
        'description' => __('100=Thin, 300=Light, 400=Regular, 700=Bold', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'select',
        'choices'     => array(
            '100' => '100 - Thin',
            '200' => '200 - Extra Light',
            '300' => '300 - Light',
            '400' => '400 - Regular',
            '500' => '500 - Medium',
            '600' => '600 - Semi Bold',
            '700' => '700 - Bold',
        ),
    ));

    // Description Font Size
    $wp_customize->add_setting('ihowz_hero_description_font_size', array(
        'default'           => '1.5',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_description_font_size', array(
        'label'       => __('Description Font Size (rem)', 'ihowz-theme'),
        'description' => __('Default: 1.5rem', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 5,
            'step' => 0.25,
        ),
    ));

    // Description Font Weight
    $wp_customize->add_setting('ihowz_hero_description_font_weight', array(
        'default'           => '300',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('ihowz_hero_description_font_weight', array(
        'label'       => __('Description Font Weight', 'ihowz-theme'),
        'description' => __('100=Thin, 300=Light, 400=Regular, 700=Bold', 'ihowz-theme'),
        'section'     => 'ihowz_hero_section',
        'type'        => 'select',
        'choices'     => array(
            '100' => '100 - Thin',
            '200' => '200 - Extra Light',
            '300' => '300 - Light',
            '400' => '400 - Regular',
            '500' => '500 - Medium',
            '600' => '600 - Semi Bold',
            '700' => '700 - Bold',
        ),
    ));
}
add_action('customize_register', 'ihowz_theme_customize_register');

/**
 * Add MegaMenu Custom Fields to Menu Items
 */
function ihowz_megamenu_custom_fields($item_id, $item, $depth, $args) {
    ?>
    <div class="megamenu-options" style="margin: 15px 0; padding: 15px; background: #f5f5f5; border-radius: 4px;">
        <h4 style="margin-top: 0;">MegaMenu Settings</h4>

        <p class="description" style="margin-bottom: 15px;">
            <label>
                <input type="checkbox" name="menu-item-megamenu-enabled[<?php echo $item_id; ?>]" value="1" <?php checked(get_post_meta($item_id, '_megamenu_enabled', true), '1'); ?>>
                Enable MegaMenu for this item
            </label>
        </p>

        <p class="description">
            <label>
                Column Count:
                <select name="menu-item-megamenu-columns[<?php echo $item_id; ?>]">
                    <?php
                    $columns = get_post_meta($item_id, '_megamenu_columns', true);
                    for ($i = 2; $i <= 4; $i++) {
                        echo '<option value="' . $i . '" ' . selected($columns, $i, false) . '>' . $i . ' Columns</option>';
                    }
                    ?>
                </select>
            </label>
        </p>

        <p class="description">
            <label>
                <input type="checkbox" name="menu-item-megamenu-featured[<?php echo $item_id; ?>]" value="1" <?php checked(get_post_meta($item_id, '_megamenu_featured', true), '1'); ?>>
                Featured item (highlighted)
            </label>
        </p>

        <p class="description">
            <label style="display: block; margin-bottom: 5px;">
                Icon/Emoji (optional):
                <input type="text" name="menu-item-megamenu-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr(get_post_meta($item_id, '_megamenu_icon', true)); ?>" placeholder="e.g., 🏠 or text" style="width: 100%;">
            </label>
            <small>Add an emoji or text that appears before the menu item</small>
        </p>

        <p class="description">
            <label style="display: block; margin-bottom: 5px;">
                Description (optional):
                <textarea name="menu-item-megamenu-description[<?php echo $item_id; ?>]" rows="2" style="width: 100%;"><?php echo esc_textarea(get_post_meta($item_id, '_megamenu_description', true)); ?></textarea>
            </label>
            <small>Short description that appears below the menu item</small>
        </p>
    </div>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'ihowz_megamenu_custom_fields', 10, 4);

/**
 * Save MegaMenu Custom Fields
 */
function ihowz_save_megamenu_custom_fields($menu_id, $menu_item_db_id) {
    // Save megamenu enabled
    $megamenu_enabled = isset($_POST['menu-item-megamenu-enabled'][$menu_item_db_id]) ? '1' : '0';
    update_post_meta($menu_item_db_id, '_megamenu_enabled', $megamenu_enabled);

    // Save column count
    if (isset($_POST['menu-item-megamenu-columns'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_megamenu_columns', sanitize_text_field($_POST['menu-item-megamenu-columns'][$menu_item_db_id]));
    }

    // Save featured
    $megamenu_featured = isset($_POST['menu-item-megamenu-featured'][$menu_item_db_id]) ? '1' : '0';
    update_post_meta($menu_item_db_id, '_megamenu_featured', $megamenu_featured);

    // Save icon
    if (isset($_POST['menu-item-megamenu-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_megamenu_icon', sanitize_text_field($_POST['menu-item-megamenu-icon'][$menu_item_db_id]));
    }

    // Save description
    if (isset($_POST['menu-item-megamenu-description'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_megamenu_description', sanitize_textarea_field($_POST['menu-item-megamenu-description'][$menu_item_db_id]));
    }
}
add_action('wp_update_nav_menu_item', 'ihowz_save_megamenu_custom_fields', 10, 2);

/**
 * Output Hero Section Custom Styles
 */
function ihowz_hero_custom_styles() {
    $title_size = get_theme_mod('ihowz_hero_title_font_size', '5');
    $title_weight = get_theme_mod('ihowz_hero_title_font_weight', '200');
    $subtitle_size = get_theme_mod('ihowz_hero_subtitle_font_size', '2.5');
    $subtitle_weight = get_theme_mod('ihowz_hero_subtitle_font_weight', '300');
    $description_size = get_theme_mod('ihowz_hero_description_font_size', '1.5');
    $description_weight = get_theme_mod('ihowz_hero_description_font_weight', '300');

    $custom_css = "
        .hero-title {
            font-size: {$title_size}rem !important;
            font-weight: {$title_weight} !important;
        }
        .hero-subtitle {
            font-size: {$subtitle_size}rem !important;
            font-weight: {$subtitle_weight} !important;
        }
        .hero-description {
            font-size: {$description_size}rem !important;
            font-weight: {$description_weight} !important;
        }
    ";

    wp_add_inline_style('ihowz-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'ihowz_hero_custom_styles');
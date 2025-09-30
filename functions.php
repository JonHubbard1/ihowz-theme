<?php
/**
 * iHowz Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

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
    wp_enqueue_style('ihowz-style', get_stylesheet_uri(), array(), '1.0.2');

    // Custom JavaScript
    wp_enqueue_script('ihowz-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);

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
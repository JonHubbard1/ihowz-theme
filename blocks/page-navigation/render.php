<?php
/**
 * Page Navigation Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Detect if rendering in a widget area
$is_widget_context = did_action('dynamic_sidebar_before') > did_action('dynamic_sidebar_after');

// Get attributes with defaults
$depth = isset($attributes['depth']) ? intval($attributes['depth']) : 2;
$show_only_children = isset($attributes['showOnlyChildren']) ? $attributes['showOnlyChildren'] : false;
$parent_page = isset($attributes['parentPage']) ? intval($attributes['parentPage']) : 0;
$exclude_ids = isset($attributes['excludeIds']) ? sanitize_text_field($attributes['excludeIds']) : '';
$title = isset($attributes['title']) ? sanitize_text_field($attributes['title']) : '';
$show_title = isset($attributes['showTitle']) ? $attributes['showTitle'] : false;

// Determine the parent page for the list
$child_of = 0;
$is_editor = defined('REST_REQUEST') && REST_REQUEST;
$editor_post_id = isset($_GET['editor_post_id']) ? intval($_GET['editor_post_id']) : 0;

if ($show_only_children) {
    // Show children of current page
    $current_page_id = 0;

    if (is_page()) {
        global $post;
        $current_page_id = $post->ID;
        $current_parent = $post->post_parent;
    } elseif ($is_editor && $editor_post_id > 0) {
        // In editor context, use the post ID passed from the editor
        $current_page_id = $editor_post_id;
        $editor_post = get_post($editor_post_id);
        $current_parent = $editor_post ? $editor_post->post_parent : 0;
    }

    if ($current_page_id > 0) {
        // If current page has children, show them
        // Otherwise show siblings (children of parent)
        $children = get_pages(array('child_of' => $current_page_id, 'post_status' => 'publish'));
        if (!empty($children)) {
            $child_of = $current_page_id;
        } elseif ($current_parent) {
            $child_of = $current_parent;
        } else {
            $child_of = $current_page_id;
        }
    }
} elseif ($parent_page > 0) {
    // Show children of specific page
    $child_of = $parent_page;
}

// Build wp_list_pages arguments
$args = array(
    'depth'       => $depth,
    'child_of'    => $child_of,
    'title_li'    => '',
    'echo'        => false,
    'sort_column' => 'menu_order, post_title',
    'post_status' => 'publish',
);

// Add exclusions if set
if (!empty($exclude_ids)) {
    $args['exclude'] = $exclude_ids;
}

// Get the page list
$pages = wp_list_pages($args);

// Don't render if no pages
if (empty($pages)) {
    return;
}

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-page-navigation ihowz-page-navigation depth-' . $depth;
if ($is_widget_context) {
    $wrapper_classes .= ' in-widget';
}
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => $wrapper_classes,
));
?>

<nav <?php echo $wrapper_attributes; ?> aria-label="<?php esc_attr_e('Page navigation', 'ihowz-theme'); ?>">
    <?php if ($show_title && !empty($title)) : ?>
        <h3 class="page-navigation-title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <ul class="page-navigation-list">
        <?php echo $pages; ?>
    </ul>
</nav>

<?php
/**
 * Content with Sidebar Block - Server-side Render
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Detect if rendering in a widget area
$is_widget_context = did_action('dynamic_sidebar_before') > did_action('dynamic_sidebar_after');

// Extract attributes with defaults
$sidebar_source = isset($attributes['sidebarSource']) ? $attributes['sidebarSource'] : 'blocks';
$widget_area = isset($attributes['widgetArea']) ? $attributes['widgetArea'] : 'page-sidebar';
$content_width = isset($attributes['contentWidth']) ? intval($attributes['contentWidth']) : 70;
$gap_size = isset($attributes['gapSize']) ? intval($attributes['gapSize']) : 40;
$sidebar_position = isset($attributes['sidebarPosition']) ? $attributes['sidebarPosition'] : 'right';
$vertical_alignment = isset($attributes['verticalAlignment']) ? $attributes['verticalAlignment'] : 'top';

// Calculate sidebar width
$sidebar_width = 100 - $content_width;

// Build inline styles
$grid_columns = $sidebar_position === 'right'
    ? "{$content_width}fr {$sidebar_width}fr"
    : "{$sidebar_width}fr {$content_width}fr";

$align_items = 'flex-start';
if ($vertical_alignment === 'center') {
    $align_items = 'center';
} elseif ($vertical_alignment === 'stretch') {
    $align_items = 'stretch';
}

$inline_style = sprintf(
    'grid-template-columns: %s; gap: %dpx; align-items: %s;',
    $grid_columns,
    $gap_size,
    $align_items
);

// Build wrapper classes
$wrapper_class = 'ihowz-content-with-sidebar sidebar-' . $sidebar_position;
if ($is_widget_context) {
    $wrapper_class .= ' in-widget';
}

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => $wrapper_class,
    'style' => $inline_style,
));

// Parse inner blocks content to separate content and sidebar
// The template creates two groups: content-area and sidebar-area
$content_html = $content;
$sidebar_html = '';

// If using widgets, we need to capture the widget area output
if ($sidebar_source === 'widgets') {
    ob_start();
    if (is_active_sidebar($widget_area)) {
        dynamic_sidebar($widget_area);
    } else {
        echo '<p class="no-widgets">' . esc_html__('Add widgets to this sidebar in Appearance > Widgets', 'ihowz-theme') . '</p>';
    }
    $sidebar_html = ob_get_clean();
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($sidebar_position === 'left' && $sidebar_source === 'widgets') : ?>
        <aside class="sidebar-area widget-sidebar">
            <?php echo $sidebar_html; ?>
        </aside>
        <div class="content-area">
            <?php echo $content_html; ?>
        </div>
    <?php elseif ($sidebar_position === 'right' && $sidebar_source === 'widgets') : ?>
        <div class="content-area">
            <?php echo $content_html; ?>
        </div>
        <aside class="sidebar-area widget-sidebar">
            <?php echo $sidebar_html; ?>
        </aside>
    <?php else : ?>
        <?php
        // When using blocks, the InnerBlocks template handles the order
        echo $content_html;
        ?>
    <?php endif; ?>
</div>

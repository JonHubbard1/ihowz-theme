<?php
/**
 * Custom MegaMenu Walker
 *
 * @package iHowz
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class IHowz_MegaMenu_Walker extends Walker_Nav_Menu {

    /**
     * Start the element output
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Add megamenu container class for first level dropdowns
        if ($depth === 0) {
            $classes = 'sub-menu megamenu-dropdown';
        } else {
            $classes = 'sub-menu';
        }

        $output .= "{$n}{$indent}<ul class=\"$classes\">{$n}";
    }

    /**
     * Start the menu item output
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add megamenu specific classes
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-megamenu';

            // Check for megamenu enabled meta
            $megamenu_enabled = get_post_meta($item->ID, '_megamenu_enabled', true);
            if ($megamenu_enabled) {
                $classes[] = 'megamenu-enabled';

                // Get column count
                $columns = get_post_meta($item->ID, '_megamenu_columns', true);
                if ($columns) {
                    $classes[] = 'megamenu-cols-' . $columns;
                }
            }
        }

        // Add featured class
        $is_featured = get_post_meta($item->ID, '_megamenu_featured', true);
        if ($is_featured) {
            $classes[] = 'megamenu-featured';
        }

        // Add icon class if icon exists
        $icon = get_post_meta($item->ID, '_megamenu_icon', true);
        if ($icon) {
            $classes[] = 'has-icon';
        }

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        // Build the link HTML
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';

        // Add icon if exists
        if ($icon && $depth === 0) {
            $item_output .= '<span class="menu-icon">' . $icon . '</span>';
        }

        $item_output .= '<span class="menu-text">' . $args->link_before . $title . $args->link_after . '</span>';

        // Add dropdown indicator for items with children
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<span class="dropdown-indicator"></span>';
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        // Add description if exists and is inside dropdown (not on menu bar)
        $description = get_post_meta($item->ID, '_megamenu_description', true);
        if ($description && $depth > 0) {
            $item_output .= '<span class="menu-description">' . esc_html($description) . '</span>';
        }

        /**
         * Filters a menu item's starting output.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

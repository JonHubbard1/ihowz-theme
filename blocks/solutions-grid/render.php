<?php
/**
 * Solutions Grid Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Get attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? $attributes['eyebrowText'] : '';
$heading = isset($attributes['heading']) ? $attributes['heading'] : '';
$show_top_button = isset($attributes['showTopButton']) ? $attributes['showTopButton'] : false;
$top_button_text = isset($attributes['topButtonText']) ? $attributes['topButtonText'] : '';
$top_button_url = isset($attributes['topButtonUrl']) ? esc_url($attributes['topButtonUrl']) : '';
$rows = isset($attributes['rows']) ? $attributes['rows'] : array();
$gap = isset($attributes['gap']) ? intval($attributes['gap']) : 20;
$card_border_radius = isset($attributes['cardBorderRadius']) ? intval($attributes['cardBorderRadius']) : 16;
$card_min_height = isset($attributes['cardMinHeight']) ? intval($attributes['cardMinHeight']) : 250;

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-solutions-grid ihowz-solutions-grid';
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Check if we have any content to show
$has_header = $eyebrow_text || $heading || ($show_top_button && $top_button_text);
$has_rows = !empty($rows);

if (!$has_header && !$has_rows) {
    return;
}
?>

<section class="<?php echo esc_attr($wrapper_classes); ?>">
    <?php if ($has_header) : ?>
        <div class="solutions-grid-header">
            <div class="solutions-grid-header-content">
                <?php if ($eyebrow_text) : ?>
                    <span class="solutions-grid-eyebrow ihowz-eyebrow"><?php echo esc_html($eyebrow_text); ?></span>
                <?php endif; ?>
                <?php if ($heading) : ?>
                    <h2 class="solutions-grid-heading ihowz-heading"><?php echo wp_kses_post($heading); ?></h2>
                <?php endif; ?>
            </div>
            <?php if ($show_top_button && $top_button_text) : ?>
                <a href="<?php echo $top_button_url; ?>" class="solutions-grid-top-btn">
                    <?php echo esc_html($top_button_text); ?>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($has_rows) : ?>
        <div class="solutions-grid-rows" style="gap: <?php echo $gap; ?>px;">
            <?php foreach ($rows as $row_index => $row) : ?>
                <?php if (!empty($row['items'])) : ?>
                    <div class="solutions-grid-row" style="gap: <?php echo $gap; ?>px;">
                        <?php foreach ($row['items'] as $item_index => $item) : ?>
                            <?php
                            $item_image = isset($item['imageUrl']) ? esc_url($item['imageUrl']) : '';
                            $item_title = isset($item['title']) ? $item['title'] : '';
                            $item_button_text = isset($item['buttonText']) ? $item['buttonText'] : '';
                            $item_button_url = isset($item['buttonUrl']) ? esc_url($item['buttonUrl']) : '';
                            $item_width = isset($item['width']) ? floatval($item['width']) : 0;

                            // Calculate flex basis accounting for gaps
                            $flex_style = '';
                            if ($item_width > 0) {
                                $flex_style = "flex: 0 0 calc({$item_width}% - " . ($gap / 2) . "px);";
                            } else {
                                $flex_style = "flex: 1 1 0;";
                            }
                            ?>
                            <div class="solutions-grid-card" style="<?php echo $flex_style; ?> min-height: <?php echo $card_min_height; ?>px; border-radius: <?php echo $card_border_radius; ?>px;<?php if ($item_image) : ?> background-image: url('<?php echo $item_image; ?>');<?php endif; ?>">
                                <div class="solutions-grid-card-overlay" style="border-radius: <?php echo $card_border_radius; ?>px;"></div>
                                <div class="solutions-grid-card-content">
                                    <?php if ($item_title) : ?>
                                        <h3 class="solutions-grid-card-title"><?php echo esc_html($item_title); ?></h3>
                                    <?php endif; ?>
                                    <?php if ($item_button_text) : ?>
                                        <a href="<?php echo $item_button_url; ?>" class="solutions-grid-card-btn">
                                            <?php echo esc_html($item_button_text); ?>
                                            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

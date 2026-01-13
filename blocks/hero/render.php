<?php
/**
 * Hero Section Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Detect if rendering in a widget area
$is_widget_context = did_action('dynamic_sidebar_before') > did_action('dynamic_sidebar_after');

// Get attributes with defaults
$background_type = isset($attributes['backgroundType']) ? $attributes['backgroundType'] : 'image';
$background_image = isset($attributes['backgroundImage']) ? esc_url($attributes['backgroundImage']) : '';
$background_video = isset($attributes['backgroundVideo']) ? esc_url($attributes['backgroundVideo']) : '';
$overlay_opacity = isset($attributes['overlayOpacity']) ? intval($attributes['overlayOpacity']) : 30;
$overlay_color = isset($attributes['overlayColor']) ? sanitize_hex_color($attributes['overlayColor']) : '#1a365d';
$heading = isset($attributes['heading']) ? $attributes['heading'] : '';
$subheading = isset($attributes['subheading']) ? $attributes['subheading'] : '';
$primary_btn_text = isset($attributes['primaryButtonText']) ? $attributes['primaryButtonText'] : '';
$primary_btn_url = isset($attributes['primaryButtonUrl']) ? esc_url($attributes['primaryButtonUrl']) : '#';
$secondary_btn_text = isset($attributes['secondaryButtonText']) ? $attributes['secondaryButtonText'] : '';
$secondary_btn_url = isset($attributes['secondaryButtonUrl']) ? esc_url($attributes['secondaryButtonUrl']) : '#';
$min_height = isset($attributes['minHeight']) ? intval($attributes['minHeight']) : 600;
$full_width = isset($attributes['fullWidth']) ? $attributes['fullWidth'] : true;

// Left card
$show_left_card = isset($attributes['showLeftCard']) ? $attributes['showLeftCard'] : false;
$left_card_image = isset($attributes['leftCardImage']) ? esc_url($attributes['leftCardImage']) : '';
$left_card_title = isset($attributes['leftCardTitle']) ? $attributes['leftCardTitle'] : '';
$left_card_text = isset($attributes['leftCardText']) ? $attributes['leftCardText'] : '';

// Right card
$show_right_card = isset($attributes['showRightCard']) ? $attributes['showRightCard'] : false;
$right_card_image = isset($attributes['rightCardImage']) ? esc_url($attributes['rightCardImage']) : '';
$right_card_title = isset($attributes['rightCardTitle']) ? $attributes['rightCardTitle'] : '';
$right_card_text = isset($attributes['rightCardText']) ? $attributes['rightCardText'] : '';

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-hero ihowz-hero';
if ($is_widget_context) {
    $wrapper_classes .= ' in-widget';
}
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if ($full_width) {
    $wrapper_classes .= ' alignfull';
}

// Convert hex to rgba for overlay
$hex = str_replace('#', '', $overlay_color);
$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));
$overlay_rgba = "rgba({$r}, {$g}, {$b}, " . ($overlay_opacity / 100) . ")";

?>

<section class="<?php echo esc_attr($wrapper_classes); ?> hero-bg-<?php echo esc_attr($background_type); ?>" style="min-height: <?php echo intval($min_height); ?>px;<?php if ($background_type === 'image' && $background_image) : ?> background-image: url('<?php echo esc_url($background_image); ?>');<?php endif; ?>">

    <?php if ($background_type === 'video' && $background_video) : ?>
        <div class="hero-video-wrapper">
            <video class="hero-video" autoplay muted loop playsinline>
                <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
            </video>
        </div>
    <?php endif; ?>

    <div class="hero-overlay" style="background: <?php echo esc_attr($overlay_rgba); ?>;"></div>

    <div class="hero-content">
        <?php if ($heading) : ?>
            <h1 class="hero-heading"><?php echo wp_kses_post($heading); ?></h1>
        <?php endif; ?>

        <?php if ($subheading) : ?>
            <p class="hero-subheading"><?php echo wp_kses_post($subheading); ?></p>
        <?php endif; ?>

        <?php if ($primary_btn_text || $secondary_btn_text) : ?>
            <div class="hero-buttons">
                <?php if ($primary_btn_text) : ?>
                    <a href="<?php echo $primary_btn_url; ?>" class="hero-btn hero-btn-primary">
                        <?php echo esc_html($primary_btn_text); ?>
                        <svg class="hero-btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($secondary_btn_text) : ?>
                    <a href="<?php echo $secondary_btn_url; ?>" class="hero-btn hero-btn-secondary">
                        <?php echo esc_html($secondary_btn_text); ?>
                        <svg class="hero-btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($show_left_card && ($left_card_title || $left_card_text)) : ?>
        <div class="hero-card hero-card-left">
            <?php if ($left_card_image) : ?>
                <div class="hero-card-image">
                    <img src="<?php echo $left_card_image; ?>" alt="">
                </div>
            <?php endif; ?>
            <div class="hero-card-content">
                <?php if ($left_card_title) : ?>
                    <h3 class="hero-card-title"><?php echo esc_html($left_card_title); ?></h3>
                <?php endif; ?>
                <?php if ($left_card_text) : ?>
                    <p class="hero-card-text"><?php echo esc_html($left_card_text); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show_right_card && ($right_card_title || $right_card_text)) : ?>
        <div class="hero-card hero-card-right">
            <?php if ($right_card_image) : ?>
                <div class="hero-card-image">
                    <img src="<?php echo $right_card_image; ?>" alt="">
                </div>
            <?php endif; ?>
            <div class="hero-card-content">
                <?php if ($right_card_title) : ?>
                    <h3 class="hero-card-title"><?php echo esc_html($right_card_title); ?></h3>
                <?php endif; ?>
                <?php if ($right_card_text) : ?>
                    <p class="hero-card-text"><?php echo esc_html($right_card_text); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

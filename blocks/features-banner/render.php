<?php
/**
 * Features Banner Block - Server-side Render
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Extract attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? esc_html($attributes['eyebrowText']) : 'Our Services';
$heading = isset($attributes['heading']) ? esc_html($attributes['heading']) : 'Experience the future of housing.';
$image_card_title = isset($attributes['imageCardTitle']) ? esc_html($attributes['imageCardTitle']) : 'Making Everything Better';
$image_card_subtitle = isset($attributes['imageCardSubtitle']) ? esc_html($attributes['imageCardSubtitle']) : 'From the start';
$image_card_title_position = isset($attributes['imageCardTitlePosition']) ? esc_attr($attributes['imageCardTitlePosition']) : 'top';
$image_min_height = isset($attributes['imageMinHeight']) ? intval($attributes['imageMinHeight']) : 400;
$image_alignment = isset($attributes['imageAlignment']) ? esc_attr($attributes['imageAlignment']) : 'center';
$image_url = isset($attributes['imageUrl']) ? esc_url($attributes['imageUrl']) : '';
$image_id = isset($attributes['imageId']) ? absint($attributes['imageId']) : 0;
$features = isset($attributes['features']) ? $attributes['features'] : array();
$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '#f5f5f5';

// Get image URL from ID if available
if ($image_id && !$image_url) {
    $image_url = wp_get_attachment_image_url($image_id, 'large');
}

// Icon SVGs mapping
$icons = array(
    'lightbulb' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2v1M12 8a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.4V18h4v-2.6c1.2-.6 2-1.9 2-3.4a4 4 0 0 0-4-4z"/></svg>',
    'home' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'star' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
    'shield' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'users' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'chart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
    'check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
    'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
);

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => 'ihowz-features-banner',
    'style' => 'background-color: ' . $background_color . ';',
));
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="features-banner-container">
        <!-- Header -->
        <div class="features-banner-header">
            <?php if ($eyebrow_text) : ?>
                <span class="features-banner-eyebrow ihowz-eyebrow"><?php echo $eyebrow_text; ?></span>
            <?php endif; ?>
            <?php if ($heading) : ?>
                <h2 class="features-banner-heading ihowz-heading"><?php echo $heading; ?></h2>
            <?php endif; ?>
        </div>

        <!-- Content Grid -->
        <div class="features-banner-grid">
            <!-- Featured Image Card -->
            <div class="features-banner-image-card title-position-<?php echo $image_card_title_position; ?>" style="min-height: <?php echo $image_min_height; ?>px;">
                <?php if ($image_url) : ?>
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $image_card_title; ?>" class="features-banner-image" style="object-position: center <?php echo $image_alignment; ?>;" />
                <?php else : ?>
                    <div class="features-banner-image-placeholder"></div>
                <?php endif; ?>
                <div class="features-banner-image-overlay">
                    <div class="features-banner-image-content">
                        <h3 class="features-banner-image-title"><?php echo $image_card_title; ?></h3>
                        <p class="features-banner-image-subtitle"><?php echo $image_card_subtitle; ?></p>
                    </div>
                    <!-- Decorative circular element -->
                    <div class="features-banner-decorative-circle">
                        <svg viewBox="0 0 100 100" class="circular-progress">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                            <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-dasharray="200 283" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Feature Cards -->
            <?php foreach ($features as $index => $feature) :
                $icon_key = isset($feature['icon']) ? $feature['icon'] : 'lightbulb';
                $icon_svg = isset($icons[$icon_key]) ? $icons[$icon_key] : $icons['lightbulb'];
                $title = isset($feature['title']) ? esc_html($feature['title']) : '';
                $description = isset($feature['description']) ? esc_html($feature['description']) : '';
            ?>
                <div class="features-banner-card">
                    <div class="features-banner-card-icon">
                        <?php echo $icon_svg; ?>
                    </div>
                    <h3 class="features-banner-card-title"><?php echo $title; ?></h3>
                    <p class="features-banner-card-description"><?php echo $description; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

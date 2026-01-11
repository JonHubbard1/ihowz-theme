<?php
/**
 * Member Services Bento Block - Frontend Render
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get attributes
$eyebrow_text = $attributes['eyebrowText'] ?? '';
$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$services = $attributes['services'] ?? [];
$background_color = $attributes['backgroundColor'] ?? '#f5f5f5';

// Build wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'ihowz-member-services-bento',
    'style' => 'background-color: ' . esc_attr($background_color) . ';'
]);

// SVG icons
$icons = [
    'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
    'lightbulb' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18h6M10 22h4M12 2v1M12 8a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.4V18h4v-2.6c1.2-.6 2-1.9 2-3.4a4 4 0 0 0-4-4z"/></svg>',
    'newspaper' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><line x1="10" y1="6" x2="18" y2="6"/><line x1="10" y1="10" x2="18" y2="10"/><line x1="10" y1="14" x2="18" y2="14"/></svg>',
    'megaphone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>',
    'tag' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
    'folder' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
    'home' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'users' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'shield' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'chart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
    'star' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
    'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>'
];

?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="member-services-bento-container">
        <?php if ($eyebrow_text || $heading || $subheading) : ?>
            <div class="member-services-bento-header">
                <?php if ($eyebrow_text) : ?>
                    <span class="member-services-bento-eyebrow"><?php echo esc_html($eyebrow_text); ?></span>
                <?php endif; ?>
                <?php if ($heading) : ?>
                    <h2 class="member-services-bento-heading"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>
                <?php if ($subheading) : ?>
                    <p class="member-services-bento-subheading"><?php echo esc_html($subheading); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($services)) : ?>
            <div class="member-services-bento-cards">
                <?php foreach ($services as $service) :
                    $icon_key = $service['icon'] ?? 'calendar';
                    $icon_svg = $icons[$icon_key] ?? $icons['calendar'];
                    $size = $service['size'] ?? 'small';
                    $image_url = $service['imageUrl'] ?? '';
                    $has_image = ($size === 'medium' || $size === 'large') && !empty($image_url);
                    $card_class = 'member-services-bento-card member-services-bento-card--' . esc_attr($size);
                    if ($has_image) {
                        $card_class .= ' has-image';
                    }
                ?>
                    <div class="<?php echo $card_class; ?>">
                        <div class="member-services-bento-card-content">
                            <div class="member-services-bento-card-icon">
                                <?php echo $icon_svg; ?>
                            </div>
                            <h3 class="member-services-bento-card-title"><?php echo esc_html($service['title'] ?? ''); ?></h3>
                            <p class="member-services-bento-card-description"><?php echo esc_html($service['description'] ?? ''); ?></p>
                            <?php if (!empty($service['buttonText'])) : ?>
                                <a href="<?php echo esc_url($service['buttonUrl'] ?? '#'); ?>" class="member-services-bento-card-btn">
                                    <?php echo esc_html($service['buttonText']); ?>
                                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if ($has_image) : ?>
                            <div class="member-services-bento-card-image">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($service['title'] ?? ''); ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

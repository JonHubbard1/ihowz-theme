<?php
/**
 * About Stats Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Get attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? $attributes['eyebrowText'] : '';
$main_text = isset($attributes['mainText']) ? $attributes['mainText'] : '';
$stats = isset($attributes['stats']) ? $attributes['stats'] : array();
$secondary_text = isset($attributes['secondaryText']) ? $attributes['secondaryText'] : '';
$show_button = isset($attributes['showButton']) ? $attributes['showButton'] : false;
$button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
$button_url = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';
$image1_url = isset($attributes['image1Url']) ? esc_url($attributes['image1Url']) : '';
$image2_url = isset($attributes['image2Url']) ? esc_url($attributes['image2Url']) : '';

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-about-stats ihowz-about-stats';
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Check if we have content
$has_content = $eyebrow_text || $main_text || !empty($stats) || $secondary_text || $image1_url || $image2_url;
if (!$has_content) {
    return;
}
?>

<section class="<?php echo esc_attr($wrapper_classes); ?>">
    <div class="about-stats-grid">
        <!-- Eyebrow Text - cols 1-2, row 1 -->
        <div class="about-stats-eyebrow-area">
            <?php if ($eyebrow_text) : ?>
                <span class="about-stats-eyebrow ihowz-eyebrow"><?php echo esc_html($eyebrow_text); ?></span>
            <?php endif; ?>
        </div>

        <!-- Main Text - cols 3-5, row 1 -->
        <div class="about-stats-main-area">
            <?php if ($main_text) : ?>
                <p class="about-stats-main-text"><?php echo wp_kses_post($main_text); ?></p>
            <?php endif; ?>
        </div>

        <!-- Stats - one per column (cols 1, 2, 3), row 2 -->
        <?php if (!empty($stats)) : ?>
            <?php foreach ($stats as $index => $stat) : ?>
                <?php
                $stat_number = isset($stat['number']) ? $stat['number'] : '';
                $stat_label1 = isset($stat['label1']) ? $stat['label1'] : '';
                $stat_label2 = isset($stat['label2']) ? $stat['label2'] : '';
                $col = $index + 1; // columns 1, 2, 3
                ?>
                <?php if ($stat_number && $col <= 3) : ?>
                    <div class="about-stats-stat about-stats-stat-<?php echo $col; ?>">
                        <div class="about-stats-number"><?php echo esc_html($stat_number); ?></div>
                        <?php if ($stat_label1) : ?>
                            <div class="about-stats-label"><?php echo esc_html($stat_label1); ?></div>
                        <?php endif; ?>
                        <?php if ($stat_label2) : ?>
                            <div class="about-stats-label about-stats-label-2"><?php echo esc_html($stat_label2); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Big Image (Image 2) - cols 4-5, rows 2-3 -->
        <?php if ($image2_url) : ?>
            <div class="about-stats-image about-stats-image-2">
                <img src="<?php echo $image2_url; ?>" alt="">
            </div>
        <?php endif; ?>

        <!-- Bottom Text + Button - cols 1-2, row 3 -->
        <div class="about-stats-text-area">
            <?php if ($secondary_text) : ?>
                <p class="about-stats-secondary-text"><?php echo wp_kses_post($secondary_text); ?></p>
            <?php endif; ?>
            <?php if ($show_button && $button_text) : ?>
                <a href="<?php echo $button_url; ?>" class="about-stats-btn">
                    <?php echo esc_html($button_text); ?>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php endif; ?>
        </div>

        <!-- Small Image (Image 1) - col 3, row 3 -->
        <?php if ($image1_url) : ?>
            <div class="about-stats-image about-stats-image-1">
                <img src="<?php echo $image1_url; ?>" alt="">
            </div>
        <?php endif; ?>
    </div>
</section>

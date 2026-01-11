<?php
/**
 * Feedback/Testimonials Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Get attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? $attributes['eyebrowText'] : '';
$heading = isset($attributes['heading']) ? $attributes['heading'] : '';
$show_button = isset($attributes['showButton']) ? $attributes['showButton'] : false;
$button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
$button_url = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';
$testimonials = isset($attributes['testimonials']) ? $attributes['testimonials'] : array();

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-feedback ihowz-feedback';
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Check if we have content
$has_content = $eyebrow_text || $heading || !empty($testimonials);
if (!$has_content) {
    return;
}

// Quote icon SVG
$quote_icon = '<svg class="feedback-quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>';
?>

<section class="<?php echo esc_attr($wrapper_classes); ?>">
    <div class="feedback-grid">
        <!-- Header Area -->
        <div class="feedback-header">
            <?php if ($eyebrow_text) : ?>
                <span class="feedback-eyebrow ihowz-eyebrow"><?php echo esc_html($eyebrow_text); ?></span>
            <?php endif; ?>
            <?php if ($heading) : ?>
                <h2 class="feedback-heading ihowz-heading"><?php echo wp_kses_post($heading); ?></h2>
            <?php endif; ?>
            <?php if ($show_button && $button_text) : ?>
                <a href="<?php echo $button_url; ?>" class="feedback-btn">
                    <?php echo esc_html($button_text); ?>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php endif; ?>
        </div>

        <!-- Testimonial Cards -->
        <?php foreach ($testimonials as $index => $testimonial) : ?>
            <?php
            $quote = isset($testimonial['quote']) ? $testimonial['quote'] : '';
            $author_name = isset($testimonial['authorName']) ? $testimonial['authorName'] : '';
            $author_role = isset($testimonial['authorRole']) ? $testimonial['authorRole'] : '';
            $author_image = isset($testimonial['authorImage']) ? esc_url($testimonial['authorImage']) : '';
            ?>
            <?php if ($quote) : ?>
                <div class="feedback-card feedback-card-<?php echo $index + 1; ?>">
                    <?php echo $quote_icon; ?>
                    <blockquote class="feedback-quote">
                        <?php echo wp_kses_post($quote); ?>
                    </blockquote>
                    <div class="feedback-author">
                        <?php if ($author_image) : ?>
                            <img src="<?php echo $author_image; ?>" alt="<?php echo esc_attr($author_name); ?>" class="feedback-author-image">
                        <?php else : ?>
                            <div class="feedback-author-placeholder"></div>
                        <?php endif; ?>
                        <div class="feedback-author-info">
                            <?php if ($author_name) : ?>
                                <span class="feedback-author-name"><?php echo esc_html($author_name); ?></span>
                            <?php endif; ?>
                            <?php if ($author_role) : ?>
                                <span class="feedback-author-role"><?php echo esc_html($author_role); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

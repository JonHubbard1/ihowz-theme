<?php
/**
 * Feedback/Testimonials Block - Server-side rendering
 *
 * Displays randomly selected testimonials from the database.
 *
 * @package iHowz Theme
 */

// Detect if rendering in a widget area
$is_widget_context = did_action('dynamic_sidebar_before') > did_action('dynamic_sidebar_after');

// Get attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? $attributes['eyebrowText'] : '';
$heading = isset($attributes['heading']) ? $attributes['heading'] : '';
$show_button = isset($attributes['showButton']) ? $attributes['showButton'] : false;
$button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
$button_url = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';
$display_rows = isset($attributes['displayRows']) ? $attributes['displayRows'] : '2';

// Calculate number of testimonials needed based on rows
// 1 row = 2 testimonials (header + 2 in first row)
// 2 rows = 5 testimonials (header + 2 in first row, 3 in second row)
$testimonial_count = ($display_rows === '1') ? 2 : 5;

// Query testimonials from CPT with random order
$testimonial_query = new WP_Query(array(
    'post_type'      => 'ihowz_testimonial',
    'posts_per_page' => $testimonial_count,
    'orderby'        => 'rand',
    'post_status'    => 'publish',
));

// Build testimonials array from query results
$testimonials = array();
if ($testimonial_query->have_posts()) {
    while ($testimonial_query->have_posts()) {
        $testimonial_query->the_post();

        // Get featured image URL for author photo
        $author_image = '';
        if (has_post_thumbnail()) {
            $author_image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
        }

        $testimonials[] = array(
            'quote'       => get_post_meta(get_the_ID(), '_ihowz_testimonial_quote', true),
            'authorName'  => get_the_title(),
            'authorRole'  => get_post_meta(get_the_ID(), '_ihowz_testimonial_role', true),
            'authorImage' => $author_image,
        );
    }
    wp_reset_postdata();
}

// Fallback: Check for legacy block attribute testimonials if CPT query is empty
if (empty($testimonials) && !empty($attributes['testimonials'])) {
    $testimonials = array_slice($attributes['testimonials'], 0, $testimonial_count);
}

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-feedback ihowz-feedback';
$wrapper_classes .= ' feedback-rows-' . esc_attr($display_rows);
if ($is_widget_context) {
    $wrapper_classes .= ' in-widget';
}
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Check if we have content to display
$has_content = $eyebrow_text || $heading || !empty($testimonials);
if (!$has_content) {
    return;
}

// Quote icon SVG - explicit width/height for editor preview fallback
$quote_icon = '<svg class="feedback-quote-icon" width="40" height="40" style="width:40px;height:40px;flex-shrink:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>';

// Inline critical styles for editor preview (ServerSideRender doesn't always load block styles)
$inline_styles = '
<style>
.wp-block-ihowz-feedback .feedback-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.wp-block-ihowz-feedback .feedback-card{background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column}
.wp-block-ihowz-feedback .feedback-quote-icon{width:40px;height:40px;color:#9cc130;margin-bottom:16px;flex-shrink:0}
.wp-block-ihowz-feedback .feedback-quote{font-size:1rem;line-height:1.7;color:#757575;margin:0 0 20px;flex-grow:1}
.wp-block-ihowz-feedback .feedback-author{display:flex;align-items:center;gap:12px;margin-top:auto}
.wp-block-ihowz-feedback .feedback-author-image{width:44px;height:44px;border-radius:50%;object-fit:cover}
.wp-block-ihowz-feedback .feedback-author-placeholder{width:44px;height:44px;border-radius:50%;background:#e0e0e0}
.wp-block-ihowz-feedback .feedback-author-name{font-weight:600;color:#263238}
.wp-block-ihowz-feedback .feedback-author-role{font-size:0.875rem;color:#9cc130}
@media(max-width:992px){.wp-block-ihowz-feedback .feedback-grid{grid-template-columns:repeat(2,1fr)}.wp-block-ihowz-feedback .feedback-header{grid-column:1/3}}
@media(max-width:540px){.wp-block-ihowz-feedback .feedback-grid{grid-template-columns:1fr}.wp-block-ihowz-feedback .feedback-header{grid-column:1}}
</style>';
?>

<?php echo $inline_styles; ?>
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
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
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

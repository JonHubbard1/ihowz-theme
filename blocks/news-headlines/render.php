<?php
/**
 * News Headlines Block - Server-side rendering
 *
 * Displays posts from a selected category in a configurable grid layout.
 *
 * @package iHowz Theme
 */

// Get attributes with defaults
$category = isset($attributes['category']) ? sanitize_text_field($attributes['category']) : '';
$post_count = isset($attributes['postCount']) ? absint($attributes['postCount']) : 6;
$columns = isset($attributes['columns']) ? absint($attributes['columns']) : 3;
$rows = isset($attributes['rows']) ? absint($attributes['rows']) : 2;
$show_featured_image = isset($attributes['showFeaturedImage']) ? $attributes['showFeaturedImage'] : true;
$show_author = isset($attributes['showAuthor']) ? $attributes['showAuthor'] : true;
$show_date = isset($attributes['showDate']) ? $attributes['showDate'] : true;
$show_excerpt = isset($attributes['showExcerpt']) ? $attributes['showExcerpt'] : true;
$container_width = isset($attributes['containerWidth']) ? $attributes['containerWidth'] : 'content';
$background_opacity = isset($attributes['backgroundOpacity']) ? absint($attributes['backgroundOpacity']) : 0;

// Calculate max posts based on columns * rows
$max_posts = $columns * $rows;
$posts_to_fetch = min($post_count, $max_posts);

// Query arguments
$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_to_fetch,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Add category filter if specified
if (!empty($category)) {
    $query_args['category_name'] = $category;
}

// Execute query
$news_query = new WP_Query($query_args);

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-news-headlines ihowz-news-headlines';

// Container width class
if ($container_width === 'full') {
    $wrapper_classes .= ' ihowz-width-full';
} else {
    $wrapper_classes .= ' ihowz-width-standard';
}

// Background color class
if ($background_opacity > 0) {
    $wrapper_classes .= ' ihowz-bg-green-' . $background_opacity;
}

// Rounded corners for content width with background
if ($container_width === 'content' && $background_opacity > 0) {
    $wrapper_classes .= ' ihowz-rounded';
}

// Additional classes from editor
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Check if we have posts to display
if (!$news_query->have_posts()) {
    wp_reset_postdata();
    return;
}

// Inline styles for grid columns (CSS custom property)
$grid_style = '--news-columns: ' . esc_attr($columns) . ';';

// Inline critical styles for editor preview
$inline_styles = '
<style>
.wp-block-ihowz-news-headlines .news-headlines-inner{width:90%;max-width:1600px;margin-left:auto;margin-right:auto}
.wp-block-ihowz-news-headlines.ihowz-width-standard .news-headlines-inner{width:100%;max-width:none}
.wp-block-ihowz-news-headlines .news-headlines-grid{display:grid;grid-template-columns:repeat(var(--news-columns, 3), 1fr);gap:24px}
.wp-block-ihowz-news-headlines .news-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(38,50,56,0.08);transition:transform 0.25s ease-out,box-shadow 0.25s ease-out}
.wp-block-ihowz-news-headlines .news-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(38,50,56,0.12)}
.wp-block-ihowz-news-headlines .news-card-image{aspect-ratio:16/9;overflow:hidden}
.wp-block-ihowz-news-headlines .news-card-image img{width:100%;height:100%;object-fit:cover;transition:transform 0.3s ease}
.wp-block-ihowz-news-headlines .news-card:hover .news-card-image img{transform:scale(1.05)}
.wp-block-ihowz-news-headlines .news-card-content{padding:20px}
.wp-block-ihowz-news-headlines .news-card-title{font-size:1.125rem;font-weight:600;color:#263238;margin:0 0 12px;line-height:1.4}
.wp-block-ihowz-news-headlines .news-card-title a{color:inherit;text-decoration:none}
.wp-block-ihowz-news-headlines .news-card-title a:hover{color:#9cc130}
.wp-block-ihowz-news-headlines .news-card-excerpt{font-size:0.9375rem;color:#757575;line-height:1.6;margin:0 0 16px}
.wp-block-ihowz-news-headlines .news-card-meta{display:flex;flex-wrap:wrap;gap:12px;font-size:0.8125rem;color:#9e9e9e}
.wp-block-ihowz-news-headlines .news-card-meta-item{display:flex;align-items:center;gap:4px}
.wp-block-ihowz-news-headlines .news-card-meta svg{width:14px;height:14px;flex-shrink:0}
.wp-block-ihowz-news-headlines.ihowz-rounded{border-radius:16px}
.wp-block-ihowz-news-headlines.ihowz-width-standard{padding:40px}
.wp-block-ihowz-news-headlines.ihowz-width-full{padding:40px 0}
.wp-block-ihowz-news-headlines.ihowz-bg-green-75 .news-card-title,
.wp-block-ihowz-news-headlines.ihowz-bg-green-100 .news-card-title{color:#263238}
@media(max-width:992px){.wp-block-ihowz-news-headlines .news-headlines-grid{grid-template-columns:repeat(2, 1fr)}}
@media(max-width:540px){.wp-block-ihowz-news-headlines .news-headlines-grid{grid-template-columns:1fr}.wp-block-ihowz-news-headlines.ihowz-width-standard,.wp-block-ihowz-news-headlines.ihowz-width-full{padding:24px 0}.wp-block-ihowz-news-headlines.ihowz-width-standard .news-headlines-inner{padding:0 16px}}
</style>';

// SVG icons
$calendar_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd"/></svg>';
$author_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/></svg>';
?>

<?php echo $inline_styles; ?>
<section class="<?php echo esc_attr($wrapper_classes); ?>" style="<?php echo esc_attr($grid_style); ?>">
    <div class="news-headlines-inner">
        <div class="news-headlines-grid">
            <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                <article class="news-card">
                    <?php if ($show_featured_image && has_post_thumbnail()) : ?>
                        <div class="news-card-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="news-card-content">
                        <h3 class="news-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($show_excerpt) : ?>
                            <p class="news-card-excerpt">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 18, '...')); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($show_date || $show_author) : ?>
                            <div class="news-card-meta">
                                <?php if ($show_date) : ?>
                                    <span class="news-card-meta-item news-card-date">
                                        <?php echo $calendar_icon; ?>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </span>
                                <?php endif; ?>
                                <?php if ($show_author) : ?>
                                    <span class="news-card-meta-item news-card-author">
                                        <?php echo $author_icon; ?>
                                        <?php echo esc_html(get_the_author()); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>

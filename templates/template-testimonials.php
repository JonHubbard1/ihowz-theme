<?php
/**
 * Template Name: All Testimonials
 * Description: Displays all testimonials in a grid layout
 *
 * @package iHowz Theme
 */

get_header();

// Query all published testimonials
$testimonials_query = new WP_Query(array(
    'post_type'      => 'ihowz_testimonial',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
));

// Quote icon SVG
$quote_icon = '<svg class="testimonial-quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>';
?>

<main id="main-content" class="site-main">
    <article class="page-testimonials">
        <header class="page-header testimonials-header">
            <div class="container">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php if (get_the_content()) : ?>
                        <div class="page-intro">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; endif; ?>
            </div>
        </header>

        <section class="testimonials-list">
            <div class="container">
                <?php if ($testimonials_query->have_posts()) : ?>
                    <div class="testimonials-grid">
                        <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                            $quote = get_post_meta(get_the_ID(), '_ihowz_testimonial_quote', true);
                            $role = get_post_meta(get_the_ID(), '_ihowz_testimonial_role', true);
                            $author_name = get_the_title();
                            $author_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : '';
                        ?>
                            <?php if ($quote) : ?>
                                <div class="testimonial-card">
                                    <?php echo $quote_icon; ?>
                                    <blockquote class="testimonial-quote">
                                        <?php echo wp_kses_post($quote); ?>
                                    </blockquote>
                                    <div class="testimonial-author">
                                        <?php if ($author_image) : ?>
                                            <img src="<?php echo esc_url($author_image); ?>" alt="<?php echo esc_attr($author_name); ?>" class="testimonial-author-image">
                                        <?php else : ?>
                                            <div class="testimonial-author-placeholder"></div>
                                        <?php endif; ?>
                                        <div class="testimonial-author-info">
                                            <?php if ($author_name) : ?>
                                                <span class="testimonial-author-name"><?php echo esc_html($author_name); ?></span>
                                            <?php endif; ?>
                                            <?php if ($role) : ?>
                                                <span class="testimonial-author-role"><?php echo esc_html($role); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="no-testimonials"><?php _e('No testimonials found.', 'ihowz-theme'); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </article>
</main>

<style>
/* Testimonials Page Styles */
.page-testimonials .testimonials-header {
    padding: clamp(2rem, 5vw, 4rem) 0;
    text-align: center;
}

.page-testimonials .page-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-testimonials .page-intro {
    max-width: 700px;
    margin: 0 auto;
    color: var(--text-secondary, #64748b);
    font-size: 1.125rem;
    line-height: 1.7;
}

.testimonials-list {
    padding: 0 0 clamp(3rem, 6vw, 5rem);
}

.testimonials-list .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 380px), 1fr));
    gap: 1.5rem;
}

.testimonial-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.testimonial-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 8px 24px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.testimonial-quote-icon {
    width: 2rem;
    height: 2rem;
    color: var(--primary-green, #9cc130);
    margin-bottom: 1rem;
    flex-shrink: 0;
}

.testimonial-quote {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--text-primary, #1e293b);
    margin: 0 0 1.5rem;
    flex-grow: 1;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.testimonial-author-image {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.testimonial-author-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    flex-shrink: 0;
}

.testimonial-author-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.testimonial-author-name {
    font-weight: 600;
    font-size: 0.9375rem;
    color: var(--text-primary, #1e293b);
}

.testimonial-author-role {
    font-size: 0.8125rem;
    color: var(--primary-green, #9cc130);
    font-weight: 500;
}

.no-testimonials {
    text-align: center;
    padding: 3rem;
    color: var(--text-secondary, #64748b);
    font-size: 1.125rem;
}

/* Dark mode support if theme uses it */
@media (prefers-color-scheme: dark) {
    .testimonial-card {
        background: var(--card-bg, #1e293b);
    }

    .testimonial-quote,
    .testimonial-author-name {
        color: var(--text-primary, #f1f5f9);
    }

    .testimonial-author {
        border-top-color: rgba(255, 255, 255, 0.1);
    }
}
</style>

<?php get_footer(); ?>

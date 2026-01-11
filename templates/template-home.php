<?php
/**
 * Template Name: Home Page
 *
 * Modern landing page with dynamic hero, membership benefits, and sponsor showcase
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main template-home">

    <?php while (have_posts()) : the_post(); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html(get_field('hero_title') ?: '50+ Years Protecting Landlords'); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html(get_field('hero_subtitle') ?: 'Professional support and expert guidance for landlords across the UK'); ?></p>
                <div class="hero-cta">
                    <span class="cta-highlight">JOIN TODAY - £85/year | FREE Trial Available</span>
                </div>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(get_field('cta_trial_url') ?: '#'); ?>" class="btn btn-cta">Start Free Trial</a>
                    <a href="<?php echo esc_url(get_field('cta_learn_url') ?: '#'); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Indicators, Quick Actions, Latest Alerts -->
    <section class="info-cards-section">
        <div class="container">
            <div class="info-cards-grid">
                <!-- Trust Indicators -->
                <div class="info-card">
                    <h3><?php _e('Trust Indicators', 'ihowz-theme'); ?></h3>
                    <ul class="info-list">
                        <li><?php _e('50+ Years Experience', 'ihowz-theme'); ?></li>
                        <li><?php _e('10,000+ Members', 'ihowz-theme'); ?></li>
                        <li><?php _e('5-Star Reviews', 'ihowz-theme'); ?></li>
                        <li><?php _e('Industry Awards', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="info-card">
                    <h3><?php _e('Quick Actions', 'ihowz-theme'); ?></h3>
                    <ul class="info-list">
                        <li><a href="#"><?php _e('Get Instant Quote', 'ihowz-theme'); ?></a></li>
                        <li><a href="#"><?php _e('Download Guide', 'ihowz-theme'); ?></a></li>
                        <li><a href="#"><?php _e('Book Consultation', 'ihowz-theme'); ?></a></li>
                        <li><a href="#"><?php _e('Get Support', 'ihowz-theme'); ?></a></li>
                    </ul>
                </div>

                <!-- Latest Alerts -->
                <div class="info-card">
                    <h3><?php _e('Latest Alerts', 'ihowz-theme'); ?></h3>
                    <ul class="info-list">
                        <li><?php _e('Regulation Changes', 'ihowz-theme'); ?></li>
                        <li><?php _e('Court Decisions', 'ihowz-theme'); ?></li>
                        <li><?php _e('Member Benefits', 'ihowz-theme'); ?></li>
                        <li><?php _e('Event Reminders', 'ihowz-theme'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Benefits & Featured News -->
    <section class="two-column-section">
        <div class="container">
            <div class="two-column-grid">
                <!-- Membership Benefits -->
                <div class="content-column">
                    <h2><?php _e('Membership Benefits Showcase', 'ihowz-theme'); ?></h2>
                    <div class="benefits-list">
                        <ul>
                            <li><?php _e('Legal Advice Line', 'ihowz-theme'); ?></li>
                            <li><?php _e('Document Library', 'ihowz-theme'); ?></li>
                            <li><?php _e('Insurance Discounts', 'ihowz-theme'); ?></li>
                            <li><?php _e('Tax Investigation Cover', 'ihowz-theme'); ?></li>
                            <li><?php _e('Weekly Updates', 'ihowz-theme'); ?></li>
                            <li><?php _e('Quarterly Magazine', 'ihowz-theme'); ?></li>
                        </ul>
                    </div>
                    <div class="value-calculator">
                        <span class="calculator-highlight"><?php _e('Value Calculator: Show £2000+ Annual Savings', 'ihowz-theme'); ?></span>
                    </div>
                </div>

                <!-- Featured News -->
                <div class="content-column">
                    <h2><?php _e('Featured News & Insights', 'ihowz-theme'); ?></h2>
                    <?php
                    $recent_posts = new WP_Query(array(
                        'posts_per_page' => 4,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));

                    if ($recent_posts->have_posts()) :
                        echo '<div class="news-list">';
                        while ($recent_posts->have_posts()) : $recent_posts->the_post();
                            ?>
                            <article class="news-item">
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <p class="news-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            </article>
                            <?php
                        endwhile;
                        echo '</div>';
                        wp_reset_postdata();
                    endif;
                    ?>
                    <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn-primary"><?php _e('View All Articles', 'ihowz-theme'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof Section -->
    <section class="social-proof-section">
        <div class="container">
            <h2><?php _e('What Our Members Say', 'ihowz-theme'); ?></h2>
            <div class="testimonials-grid">
                <?php for ($i = 1; $i <= 3; $i++) : ?>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <?php echo wpautop(get_field("testimonial_{$i}_content") ?: 'Testimonial content goes here.'); ?>
                    </div>
                    <div class="testimonial-author">
                        <strong><?php echo esc_html(get_field("testimonial_{$i}_author") ?: 'Member Name'); ?></strong>
                        <span><?php echo esc_html(get_field("testimonial_{$i}_role") ?: 'Landlord'); ?></span>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Sponsor Showcase - Premium Partners -->
    <section class="sponsors-section">
        <div class="container">
            <h2><?php _e('Our Premium Partners', 'ihowz-theme'); ?></h2>
            <div class="sponsors-grid">
                <?php if (is_active_sidebar('home-sponsors')) : ?>
                    <?php dynamic_sidebar('home-sponsors'); ?>
                <?php else : ?>
                    <!-- Placeholder sponsor cards -->
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <div class="sponsor-card-placeholder">
                        <div class="sponsor-ad-space" style="width: 250px; height: 250px;">
                            <p><?php echo sprintf(__('Sponsor Ad %d', 'ihowz-theme'), $i); ?><br>250x250</p>
                        </div>
                    </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            <div class="sponsors-cta">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('sponsors'))); ?>" class="btn btn-secondary"><?php _e('View All Partners', 'ihowz-theme'); ?></a>
            </div>
        </div>
    </section>

    <!-- Page Content -->
    <div class="container">
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>

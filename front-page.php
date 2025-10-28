<?php
/**
 * Front Page Template - Home Page with Hero Video
 */

get_header(); ?>

<!-- Hero Video Section -->
<section class="hero-video-section">
    <div class="hero-video-container">
        <video id="hero-background-video" class="hero-video" autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url(home_url('/wp-content/uploads/2025/10/starterBackdrop.png')); ?>">
            <source src="<?php echo esc_url(home_url('/wp-content/uploads/2025/10/backgroundVideo.mp4')); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-video-overlay"></div>
        <div class="hero-content">
            <div class="hero-content-inner">
                <h1 class="hero-title"><?php echo esc_html(get_theme_mod('ihowz_hero_title', 'Welcome to iHowz')); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html(get_theme_mod('ihowz_hero_subtitle', 'Connecting landlords and tenants for better housing solutions')); ?></p>
                <div class="hero-content-bottom">
                    <?php
                    $hero_description = get_theme_mod('ihowz_hero_description', '');
                    if (!empty($hero_description)) : ?>
                        <div class="hero-description">
                            <?php echo wp_kses_post($hero_description); ?>
                        </div>
                    <?php endif; ?>
                    <div class="hero-buttons">
                        <a href="<?php echo esc_url(get_theme_mod('ihowz_hero_button_primary_url', '/join')); ?>" class="hero-button hero-button-primary"><?php echo esc_html(get_theme_mod('ihowz_hero_button_primary_text', 'Join Today')); ?></a>
                        <a href="<?php echo esc_url(get_theme_mod('ihowz_hero_button_secondary_url', '/about')); ?>" class="hero-button hero-button-secondary"><?php echo esc_html(get_theme_mod('ihowz_hero_button_secondary_text', 'Learn More')); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="site-main">
    <div class="container">

        <?php if (have_posts()) : ?>

            <div class="page-header">
                <h2 class="section-title">Latest News</h2>
            </div>

            <div class="posts-grid">
                <?php
                // Query latest posts
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'post_status' => 'publish'
                );
                $latest_posts = new WP_Query($args);

                while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-card-content">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php ihowz_theme_post_meta(); ?>

                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                                <?php echo ihowz_theme_read_more_link(); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata();
                ?>
            </div>

        <?php else : ?>

            <div class="no-posts">
                <h2><?php esc_html_e('Nothing Found', 'ihowz-theme'); ?></h2>
                <p><?php esc_html_e('It looks like nothing was found at this location.', 'ihowz-theme'); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>

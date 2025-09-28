<?php
/**
 * The main template file
 */

get_header(); ?>

<main class="site-main">
    <div class="container">

        <?php if (have_posts()) : ?>

            <?php if (is_home()) : ?>
                <div class="page-header">
                    <h1 class="page-title"><?php echo get_bloginfo('name'); ?></h1>
                    <p class="page-description"><?php echo get_bloginfo('description'); ?></p>
                </div>
            <?php endif; ?>

            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
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
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php ihowz_theme_pagination(); ?>
            </div>

        <?php else : ?>

            <div class="no-posts">
                <h2><?php esc_html_e('Nothing Found', 'ihowz-theme'); ?></h2>
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'ihowz-theme'); ?></p>
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
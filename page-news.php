<?php
/**
 * Template for the News page
 */

get_header(); ?>

<main class="site-main">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">Latest News</h1>
            <p class="page-description">Stay updated with the latest industry news and insights</p>
        </div>

        <?php ihowz_theme_breadcrumb(); ?>

        <?php
        // Custom query for news posts
        $news_query = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 9,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        ));

        if ($news_query->have_posts()) : ?>

            <div class="posts-grid">
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
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
                <?php
                echo paginate_links(array(
                    'total' => $news_query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'prev_text' => esc_html__('« Previous', 'ihowz-theme'),
                    'next_text' => esc_html__('Next »', 'ihowz-theme'),
                ));
                ?>
            </div>

        <?php else : ?>

            <div class="no-posts">
                <h2><?php esc_html_e('No News Found', 'ihowz-theme'); ?></h2>
                <p><?php esc_html_e('Check back soon for the latest updates.', 'ihowz-theme'); ?></p>
            </div>

        <?php endif;
        wp_reset_postdata();
        ?>

    </div>
</main>

<?php get_footer(); ?>
<?php
/**
 * Generic page template
 */

get_header(); ?>

<main class="site-main">

    <?php while (have_posts()) : the_post(); ?>

        <?php
        ihowz_page_header_bar(array(
            'title'    => get_the_title(),
            'subtitle' => has_excerpt() ? get_the_excerpt() : '',
        ));
        ?>

        <div class="container page-container-with-sidebar">
            <div class="page-main-content">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="page-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="page-content">
                        <?php the_content(); ?>

                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'ihowz-theme'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                </article>

                <?php
                // If comments are open or we have at least one comment
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div>

            <aside class="page-sidebar">
                <?php if (is_active_sidebar('page-sidebar')) : ?>
                    <?php dynamic_sidebar('page-sidebar'); ?>
                <?php else : ?>
                    <div class="widget">
                        <h3>Sidebar</h3>
                        <p>Add widgets to this sidebar in Appearance > Widgets.</p>
                    </div>
                <?php endif; ?>
            </aside>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
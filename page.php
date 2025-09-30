<?php
/**
 * Generic page template
 */

get_header(); ?>

<main class="site-main">

    <?php while (have_posts()) : the_post(); ?>

        <!-- Page Header -->
        <div class="container">
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </header>
        </div>

        <!-- Breadcrumb -->
        <?php
        if (!is_front_page() && !is_home()) {
            echo '<div class="container">';
            ihowz_theme_breadcrumb();
            echo '</div>';
        }
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
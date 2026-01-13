<?php
/**
 * Template Name: Full Width with Widgets and Title
 * Template Post Type: page
 *
 * A full-width template with page title header and a widget sidebar.
 * Content follows Standard Content Width Rule (90%/1600px).
 */

get_header(); ?>

<main class="site-main site-main-fullwidth">

    <?php while (have_posts()) : the_post(); ?>

        <!-- Page Header with Full-Width Background -->
        <div class="page-header-fullwidth">
            <div class="page-header-inner">
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>

                <!-- Breadcrumb -->
                <?php
                if (!is_front_page() && !is_home()) {
                    ihowz_theme_breadcrumb();
                }
                ?>
            </div>
        </div>

        <article id="post-<?php the_ID(); ?>" <?php post_class('fullwidth-article'); ?>>

            <!-- Widget Area Above Content -->
            <?php if (is_active_sidebar('page-content-above')) : ?>
                <div class="page-widgets-above">
                    <?php dynamic_sidebar('page-content-above'); ?>
                </div>
            <?php endif; ?>

            <!-- Content with Sidebar - follows Standard Content Width Rule -->
            <div class="page-with-widgets-container">
                <div class="page-main-content">
                    <div class="page-content">
                        <?php the_content(); ?>

                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'ihowz-theme'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <?php
                    // If comments are open or we have at least one comment
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>

                <aside class="page-widgets-sidebar">
                    <?php if (is_active_sidebar('page-sidebar')) : ?>
                        <?php dynamic_sidebar('page-sidebar'); ?>
                    <?php else : ?>
                        <div class="widget">
                            <h3><?php esc_html_e('Sidebar', 'ihowz-theme'); ?></h3>
                            <p><?php esc_html_e('Add widgets to this sidebar in Appearance > Widgets.', 'ihowz-theme'); ?></p>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>

            <!-- Widget Area Below Content -->
            <?php if (is_active_sidebar('page-content-below')) : ?>
                <div class="page-widgets-below">
                    <?php dynamic_sidebar('page-content-below'); ?>
                </div>
            <?php endif; ?>

        </article>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>

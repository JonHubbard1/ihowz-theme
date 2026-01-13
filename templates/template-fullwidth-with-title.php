<?php
/**
 * Template Name: Full Width with Title
 * Template Post Type: page
 *
 * A full-width template with page title and no sidebar - perfect for block-based layouts.
 * Use the "Content with Sidebar" block to add sidebar sections where needed.
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

            <div class="page-content fullwidth-content">
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
            echo '<div class="container">';
            comments_template();
            echo '</div>';
        endif;
        ?>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>

<?php
/**
 * Template Name: Sponsors Page
 *
 * A full-width page template for sponsor listings, used by Member Offers
 * that link to dedicated sponsor pages on this site.
 *
 * @package iHowz
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

        <div class="container page-container">
            <div class="page-main-content page-main-content-full">
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
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div>
        </div>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>

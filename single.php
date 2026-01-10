<?php
/**
 * Single post template
 */

get_header(); ?>

<main class="site-main">
    <div class="container">

        <?php ihowz_theme_breadcrumb(); ?>

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="single-post-header">
                    <h1 class="single-post-title"><?php the_title(); ?></h1>
                    <div class="single-post-meta">
                        <?php ihowz_theme_post_meta(); ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php the_content(); ?>

                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'ihowz-theme'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <footer class="post-footer">
                    <?php if (get_the_tags()) : ?>
                        <div class="post-tags">
                            <strong><?php esc_html_e('Tags:', 'ihowz-theme'); ?></strong>
                            <?php the_tags('', ', ', ''); ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-navigation">
                        <?php
                        the_post_navigation(array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'ihowz-theme') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'ihowz-theme') . '</span> <span class="nav-title">%title</span>',
                        ));
                        ?>
                    </div>
                </footer>

            </article>

            <?php
            // If comments are open or we have at least one comment
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
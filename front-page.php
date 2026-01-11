<?php
/**
 * Front Page Template
 *
 * Displays the content of the page set as the static front page,
 * allowing full use of Gutenberg blocks and page templates.
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main front-page">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>

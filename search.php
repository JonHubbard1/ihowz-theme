<?php
/**
 * Search results template.
 *
 * Delegates all result aggregation (WordPress pages/news/testimonials,
 * iHowz documents, events, and membership types) to the plugin's
 * [ihowz_site_search_results] shortcode so the logic lives with the plugin
 * that owns the custom tables.
 */

get_header(); ?>

<main class="site-main search-results-page">

    <?php
    ihowz_page_header_bar(array(
        'title'            => sprintf(__('Search results for "%s"', 'ihowz-theme'), get_search_query()),
        'show_breadcrumbs' => false,
    ));
    ?>

    <div class="container">
        <?php echo do_shortcode('[ihowz_site_search_results]'); ?>
    </div>
</main>

<?php get_footer(); ?>
<?php
/**
 * Template Name: Resource Hub & Document Library
 *
 * Filterable resource grid with search and categorization
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main template-resources">

    <?php while (have_posts()) : the_post(); ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <?php if (get_the_excerpt()) : ?>
            <p class="page-intro"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Filter & Search Bar -->
    <section class="filter-search-section">
        <div class="container">
            <form class="resource-filters" id="resource-filters">
                <div class="filter-grid">
                    <div class="filter-item">
                        <label for="category-filter"><?php _e('Category', 'ihowz-theme'); ?></label>
                        <select id="category-filter" name="category">
                            <option value=""><?php _e('All Categories', 'ihowz-theme'); ?></option>
                            <?php
                            $categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => true));
                            foreach ($categories as $category) :
                                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="type-filter"><?php _e('Document Type', 'ihowz-theme'); ?></label>
                        <select id="type-filter" name="type">
                            <option value=""><?php _e('All Types', 'ihowz-theme'); ?></option>
                            <option value="pdf"><?php _e('PDF', 'ihowz-theme'); ?></option>
                            <option value="doc"><?php _e('Document', 'ihowz-theme'); ?></option>
                            <option value="template"><?php _e('Template', 'ihowz-theme'); ?></option>
                            <option value="guide"><?php _e('Guide', 'ihowz-theme'); ?></option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="date-filter"><?php _e('Date Range', 'ihowz-theme'); ?></label>
                        <select id="date-filter" name="date">
                            <option value=""><?php _e('All Time', 'ihowz-theme'); ?></option>
                            <option value="week"><?php _e('Last Week', 'ihowz-theme'); ?></option>
                            <option value="month"><?php _e('Last Month', 'ihowz-theme'); ?></option>
                            <option value="year"><?php _e('Last Year', 'ihowz-theme'); ?></option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="search-keywords"><?php _e('Search', 'ihowz-theme'); ?></label>
                        <input type="text" id="search-keywords" name="search" placeholder="<?php _e('Search keywords...', 'ihowz-theme'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><?php _e('Apply Filters', 'ihowz-theme'); ?></button>
                <button type="reset" class="btn btn-secondary"><?php _e('Clear', 'ihowz-theme'); ?></button>
            </form>
        </div>
    </section>

    <div class="container">
        <div class="resources-layout">

            <!-- Featured Resources & Quick Access -->
            <div class="resources-top-section">
                <div class="featured-resources">
                    <h2><?php _e('Featured Resources', 'ihowz-theme'); ?></h2>
                    <?php
                    $featured = get_field('featured_resources');
                    if ($featured) :
                        echo '<ul class="featured-list">';
                        foreach ($featured as $item) :
                            echo '<li>' . esc_html($item['resource_title']) . '</li>';
                        endforeach;
                        echo '</ul>';
                    else : ?>
                        <ul class="featured-list">
                            <li><?php _e('New Legislation Guide', 'ihowz-theme'); ?></li>
                            <li><?php _e('Popular Template Pack', 'ihowz-theme'); ?></li>
                            <li><?php _e('Expert Webinar Series', 'ihowz-theme'); ?></li>
                            <li><?php _e('Member Success Stories', 'ihowz-theme'); ?></li>
                        </ul>
                    <?php endif; ?>

                    <div class="member-benefit-cta">
                        <span class="cta-highlight"><?php _e('Members get unlimited downloads', 'ihowz-theme'); ?></span>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('join'))); ?>" class="btn btn-cta"><?php _e('Join Today', 'ihowz-theme'); ?></a>
                    </div>
                </div>

                <div class="quick-access">
                    <h3><?php _e('Quick Access', 'ihowz-theme'); ?></h3>
                    <ul class="quick-access-list">
                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>"><?php _e('Get Support', 'ihowz-theme'); ?></a></li>
                        <li><a href="#" id="most-downloaded"><?php _e('Most Downloaded', 'ihowz-theme'); ?></a></li>
                        <li><a href="#" id="recent-updates"><?php _e('Recent Updates', 'ihowz-theme'); ?></a></li>
                        <li><a href="#" id="bookmarked"><?php _e('Bookmarked Items', 'ihowz-theme'); ?></a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Resource Grid -->
    <section class="resource-grid-section">
        <div class="container">
            <h2><?php _e('All Resources', 'ihowz-theme'); ?></h2>
            <div class="resource-grid" id="resource-grid">
                <?php
                $resources_query = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($resources_query->have_posts()) :
                    while ($resources_query->have_posts()) : $resources_query->the_post();
                        ?>
                        <article class="resource-card">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="resource-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                            <?php endif; ?>

                            <div class="resource-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="resource-description"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>

                                <div class="resource-meta">
                                    <?php
                                    $file_type = get_field('file_type') ?: 'PDF';
                                    $file_size = get_field('file_size') ?: '2.5MB';
                                    $download_count = get_field('download_count') ?: '0';
                                    ?>
                                    <span class="file-type"><?php echo esc_html($file_type); ?></span>
                                    <span class="file-size"><?php echo esc_html($file_size); ?></span>
                                    <span class="download-count"><?php echo esc_html($download_count); ?> <?php _e('downloads', 'ihowz-theme'); ?></span>
                                </div>

                                <div class="resource-actions">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-small"><?php _e('View', 'ihowz-theme'); ?></a>
                                    <?php if (get_field('download_file')) : ?>
                                    <a href="<?php echo esc_url(get_field('download_file')['url']); ?>" class="btn btn-secondary btn-small" download><?php _e('Download', 'ihowz-theme'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p><?php _e('No resources found.', 'ihowz-theme'); ?></p>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <div class="load-more-section">
                <button class="btn btn-primary" id="load-more-resources"><?php _e('Load More Resources', 'ihowz-theme'); ?></button>
            </div>
        </div>
    </section>

    <!-- Professional Services -->
    <section class="professional-services-section">
        <div class="container">
            <h2><?php _e('Professional Services', 'ihowz-theme'); ?></h2>
            <div class="services-grid">
                <?php if (is_active_sidebar('resources-services')) : ?>
                    <?php dynamic_sidebar('resources-services'); ?>
                <?php else : ?>
                    <!-- Placeholder service ads -->
                    <?php for ($i = 1; $i <= 2; $i++) : ?>
                    <div class="service-card">
                        <div class="service-ad-space" style="width: 100%; height: 200px;">
                            <p><?php echo sprintf(__('Professional Service %d', 'ihowz-theme'), $i); ?></p>
                            <p><?php _e('Service description • Member discount • Contact details', 'ihowz-theme'); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Page Content -->
    <div class="container">
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>

    <?php endwhile; ?>

</main>

<script>
jQuery(document).ready(function($) {
    // Filter submission
    $('#resource-filters').on('submit', function(e) {
        e.preventDefault();
        // Here you would implement AJAX filtering
        console.log('Filters applied');
    });

    // Load more resources
    $('#load-more-resources').on('click', function() {
        // Here you would implement AJAX pagination
        console.log('Loading more resources');
    });
});
</script>

<?php get_footer(); ?>

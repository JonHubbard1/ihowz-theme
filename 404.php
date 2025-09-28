<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

<main class="site-main">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'ihowz-theme'); ?></h1>
        </div>

        <div class="error-404">
            <div class="error-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ihowz-theme'); ?></p>

                <div class="error-search">
                    <?php get_search_form(); ?>
                </div>

                <div class="error-links">
                    <h3><?php esc_html_e('Popular Pages', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ihowz-theme'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/news')); ?>"><?php esc_html_e('News', 'ihowz-theme'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/meetings')); ?>"><?php esc_html_e('Meetings', 'ihowz-theme'); ?></a></li>
                    </ul>
                </div>

                <?php
                // Show recent posts
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ));

                if ($recent_posts) :
                ?>
                    <div class="recent-posts">
                        <h3><?php esc_html_e('Recent Posts', 'ihowz-theme'); ?></h3>
                        <div class="posts-grid">
                            <?php foreach ($recent_posts as $post) : ?>
                                <article class="post-card">
                                    <div class="post-card-content">
                                        <h4>
                                            <a href="<?php echo get_permalink($post['ID']); ?>">
                                                <?php echo esc_html($post['post_title']); ?>
                                            </a>
                                        </h4>
                                        <div class="post-meta">
                                            <span><?php echo get_the_date('', $post['ID']); ?></span>
                                        </div>
                                        <div class="post-excerpt">
                                            <?php echo wp_trim_words($post['post_content'], 20); ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</main>

<style>
.error-404 {
    text-align: center;
    padding: 3rem 0;
}

.error-content {
    max-width: 600px;
    margin: 0 auto;
}

.error-search {
    margin: 2rem 0;
}

.error-links ul {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
}

.error-links a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.error-links a:hover {
    text-decoration: underline;
}

.recent-posts {
    margin-top: 3rem;
}

.recent-posts .posts-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}
</style>

<?php get_footer(); ?>
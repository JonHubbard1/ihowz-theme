<?php
/**
 * Template Name: Campaigns Hub
 *
 * Advocacy and action center with campaign tracking and tools
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main template-campaigns">

    <?php while (have_posts()) : the_post(); ?>

    <!-- Priority Campaign Hero -->
    <section class="priority-campaign-hero">
        <div class="container">
            <?php
            $priority_campaign = get_field('priority_campaign');
            if ($priority_campaign) :
                $campaign_title = $priority_campaign['campaign_title'];
                $campaign_description = $priority_campaign['campaign_description'];
                $participants = $priority_campaign['participants'] ?: '0';
                $target_percentage = $priority_campaign['target_percentage'] ?: '0';
                ?>
                <h1><?php echo esc_html($campaign_title); ?></h1>
                <p class="campaign-description"><?php echo esc_html($campaign_description); ?></p>
                <div class="campaign-progress-bar">
                    <div class="progress-fill" style="width: <?php echo esc_attr($target_percentage); ?>%;"></div>
                </div>
                <div class="campaign-stats">
                    <span class="stat-highlight"><?php echo number_format($participants); ?> <?php _e('Members Participated', 'ihowz-theme'); ?> | <?php echo esc_html($target_percentage); ?>% <?php _e('Target Reached', 'ihowz-theme'); ?></span>
                </div>
            <?php else : ?>
                <h1><?php _e('Current Priority Campaign', 'ihowz-theme'); ?></h1>
                <p><?php _e('Fight Unfair Legislation - Progress Bar & Impact Counter', 'ihowz-theme'); ?></p>
                <div class="campaign-stats">
                    <span class="stat-highlight"><?php _e('12,847 Members Participated | 89% Target Reached', 'ihowz-theme'); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <?php ihowz_theme_breadcrumb(); ?>
    </div>

    <!-- Active Campaigns & Impact -->
    <section class="campaigns-impact-section">
        <div class="container">
            <div class="campaigns-impact-grid">
                <!-- Active Campaigns -->
                <div class="active-campaigns-column">
                    <h2><?php _e('Active Campaigns', 'ihowz-theme'); ?></h2>

                    <?php
                    $active_campaigns = get_field('active_campaigns');
                    if ($active_campaigns) :
                        foreach ($active_campaigns as $campaign) : ?>
                        <div class="campaign-card">
                            <h3><?php echo esc_html($campaign['title']); ?></h3>
                            <p><?php echo esc_html($campaign['description']); ?></p>

                            <!-- Progress Indicator -->
                            <div class="campaign-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo esc_attr($campaign['progress']); ?>%;"></div>
                                </div>
                                <p class="progress-text"><?php echo esc_html($campaign['progress']); ?>% <?php _e('Complete', 'ihowz-theme'); ?></p>
                            </div>

                            <!-- Stats -->
                            <div class="campaign-meta">
                                <span class="participation-count"><?php echo number_format($campaign['participants']); ?> <?php _e('Participants', 'ihowz-theme'); ?></span>
                                <?php if ($campaign['deadline']) : ?>
                                <span class="deadline"><?php _e('Deadline:', 'ihowz-theme'); ?> <?php echo date('F j, Y', strtotime($campaign['deadline'])); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="campaign-actions">
                                <a href="<?php echo esc_url($campaign['action_url']); ?>" class="btn btn-cta"><?php _e('Take Action Now', 'ihowz-theme'); ?></a>
                                <a href="<?php echo esc_url($campaign['learn_more_url']); ?>" class="btn btn-primary"><?php _e('Learn More', 'ihowz-theme'); ?></a>
                            </div>
                        </div>
                        <?php endforeach;
                    else : ?>
                        <!-- Default campaign cards -->
                        <?php for ($i = 1; $i <= 2; $i++) : ?>
                        <div class="campaign-card">
                            <h3><?php echo sprintf(__('Campaign %d', 'ihowz-theme'), $i); ?></h3>
                            <p><?php _e('Title & Description • Progress indicator • Participation count • Deadline countdown • Quick action buttons', 'ihowz-theme'); ?></p>
                            <div class="campaign-actions">
                                <a href="#" class="btn btn-cta"><?php _e('Take Action Now', 'ihowz-theme'); ?></a>
                                <a href="#" class="btn btn-primary"><?php _e('Learn More', 'ihowz-theme'); ?></a>
                            </div>
                        </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <!-- Campaign Impact -->
                <div class="campaign-impact-column">
                    <h2><?php _e('Campaign Impact', 'ihowz-theme'); ?></h2>

                    <div class="impact-dashboard">
                        <?php
                        $total_savings = get_field('total_savings') ?: '2.3M';
                        $policy_changes = get_field('policy_changes_count') ?: '0';
                        $media_mentions = get_field('media_mentions') ?: '0';
                        $member_participation = get_field('member_participation_rate') ?: '0';
                        ?>

                        <div class="impact-stat-card">
                            <h3><?php _e('Member Savings This Year', 'ihowz-theme'); ?></h3>
                            <p class="stat-value">£<?php echo esc_html($total_savings); ?></p>
                        </div>

                        <div class="impact-stat-card">
                            <h3><?php _e('Policy Changes Achieved', 'ihowz-theme'); ?></h3>
                            <p class="stat-value"><?php echo esc_html($policy_changes); ?></p>
                        </div>

                        <div class="impact-stat-card">
                            <h3><?php _e('Media Coverage', 'ihowz-theme'); ?></h3>
                            <p class="stat-value"><?php echo esc_html($media_mentions); ?> <?php _e('mentions', 'ihowz-theme'); ?></p>
                        </div>

                        <div class="impact-stat-card">
                            <h3><?php _e('Member Participation', 'ihowz-theme'); ?></h3>
                            <p class="stat-value"><?php echo esc_html($member_participation); ?>%</p>
                        </div>
                    </div>

                    <!-- Success Metrics Details -->
                    <div class="success-metrics">
                        <h4><?php _e('What We Track', 'ihowz-theme'); ?></h4>
                        <ul>
                            <li><?php _e('Member participation stats', 'ihowz-theme'); ?></li>
                            <li><?php _e('Policy change tracking', 'ihowz-theme'); ?></li>
                            <li><?php _e('Media coverage highlights', 'ihowz-theme'); ?></li>
                            <li><?php _e('Government responses', 'ihowz-theme'); ?></li>
                        </ul>
                    </div>

                    <div class="impact-highlight">
                        <span class="highlight-text"><?php _e('£2.3M Saved for Members This Year', 'ihowz-theme'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advocacy Tools -->
    <section class="advocacy-tools-section">
        <div class="container">
            <h2><?php _e('Advocacy Tools', 'ihowz-theme'); ?></h2>
            <div class="advocacy-tools-grid">
                <div class="tool-card">
                    <h3><?php _e('Letter Builder', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Template library', 'ihowz-theme'); ?></li>
                        <li><?php _e('Personalization', 'ihowz-theme'); ?></li>
                        <li><?php _e('MP finder', 'ihowz-theme'); ?></li>
                    </ul>
                    <a href="#" class="btn btn-primary"><?php _e('Build Letter', 'ihowz-theme'); ?></a>
                </div>

                <div class="tool-card">
                    <h3><?php _e('Petition Manager', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Digital signatures', 'ihowz-theme'); ?></li>
                        <li><?php _e('Share tracking', 'ihowz-theme'); ?></li>
                        <li><?php _e('Auto-submission', 'ihowz-theme'); ?></li>
                    </ul>
                    <a href="#" class="btn btn-primary"><?php _e('Sign Petition', 'ihowz-theme'); ?></a>
                </div>

                <div class="tool-card">
                    <h3><?php _e('Social Media Kit', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Ready-made posts', 'ihowz-theme'); ?></li>
                        <li><?php _e('Hashtag campaigns', 'ihowz-theme'); ?></li>
                        <li><?php _e('Image assets', 'ihowz-theme'); ?></li>
                    </ul>
                    <a href="#" class="btn btn-primary"><?php _e('Get Kit', 'ihowz-theme'); ?></a>
                </div>

                <div class="tool-card">
                    <h3><?php _e('Event Organizer', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Local meetups', 'ihowz-theme'); ?></li>
                        <li><?php _e('Demonstration planning', 'ihowz-theme'); ?></li>
                        <li><?php _e('Volunteer coordination', 'ihowz-theme'); ?></li>
                    </ul>
                    <a href="#" class="btn btn-primary"><?php _e('Organize Event', 'ihowz-theme'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Campaign Archive & Success Stories -->
    <section class="campaign-archive-section">
        <div class="container">
            <h2><?php _e('Campaign Archive & Success Stories', 'ihowz-theme'); ?></h2>
            <div class="archive-grid">
                <?php
                // Query archived/completed campaigns
                $archived_campaigns = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post_type' => 'post',
                    'category_name' => 'campaigns',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($archived_campaigns->have_posts()) :
                    while ($archived_campaigns->have_posts()) : $archived_campaigns->the_post(); ?>
                    <article class="archive-campaign-card">
                        <h3><?php the_title(); ?></h3>
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="campaign-thumbnail">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="campaign-outcome">
                            <h4><?php _e('Outcome Achieved', 'ihowz-theme'); ?></h4>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        </div>
                        <?php
                        $impact_metrics = get_field('impact_metrics');
                        if ($impact_metrics) : ?>
                        <div class="impact-metrics">
                            <h5><?php _e('Impact Metrics', 'ihowz-theme'); ?></h5>
                            <p><?php echo esc_html($impact_metrics); ?></p>
                        </div>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read Full Story', 'ihowz-theme'); ?> &rarr;</a>
                    </article>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <!-- Default archive cards -->
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <article class="archive-campaign-card">
                        <h3><?php echo sprintf(__('Historic Campaign %d', 'ihowz-theme'), $i); ?></h3>
                        <div class="campaign-outcome">
                            <h4><?php _e('Outcome Achieved', 'ihowz-theme'); ?></h4>
                            <p><?php _e('Campaign overview • Impact metrics • Member testimonials', 'ihowz-theme'); ?></p>
                        </div>
                    </article>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>

            <div class="archive-actions">
                <a href="#" class="btn btn-primary"><?php _e('View All Campaigns', 'ihowz-theme'); ?></a>
            </div>
        </div>
    </section>

    <!-- Campaign Supporters & Allies -->
    <section class="campaign-supporters-section">
        <div class="container">
            <h2><?php _e('Campaign Supporters & Allies', 'ihowz-theme'); ?></h2>
            <div class="supporters-grid">
                <?php if (is_active_sidebar('campaign-supporters')) : ?>
                    <?php dynamic_sidebar('campaign-supporters'); ?>
                <?php else : ?>
                    <!-- Placeholder supporter cards -->
                    <?php
                    $supporter_types = array(
                        __('Legal Partner 1', 'ihowz-theme') => __('Pro Bono Services', 'ihowz-theme'),
                        __('Media Partner 2', 'ihowz-theme') => __('Campaign Coverage', 'ihowz-theme'),
                        __('Policy Advocate 3', 'ihowz-theme') => __('Government Relations', 'ihowz-theme'),
                        __('Industry Ally 4', 'ihowz-theme') => __('Coalition Support', 'ihowz-theme')
                    );
                    foreach ($supporter_types as $partner => $service) : ?>
                    <div class="supporter-card">
                        <div class="supporter-ad-space" style="width: 100%; height: 200px;">
                            <p><strong><?php echo $partner; ?></strong></p>
                            <p><?php echo $service; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

<?php get_footer(); ?>

<?php
/**
 * Template Name: Events & Meetings
 *
 * Interactive event calendar with live streaming and networking features
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main template-events">

    <?php while (have_posts()) : the_post(); ?>

    <!-- Featured Event Hero -->
    <section class="featured-event-hero">
        <div class="container">
            <?php
            $featured_event = get_field('featured_event');
            if ($featured_event) :
                $event_title = $featured_event['event_title'];
                $event_date = $featured_event['event_date'];
                $event_discount = $featured_event['early_bird_discount'];
                ?>
                <h1><?php echo esc_html($event_title); ?></h1>
                <?php if ($event_date) : ?>
                <p class="event-date"><?php echo esc_html($event_date); ?></p>
                <?php endif; ?>
                <?php if ($event_discount) : ?>
                <div class="event-discount-badge">
                    <span class="discount-highlight"><?php echo esc_html($event_discount); ?></span>
                </div>
                <?php endif; ?>
                <a href="#registration" class="btn btn-cta"><?php _e('Register Now', 'ihowz-theme'); ?></a>
            <?php else : ?>
                <h1><?php _e('Upcoming Flagship Event', 'ihowz-theme'); ?></h1>
                <p><?php _e('Featured event with countdown timer & registration CTA', 'ihowz-theme'); ?></p>
                <div class="event-discount-badge">
                    <span class="discount-highlight"><?php _e('Early Bird Discount - Save £50!', 'ihowz-theme'); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Calendar & Live Events -->
    <section class="calendar-live-section">
        <div class="container">
            <div class="calendar-live-grid">
                <!-- Interactive Calendar -->
                <div class="calendar-column">
                    <h2><?php _e('Interactive Event Calendar', 'ihowz-theme'); ?></h2>
                    <div class="calendar-view-options">
                        <button class="view-btn active" data-view="month"><?php _e('Month', 'ihowz-theme'); ?></button>
                        <button class="view-btn" data-view="week"><?php _e('Week', 'ihowz-theme'); ?></button>
                        <button class="view-btn" data-view="day"><?php _e('Day', 'ihowz-theme'); ?></button>
                    </div>
                    <div id="event-calendar" class="event-calendar">
                        <!-- Calendar will be rendered here by JavaScript -->
                        <p><?php _e('Loading calendar...', 'ihowz-theme'); ?></p>
                    </div>
                    <div class="calendar-filters">
                        <label><?php _e('Filter by:', 'ihowz-theme'); ?></label>
                        <select id="location-filter">
                            <option value=""><?php _e('All Locations', 'ihowz-theme'); ?></option>
                            <option value="online"><?php _e('Online', 'ihowz-theme'); ?></option>
                            <option value="london"><?php _e('London', 'ihowz-theme'); ?></option>
                            <option value="manchester"><?php _e('Manchester', 'ihowz-theme'); ?></option>
                            <option value="birmingham"><?php _e('Birmingham', 'ihowz-theme'); ?></option>
                        </select>
                        <select id="type-filter">
                            <option value=""><?php _e('All Types', 'ihowz-theme'); ?></option>
                            <option value="webinar"><?php _e('Webinars', 'ihowz-theme'); ?></option>
                            <option value="networking"><?php _e('Networking', 'ihowz-theme'); ?></option>
                            <option value="training"><?php _e('Training', 'ihowz-theme'); ?></option>
                            <option value="conference"><?php _e('Conferences', 'ihowz-theme'); ?></option>
                        </select>
                    </div>
                    <div class="calendar-actions">
                        <a href="#" class="btn btn-primary"><?php _e('View Calendar', 'ihowz-theme'); ?></a>
                        <a href="#" class="btn btn-secondary"><?php _e('My Schedule', 'ihowz-theme'); ?></a>
                    </div>
                </div>

                <!-- Live & Upcoming Events -->
                <div class="live-events-column">
                    <h2><?php _e('Live & Upcoming Events', 'ihowz-theme'); ?></h2>

                    <!-- Live Event Indicator -->
                    <?php
                    $live_event = get_field('live_event');
                    if ($live_event) : ?>
                    <div class="live-event-card">
                        <div class="live-indicator">
                            <span class="live-badge"><?php _e('LIVE NOW', 'ihowz-theme'); ?></span>
                        </div>
                        <h3><?php echo esc_html($live_event['event_title']); ?></h3>
                        <p><?php echo esc_html($live_event['event_description']); ?></p>
                        <a href="<?php echo esc_url($live_event['streaming_link']); ?>" class="btn btn-cta"><?php _e('Join Live Stream', 'ihowz-theme'); ?></a>
                    </div>
                    <?php endif; ?>

                    <!-- Upcoming Events List -->
                    <div class="upcoming-events-list">
                        <?php
                        // Query upcoming events (using regular posts for now, could be custom post type)
                        $upcoming_events = new WP_Query(array(
                            'posts_per_page' => 5,
                            'post_status' => 'publish',
                            'meta_key' => 'event_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => 'event_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            )
                        ));

                        if ($upcoming_events->have_posts()) :
                            while ($upcoming_events->have_posts()) : $upcoming_events->the_post();
                                $event_date = get_field('event_date');
                                $event_time = get_field('event_time');
                                $event_location = get_field('event_location');
                                ?>
                                <div class="upcoming-event-item">
                                    <h4><?php the_title(); ?></h4>
                                    <?php if ($event_date) : ?>
                                    <p class="event-date"><?php echo date('F j, Y', strtotime($event_date)); ?></p>
                                    <?php endif; ?>
                                    <?php if ($event_time) : ?>
                                    <p class="event-time"><?php echo esc_html($event_time); ?></p>
                                    <?php endif; ?>
                                    <?php if ($event_location) : ?>
                                    <p class="event-location"><?php echo esc_html($event_location); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-small btn-secondary"><?php _e('Quick Register', 'ihowz-theme'); ?></a>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else : ?>
                            <p><?php _e('No upcoming events scheduled.', 'ihowz-theme'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Event Recordings -->
                    <div class="event-recordings">
                        <h4><?php _e('Event Recordings', 'ihowz-theme'); ?></h4>
                        <p><?php _e('Access past event recordings and materials.', 'ihowz-theme'); ?></p>
                        <a href="#" class="btn btn-primary btn-small"><?php _e('View Recordings', 'ihowz-theme'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Categories -->
    <section class="event-categories-section">
        <div class="container">
            <h2><?php _e('Event Categories', 'ihowz-theme'); ?></h2>
            <div class="event-categories-grid">
                <div class="category-card">
                    <h3><?php _e('Webinars', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Expert presentations', 'ihowz-theme'); ?></li>
                        <li><?php _e('Q&A sessions', 'ihowz-theme'); ?></li>
                        <li><?php _e('CPD certified', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <div class="category-card">
                    <h3><?php _e('Networking', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Regional meetups', 'ihowz-theme'); ?></li>
                        <li><?php _e('Online forums', 'ihowz-theme'); ?></li>
                        <li><?php _e('Business mixer', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <div class="category-card">
                    <h3><?php _e('Training', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Compliance workshops', 'ihowz-theme'); ?></li>
                        <li><?php _e('Best practice sessions', 'ihowz-theme'); ?></li>
                        <li><?php _e('Certification courses', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <div class="category-card">
                    <h3><?php _e('Conferences', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Annual convention', 'ihowz-theme'); ?></li>
                        <li><?php _e('Industry summits', 'ihowz-theme'); ?></li>
                        <li><?php _e('Policy updates', 'ihowz-theme'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Member Features -->
    <section class="member-features-section">
        <div class="container">
            <div class="member-features-grid">
                <div class="feature-card">
                    <h3><?php _e('Member Networking', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Member directory', 'ihowz-theme'); ?></li>
                        <li><?php _e('Connect requests', 'ihowz-theme'); ?></li>
                        <li><?php _e('Discussion forums', 'ihowz-theme'); ?></li>
                        <li><?php _e('Mentorship program', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h3><?php _e('Event Resources', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Presentation slides', 'ihowz-theme'); ?></li>
                        <li><?php _e('Recording access', 'ihowz-theme'); ?></li>
                        <li><?php _e('Discussion notes', 'ihowz-theme'); ?></li>
                        <li><?php _e('Follow-up materials', 'ihowz-theme'); ?></li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h3><?php _e('Feedback & Ratings', 'ihowz-theme'); ?></h3>
                    <ul>
                        <li><?php _e('Event reviews', 'ihowz-theme'); ?></li>
                        <li><?php _e('Speaker ratings', 'ihowz-theme'); ?></li>
                        <li><?php _e('Improvement suggestions', 'ihowz-theme'); ?></li>
                        <li><?php _e('Photo sharing', 'ihowz-theme'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Sponsors & Partners -->
    <section class="event-sponsors-section">
        <div class="container">
            <h2><?php _e('Event Sponsors & Partners', 'ihowz-theme'); ?></h2>
            <div class="event-sponsors-grid">
                <?php if (is_active_sidebar('event-sponsors')) : ?>
                    <?php dynamic_sidebar('event-sponsors'); ?>
                <?php else : ?>
                    <!-- Placeholder sponsor cards -->
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="sponsor-card">
                        <div class="sponsor-ad-space" style="width: 100%; height: 250px;">
                            <p><?php echo sprintf(__('Event Sponsor %d', 'ihowz-theme'), $i); ?></p>
                            <p><?php _e('Special Offers • Networking Booth • Product Demos', 'ihowz-theme'); ?></p>
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

<?php get_footer(); ?>

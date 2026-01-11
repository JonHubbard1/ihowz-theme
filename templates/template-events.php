<?php
/**
 * Template Name: Events & Meetings
 *
 * Interactive event calendar with live streaming and networking features
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header();

// Check if viewing a single event
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
?>

<main id="primary" class="site-main template-events">

    <?php while (have_posts()) : the_post(); ?>

    <?php if ($event_id) : ?>
        <!-- Single Event View -->
        <section class="single-event-section">
            <div class="container">
                <?php echo do_shortcode('[ihowz_event_booking id="' . $event_id . '"]'); ?>
            </div>
        </section>

    <?php else : ?>
        <!-- Events Listing View -->

        <!-- Page Hero -->
        <section class="events-hero">
            <div class="container">
                <h1><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="page-subtitle"><?php echo get_the_excerpt(); ?></p>
                <?php else : ?>
                    <p class="page-subtitle"><?php _e('Discover upcoming events, webinars, training sessions, and networking opportunities', 'ihowz-theme'); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Breadcrumb -->
        <div class="container">
            <?php if (function_exists('ihowz_theme_breadcrumb')) ihowz_theme_breadcrumb(); ?>
        </div>

        <!-- Calendar Section -->
        <section class="calendar-section">
            <div class="container">
                <h2><?php _e('Event Calendar', 'ihowz-theme'); ?></h2>
                <?php echo do_shortcode('[ihowz_events_calendar]'); ?>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section class="upcoming-events-section">
            <div class="container">
                <h2><?php _e('Upcoming Events', 'ihowz-theme'); ?></h2>
                <?php echo do_shortcode('[ihowz_upcoming_events limit="5"]'); ?>
            </div>
        </section>

        <!-- All Events Grid -->
        <section class="events-grid-section">
            <div class="container">
                <div class="section-header">
                    <h2><?php _e('All Events', 'ihowz-theme'); ?></h2>
                    <div class="events-filters">
                        <button class="filter-btn active" data-type=""><?php _e('All', 'ihowz-theme'); ?></button>
                        <button class="filter-btn" data-type="meeting"><?php _e('Meetings', 'ihowz-theme'); ?></button>
                        <button class="filter-btn" data-type="show"><?php _e('Shows', 'ihowz-theme'); ?></button>
                        <button class="filter-btn" data-type="training"><?php _e('Training', 'ihowz-theme'); ?></button>
                        <button class="filter-btn" data-type="webinar"><?php _e('Webinars', 'ihowz-theme'); ?></button>
                        <button class="filter-btn" data-type="forum"><?php _e('Forums', 'ihowz-theme'); ?></button>
                    </div>
                </div>

                <div id="events-container">
                    <?php echo do_shortcode('[ihowz_events limit="12" view="grid"]'); ?>
                </div>
            </div>
        </section>

        <!-- Event Categories -->
        <section class="event-categories-section">
            <div class="container">
                <h2><?php _e('Event Categories', 'ihowz-theme'); ?></h2>
                <div class="event-categories-grid">
                    <a href="?type=webinar" class="category-card">
                        <span class="dashicons dashicons-video-alt2"></span>
                        <h3><?php _e('Webinars', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Expert presentations, Q&A sessions, and CPD certified content', 'ihowz-theme'); ?></p>
                    </a>

                    <a href="?type=meeting" class="category-card">
                        <span class="dashicons dashicons-groups"></span>
                        <h3><?php _e('Networking', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Regional meetups, online forums, and business mixers', 'ihowz-theme'); ?></p>
                    </a>

                    <a href="?type=training" class="category-card">
                        <span class="dashicons dashicons-welcome-learn-more"></span>
                        <h3><?php _e('Training', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Compliance workshops and best practice sessions', 'ihowz-theme'); ?></p>
                    </a>

                    <a href="?type=conference" class="category-card">
                        <span class="dashicons dashicons-megaphone"></span>
                        <h3><?php _e('Conferences', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Major industry events with keynote speakers', 'ihowz-theme'); ?></p>
                    </a>

                    <a href="?type=show" class="category-card">
                        <span class="dashicons dashicons-tickets-alt"></span>
                        <h3><?php _e('Shows', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Trade shows and exhibitions', 'ihowz-theme'); ?></p>
                    </a>

                    <a href="?type=forum" class="category-card">
                        <span class="dashicons dashicons-format-chat"></span>
                        <h3><?php _e('Forums', 'ihowz-theme'); ?></h3>
                        <p><?php _e('Discussion forums and panel sessions', 'ihowz-theme'); ?></p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Member Benefits -->
        <section class="member-benefits-section">
            <div class="container">
                <div class="benefits-content">
                    <h2><?php _e('Member Benefits', 'ihowz-theme'); ?></h2>
                    <p><?php _e('iHowz members enjoy exclusive access to events and special pricing', 'ihowz-theme'); ?></p>
                    <ul class="benefits-list">
                        <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Early access to event registration', 'ihowz-theme'); ?></li>
                        <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Member-only discounted pricing', 'ihowz-theme'); ?></li>
                        <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Exclusive networking events', 'ihowz-theme'); ?></li>
                        <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Access to event recordings', 'ihowz-theme'); ?></li>
                    </ul>
                    <?php if (!is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-cta"><?php _e('Become a Member', 'ihowz-theme'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    <?php endif; ?>

    <?php endwhile; ?>

</main>

<style>
/* Events Template Styles */

.template-events .events-hero {
    background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.template-events .events-hero h1 {
    margin: 0 0 15px;
    font-size: 2.5rem;
}

.template-events .events-hero .page-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

/* Calendar Section - Full Width */
.template-events .calendar-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.template-events .calendar-section h2 {
    margin-bottom: 20px;
    color: #1e3a5f;
}

.template-events .ihowz-events-calendar-container {
    width: 100%;
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.template-events #ihowz-events-calendar {
    width: 100% !important;
    min-height: 500px;
}

/* FullCalendar overrides */
.template-events .fc {
    width: 100% !important;
}

.template-events .fc-view-harness {
    width: 100% !important;
}

/* Upcoming Events Section */
.template-events .upcoming-events-section {
    padding: 40px 0;
}

.template-events .upcoming-events-section h2 {
    margin-bottom: 20px;
    color: #1e3a5f;
}

/* Upcoming events widget - horizontal layout for full-width section */
.template-events .ihowz-upcoming-events-widget .upcoming-events-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.template-events .ihowz-upcoming-events-widget .upcoming-event-item {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.template-events .ihowz-upcoming-events-widget .upcoming-event-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.template-events .ihowz-upcoming-events-widget .event-date-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1e3a5f;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    min-width: 50px;
}

.template-events .ihowz-upcoming-events-widget .event-date-mini .day {
    font-size: 1.4rem;
    font-weight: bold;
    line-height: 1;
}

.template-events .ihowz-upcoming-events-widget .event-date-mini .month {
    font-size: 0.75rem;
    text-transform: uppercase;
}

.template-events .ihowz-upcoming-events-widget .event-info {
    flex: 1;
}

.template-events .ihowz-upcoming-events-widget .event-info .event-title {
    display: block;
    font-weight: 600;
    color: #1e3a5f;
    text-decoration: none;
    margin-bottom: 4px;
}

.template-events .ihowz-upcoming-events-widget .event-info .event-title:hover {
    color: #2c5282;
}

.template-events .ihowz-upcoming-events-widget .event-info .event-location {
    font-size: 0.85rem;
    color: #666;
}

.template-events .ihowz-upcoming-events-widget .event-price-mini {
    font-weight: 600;
    color: #2ecc71;
}

/* Mobile view - hide calendar completely */
@media (max-width: 768px) {
    .template-events .calendar-section {
        display: none;
    }
}

.template-events .events-grid-section {
    padding: 60px 0;
}

.template-events .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.template-events .section-header h2 {
    margin: 0;
    color: #1e3a5f;
}

.template-events .events-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.template-events .filter-btn {
    padding: 8px 16px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
}

.template-events .filter-btn:hover,
.template-events .filter-btn.active {
    background: #1e3a5f;
    color: white;
    border-color: #1e3a5f;
}

.template-events .event-categories-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.template-events .event-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.template-events .category-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.template-events .category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.template-events .category-card .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    color: #1e3a5f;
    margin-bottom: 15px;
}

.template-events .category-card h3 {
    margin: 0 0 10px;
    color: #1e3a5f;
}

.template-events .category-card p {
    margin: 0;
    color: #666;
    font-size: 0.95rem;
}

.template-events .member-benefits-section {
    padding: 60px 0;
    background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
    color: white;
}

.template-events .benefits-content {
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}

.template-events .benefits-content h2 {
    color: white;
    margin-bottom: 15px;
}

.template-events .benefits-list {
    list-style: none;
    padding: 0;
    margin: 30px 0;
    text-align: left;
    display: inline-block;
}

.template-events .benefits-list li {
    padding: 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.template-events .benefits-list .dashicons {
    color: #48bb78;
}

.template-events .btn-cta {
    display: inline-block;
    background: #f6ad55;
    color: #1e3a5f;
    padding: 15px 30px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.template-events .btn-cta:hover {
    background: #ed8936;
    transform: scale(1.05);
}

.template-events .single-event-section {
    padding: 40px 0;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Event type filter buttons
    $('.events-filters .filter-btn').on('click', function() {
        var type = $(this).data('type');

        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        // Reload events with filter
        var url = '<?php echo admin_url('admin-ajax.php'); ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                action: 'ihowz_filter_events',
                type: type,
                nonce: '<?php echo wp_create_nonce('ihowz_filter_events'); ?>'
            },
            beforeSend: function() {
                $('#events-container').addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    $('#events-container').html(response.data.html);
                }
            },
            complete: function() {
                $('#events-container').removeClass('loading');
            }
        });
    });
});
</script>

<?php get_footer(); ?>

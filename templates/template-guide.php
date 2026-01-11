<?php
/**
 * Template Name: Step-by-Step Guide
 *
 * Interactive guide format with progressive disclosure and checklists
 *
 * @package iHowz
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main template-guide">

    <?php while (have_posts()) : the_post(); ?>

    <!-- Guide Hero Section -->
    <section class="guide-hero">
        <div class="container">
            <h1 class="guide-title"><?php the_title(); ?></h1>
            <div class="guide-meta">
                <?php
                $completion_time = get_field('completion_time');
                $difficulty_level = get_field('difficulty_level');
                $prerequisites = get_field('prerequisites');
                ?>
                <?php if ($completion_time) : ?>
                <span class="completion-time"><?php _e('Est. Time:', 'ihowz-theme'); ?> <?php echo esc_html($completion_time); ?></span>
                <?php endif; ?>
                <?php if ($difficulty_level) : ?>
                <span class="difficulty"><?php _e('Difficulty:', 'ihowz-theme'); ?> <?php echo esc_html($difficulty_level); ?></span>
                <?php endif; ?>
                <?php if ($prerequisites) : ?>
                <span class="prerequisites"><?php _e('Prerequisites:', 'ihowz-theme'); ?> <?php echo esc_html($prerequisites); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Guide Content -->
    <div class="container">
        <?php ihowz_theme_breadcrumb(); ?>
        <div class="guide-layout">

            <!-- Step Navigation Sidebar -->
            <aside class="step-navigation">
                <h3><?php _e('Steps', 'ihowz-theme'); ?></h3>
                <?php
                // Get guide steps from ACF repeater
                $steps = get_field('guide_steps');
                if ($steps) : ?>
                <ul class="step-list">
                    <?php foreach ($steps as $index => $step) :
                        $step_number = $index + 1;
                        $is_active = ($index === 0) ? 'active' : '';
                        ?>
                    <li class="step-item <?php echo $is_active; ?>" data-step="<?php echo $step_number; ?>">
                        <span class="step-number"><?php echo $step_number; ?></span>
                        <span class="step-title"><?php echo esc_html($step['step_title']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Progress Indicator -->
                <div class="progress-indicator">
                    <div class="progress-label"><?php _e('Progress', 'ihowz-theme'); ?></div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%;"></div>
                    </div>
                    <div class="progress-percentage">0% <?php _e('Complete', 'ihowz-theme'); ?></div>
                </div>
                <?php endif; ?>
            </aside>

            <!-- Step Content -->
            <div class="step-content-area">
                <?php if ($steps) : ?>
                    <?php foreach ($steps as $index => $step) :
                        $step_number = $index + 1;
                        $is_active = ($index === 0) ? 'active' : '';
                        ?>
                    <div class="step-content <?php echo $is_active; ?>" id="step-<?php echo $step_number; ?>">
                        <h2><?php echo esc_html($step['step_title']); ?></h2>
                        <div class="step-description">
                            <?php echo wpautop($step['step_description']); ?>
                        </div>

                        <!-- Interactive Checklist -->
                        <?php if (!empty($step['checklist_items'])) : ?>
                        <div class="checklist">
                            <h4><?php _e('Checklist', 'ihowz-theme'); ?></h4>
                            <ul class="checklist-items">
                                <?php foreach ($step['checklist_items'] as $item) : ?>
                                <li>
                                    <label>
                                        <input type="checkbox" class="checklist-checkbox">
                                        <span><?php echo esc_html($item['item_text']); ?></span>
                                    </label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <!-- Required Documents -->
                        <?php if (!empty($step['required_documents'])) : ?>
                        <div class="required-documents">
                            <h4><?php _e('Required Documents', 'ihowz-theme'); ?></h4>
                            <ul class="document-list">
                                <?php foreach ($step['required_documents'] as $doc) : ?>
                                <li><?php echo esc_html($doc['document_name']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <!-- Video Tutorial -->
                        <?php if (!empty($step['video_url'])) : ?>
                        <div class="video-tutorial">
                            <h4><?php _e('Video Tutorial', 'ihowz-theme'); ?></h4>
                            <div class="video-embed">
                                <?php echo wp_oembed_get($step['video_url']); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Template Downloads -->
                        <?php if (!empty($step['template_downloads'])) : ?>
                        <div class="template-downloads">
                            <h4><?php _e('Download Templates', 'ihowz-theme'); ?></h4>
                            <ul class="template-list">
                                <?php foreach ($step['template_downloads'] as $template) : ?>
                                <li>
                                    <a href="<?php echo esc_url($template['file']['url']); ?>" class="template-download" download>
                                        <?php echo esc_html($template['template_name']); ?>
                                        <span class="file-size"><?php echo size_format($template['file']['filesize']); ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <!-- Common Mistakes -->
                        <?php if (!empty($step['common_mistakes'])) : ?>
                        <div class="common-mistakes">
                            <h4><?php _e('Common Mistakes to Avoid', 'ihowz-theme'); ?></h4>
                            <?php echo wpautop($step['common_mistakes']); ?>
                        </div>
                        <?php endif; ?>

                        <!-- Step Navigation Buttons -->
                        <div class="step-navigation-buttons">
                            <?php if ($step_number > 1) : ?>
                            <button class="btn btn-secondary prev-step" data-prev="<?php echo $step_number - 1; ?>">&larr; <?php _e('Previous Step', 'ihowz-theme'); ?></button>
                            <?php endif; ?>

                            <?php if ($step_number < count($steps)) : ?>
                            <button class="btn btn-primary next-step" data-next="<?php echo $step_number + 1; ?>"><?php _e('Next Step', 'ihowz-theme'); ?> &rarr;</button>
                            <?php endif; ?>

                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-cta"><?php _e('Get Expert Help', 'ihowz-theme'); ?></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Fallback to main content if no steps defined -->
                    <div class="step-content active">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Tools & Resources -->
    <section class="tools-resources-section">
        <div class="container">
            <h2><?php _e('Tools & Resources', 'ihowz-theme'); ?></h2>
            <div class="tools-grid">
                <?php
                $tools = get_field('tools_resources');
                if ($tools) :
                    foreach ($tools as $tool) : ?>
                    <div class="tool-card">
                        <h4><?php echo esc_html($tool['tool_title']); ?></h4>
                        <p><?php echo esc_html($tool['tool_description']); ?></p>
                        <?php if ($tool['tool_link']) : ?>
                        <a href="<?php echo esc_url($tool['tool_link']); ?>" class="tool-link"><?php _e('Access Tool', 'ihowz-theme'); ?> &rarr;</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach;
                else : ?>
                    <!-- Default tools -->
                    <div class="tool-card"><h4><?php _e('Document Template', 'ihowz-theme'); ?></h4></div>
                    <div class="tool-card"><h4><?php _e('Legal Checklist', 'ihowz-theme'); ?></h4></div>
                    <div class="tool-card"><h4><?php _e('Video Tutorial', 'ihowz-theme'); ?></h4></div>
                    <div class="tool-card"><h4><?php _e('Expert Q&A', 'ihowz-theme'); ?></h4></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Relevant Services -->
    <section class="relevant-services-section">
        <div class="container">
            <h2><?php _e('Relevant Services', 'ihowz-theme'); ?></h2>
            <div class="services-grid">
                <?php if (is_active_sidebar('guide-services')) : ?>
                    <?php dynamic_sidebar('guide-services'); ?>
                <?php else : ?>
                    <!-- Placeholder service ads -->
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="service-card">
                        <div class="service-ad-space" style="width: 100%; height: 250px;">
                            <p><?php echo sprintf(__('Service Partner %d', 'ihowz-theme'), $i); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php endwhile; ?>

</main>

<script>
jQuery(document).ready(function($) {
    // Step navigation
    $('.next-step').on('click', function() {
        var nextStep = $(this).data('next');
        navigateToStep(nextStep);
    });

    $('.prev-step').on('click', function() {
        var prevStep = $(this).data('prev');
        navigateToStep(prevStep);
    });

    $('.step-item').on('click', function() {
        var step = $(this).data('step');
        navigateToStep(step);
    });

    function navigateToStep(stepNumber) {
        $('.step-content').removeClass('active');
        $('.step-item').removeClass('active');

        $('#step-' + stepNumber).addClass('active');
        $('.step-item[data-step="' + stepNumber + '"]').addClass('active');

        updateProgress();

        // Scroll to top of content
        $('html, body').animate({
            scrollTop: $('#step-' + stepNumber).offset().top - 100
        }, 400);
    }

    // Update progress based on checked items
    function updateProgress() {
        var totalCheckboxes = $('.checklist-checkbox').length;
        var checkedCheckboxes = $('.checklist-checkbox:checked').length;

        if (totalCheckboxes > 0) {
            var percentage = Math.round((checkedCheckboxes / totalCheckboxes) * 100);
            $('.progress-fill').css('width', percentage + '%');
            $('.progress-percentage').text(percentage + '% <?php _e('Complete', 'ihowz-theme'); ?>');
        }
    }

    // Listen to checkbox changes
    $('.checklist-checkbox').on('change', updateProgress);
});
</script>

<?php get_footer(); ?>

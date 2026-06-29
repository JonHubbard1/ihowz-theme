<?php
/**
 * Template Name: Login Page
 * Template Post Type: page
 *
 * A centered, branded login page using the iHowz corporate design system.
 */

get_header(); ?>

<main class="site-main site-main-login">
    <div class="login-page-container">
        <div class="login-page-card">
            <?php
            // Use the iHowz login shortcode with corporate branding
            $logo_url = ihowz_get_theme_logo_url();
            echo do_shortcode('[ihowz_login logo="' . esc_url($logo_url) . '" title="' . esc_attr__('Welcome Back', 'ihowz-theme') . '" subtitle="' . esc_attr__('Sign in to your iHowz account', 'ihowz-theme') . '" redirect="' . esc_url(home_url('/member-portal/')) . '" show_lost_password="true"]');
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>

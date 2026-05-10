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
            $logo_url = 'https://ihowz.greatnew.site/wp-content/uploads/2025/07/short-ihowz-logobmp-6cm.jpg';
            echo do_shortcode('[ihowz_login logo="' . esc_url($logo_url) . '" title="Welcome Back" subtitle="Sign in to your iHowz account" redirect="' . esc_url(home_url('/member-portal/')) . '" show_lost_password="true"]');
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>

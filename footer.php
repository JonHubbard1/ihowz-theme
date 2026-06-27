<!-- Advertising Bar Section -->
    <?php if (is_active_sidebar('advertising-bar')) : ?>
        <section class="advertising-bar">
            <div class="advertising-container">
                <?php dynamic_sidebar('advertising-bar'); ?>
            </div>
        </section>
    <?php endif; ?>

    <footer id="colophon" class="site-footer footer-bar">
        <div class="footer-container">
            <div class="footer-content">

                <!-- Logo Column -->
                <div class="footer-section footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <img src="/wp-content/uploads/2025/07/short-ihowz-logobmp-6cm-244-80.jpg" alt="<?php bloginfo('name'); ?>" class="footer-logo-img">
                    </a>
                    <p class="footer-company-info">iHowz Landlords' Association</p>
                    <p class="footer-company-details">Riverside Centre, River Lawn Road,<br>Tonbridge, TN9 1EP</p>
                    <p class="footer-company-details"><a href="tel:01732565601">01732 56 56 01</a><br><a href="mailto:info@ihowz.uk">info@ihowz.uk</a></p>
                    <p class="footer-company-details"><small>Company No. 06935628</small></p>
                </div>

                <!-- Column 2 - Quick Links -->
                <div class="footer-section footer-resources-menu">
                    <h3 class="foot-menu-header">Quick Links</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'ihowz_theme_footer_fallback_menu',
                    ));
                    ?>
                </div>

                <!-- Column 3 - Resources -->
                <div class="footer-section footer-resources-menu">
                    <h3 class="foot-menu-header">Resources</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-resources',
                        'container'      => false,
                        'fallback_cb'    => 'ihowz_theme_footer_resources_fallback_menu',
                    ));
                    ?>
                </div>

                <!-- Column 4 - Information -->
                <div class="footer-section footer-resources-menu">
                    <h3 class="foot-menu-header">Information</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-information',
                        'container'      => false,
                        'fallback_cb'    => 'ihowz_theme_footer_info_fallback_menu',
                    ));
                    ?>
                </div>

            </div>

            <?php $ihowz_footer_social_icons = ihowz_get_social_icons_html( 20, 'footer-social-icon' ); ?>
            <?php if ( $ihowz_footer_social_icons ) : ?>
                <!-- Social Icons Section -->
                <div class="footer-social-icons">
                    <?php echo $ihowz_footer_social_icons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>

            <!-- Legal Links -->
            <div class="footer-legal-links">
                <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms &amp; Conditions</a>
                <span class="footer-legal-separator">|</span>
                <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie Policy</a>
                <span class="footer-legal-separator">|</span>
                <a href="#" data-ihowz-cookie-settings="1">Cookie Settings</a>
                <span class="footer-legal-separator">|</span>
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
            </div>

            <!-- Copyright Section -->
            <div class="footer-bottom">
                <p class="footer-copyright-text">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * Fallback footer menu - Quick Links
 */
function ihowz_theme_footer_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url() . '">Home</a></li>';
    echo '<li><a href="' . home_url('/news') . '">News</a></li>';
    echo '<li><a href="' . home_url('/meetings') . '">Meetings</a></li>';
    echo '<li><a href="' . home_url('/join-now') . '">Join iHowz</a></li>';
    echo '</ul>';
}

/**
 * Fallback footer menu - Resources
 */
function ihowz_theme_footer_resources_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url('/landlords') . '">Landlord Guides</a></li>';
    echo '<li><a href="' . home_url('/landlord-advice') . '">Landlord Advice</a></li>';
    echo '<li><a href="' . home_url('/document-library') . '">Document Library</a></li>';
    echo '<li><a href="' . home_url('/member-offers') . '">Member Offers</a></li>';
    echo '</ul>';
}

/**
 * Fallback footer menu - Information
 */
function ihowz_theme_footer_info_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url('/terms-and-conditions/') . '">Terms &amp; Conditions</a></li>';
    echo '<li><a href="' . home_url('/cookie-policy/') . '">Cookie Policy</a></li>';
    echo '<li><a href="' . home_url('/privacy-policy/') . '">Privacy Policy</a></li>';
    echo '<li><a href="' . home_url('/campaigns') . '">Campaigns</a></li>';
    echo '</ul>';
}
?>

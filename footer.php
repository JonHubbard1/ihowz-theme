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
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/2025/07/short-ihowz-logobmp-6cm-244-80.jpg')); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img">
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

            <!-- Social Icons Section -->
            <div class="footer-social-icons">
                <a href="https://www.facebook.com/ihowznews" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://x.com/i_howz" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.linkedin.com/company/ihowz" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <a href="https://www.youtube.com/channel/UCaXtxzbZ1Lxqs1sps1klCtA" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/><polygon fill="#fff" points="9.545,15.568 15.818,12 9.545,8.432"/></svg>
                </a>
            </div>

            <!-- Legal Links -->
            <div class="footer-legal-links">
                <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms &amp; Conditions</a>
                <span class="footer-legal-separator">|</span>
                <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie Policy</a>
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

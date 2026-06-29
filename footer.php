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
                        <img src="<?php echo esc_url(ihowz_get_theme_logo_url()); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img">
                    </a>
                    <p class="footer-company-info"><?php esc_html_e('iHowz Landlords\' Association', 'ihowz-theme'); ?></p>
                    <p class="footer-company-details"><?php echo esc_html__('Marlowe Innovation Centre, Marlowe Way,', 'ihowz-theme') . '<br>' . esc_html__('Ramsgate, CT12 6FA', 'ihowz-theme'); ?></p>
                    <p class="footer-company-details"><a href="tel:01732565601">01732 56 56 01</a><br><a href="mailto:info@ihowz.uk">info@ihowz.uk</a></p>
                    <p class="footer-company-details"><small><?php esc_html_e('Company No. 06935628', 'ihowz-theme'); ?></small></p>
                </div>

                <!-- Column 2 - Quick Links -->
                <div class="footer-section footer-resources-menu">
                    <h3 class="foot-menu-header"><?php esc_html_e('Quick Links', 'ihowz-theme'); ?></h3>
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
                    <h3 class="foot-menu-header"><?php esc_html_e('Resources', 'ihowz-theme'); ?></h3>
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
                    <h3 class="foot-menu-header"><?php esc_html_e('Information', 'ihowz-theme'); ?></h3>
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
                <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>"><?php esc_html_e('Terms &amp; Conditions', 'ihowz-theme'); ?></a>
                <span class="footer-legal-separator">|</span>
                <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>"><?php esc_html_e('Cookie Policy', 'ihowz-theme'); ?></a>
                <span class="footer-legal-separator">|</span>
                <a href="#" data-ihowz-cookie-settings="1"><?php esc_html_e('Cookie Settings', 'ihowz-theme'); ?></a>
                <span class="footer-legal-separator">|</span>
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'ihowz-theme'); ?></a>
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
    echo '<li><a href="' . esc_url(home_url()) . '">' . esc_html__('Home', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/news')) . '">' . esc_html__('News', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/meetings')) . '">' . esc_html__('Meetings', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/join-now')) . '">' . esc_html__('Join iHowz', 'ihowz-theme') . '</a></li>';
    echo '</ul>';
}

/**
 * Fallback footer menu - Resources
 */
function ihowz_theme_footer_resources_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/landlords')) . '">' . esc_html__('Landlord Guides', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/landlord-advice')) . '">' . esc_html__('Landlord Advice', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/document-library')) . '">' . esc_html__('Document Library', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/member-offers')) . '">' . esc_html__('Member Offers', 'ihowz-theme') . '</a></li>';
    echo '</ul>';
}

/**
 * Fallback footer menu - Information
 */
function ihowz_theme_footer_info_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/terms-and-conditions/')) . '">' . esc_html__('Terms &amp; Conditions', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/cookie-policy/')) . '">' . esc_html__('Cookie Policy', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/privacy-policy/')) . '">' . esc_html__('Privacy Policy', 'ihowz-theme') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/campaigns')) . '">' . esc_html__('Campaigns', 'ihowz-theme') . '</a></li>';
    echo '</ul>';
}
?>

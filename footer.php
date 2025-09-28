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
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'ihowz_theme_footer_fallback_menu',
                    ));
                    ?>
                </div>

                <!-- Column 4 - Information -->
                <div class="footer-section footer-resources-menu">
                    <h3 class="foot-menu-header">Information</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'ihowz_theme_footer_fallback_menu',
                    ));
                    ?>
                </div>

            </div>

            <!-- Social Icons Section -->
            <div class="footer-social-icons">
                <a href="#" class="footer-social-icon">f</a>
                <a href="#" class="footer-social-icon">t</a>
                <a href="#" class="footer-social-icon">in</a>
                <a href="#" class="footer-social-icon">ig</a>
            </div>

            <!-- Copyright Section -->
            <div class="footer-bottom">
                <p class="footer-copyright-text">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * Fallback footer menu
 */
function ihowz_theme_footer_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url() . '">Home</a></li>';
    echo '<li><a href="' . home_url('/news') . '">News</a></li>';
    echo '<li><a href="' . home_url('/meetings') . '">Meetings</a></li>';
    echo '<li><a href="' . home_url('/terms-and-conditions-2') . '">Terms</a></li>';
    echo '</ul>';
}
?>
    <footer id="colophon" class="site-footer footer-bar">
        <div class="footer-container">
            <div class="footer-content">

                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-section">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>

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
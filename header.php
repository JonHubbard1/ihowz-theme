<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header ihowz-main-header">
        <!-- Top Gray Bar with Social Icons and Login Buttons -->
        <div class="header-top-gray-bar">
            <div class="header-top-bar">
                <div class="header-top-row">
                    <div class="header-top-row-links">
                        <!-- Social icons -->
                        <a href="#" class="header-top-row-social-icon">f</a>
                        <a href="#" class="header-top-row-social-icon">t</a>
                        <a href="#" class="header-top-row-social-icon">in</a>
                    </div>
                    <div class="header-top-row-buttons">
                        <?php if ( is_user_logged_in() ) : ?>
                            <?php $current_user = wp_get_current_user(); ?>
                            <div class="header-member-menu">
                                <button class="header-member-toggle">
                                    Welcome back <span class="header-member-name"><?php echo esc_html( $current_user->first_name . ' ' . $current_user->last_name ); ?></span>
                                    <span class="header-member-arrow">▼</span>
                                </button>
                                <div class="header-member-dropdown">
                                    <a href="/member-portal">Dashboard</a>
                                    <a href="/member-portal/?view=profile">Profile</a>
                                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="header-login-menu">
                                <button class="button header-login" type="button" aria-expanded="false" aria-controls="header-login-dropdown">
                                    Login
                                </button>
                                <div id="header-login-dropdown" class="header-login-dropdown">
                                    <form id="header-login-form" method="post" action="<?php echo esc_url(wp_login_url()); ?>">
                                        <div class="login-form-group">
                                            <label for="header-user-login"><?php _e('Email or Username', 'ihowz'); ?></label>
                                            <input type="text" name="log" id="header-user-login" required autocomplete="username">
                                        </div>
                                        <div class="login-form-group">
                                            <label for="header-user-pass"><?php _e('Password', 'ihowz'); ?></label>
                                            <input type="password" name="pwd" id="header-user-pass" required autocomplete="current-password">
                                        </div>
                                        <div class="login-form-group login-remember">
                                            <label>
                                                <input type="checkbox" name="rememberme" value="forever">
                                                <?php _e('Remember me', 'ihowz'); ?>
                                            </label>
                                        </div>
                                        <div class="login-form-error" style="display: none;"></div>
                                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                        <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>">
                                        <button type="submit" class="login-submit-btn">
                                            <?php _e('Sign In', 'ihowz'); ?>
                                        </button>
                                        <div class="login-form-links">
                                            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Forgot password?', 'ihowz'); ?></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <a href="/join" class="button header-join-today">Join Today</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Menu Bar -->
        <div class="header-menu-bar">
            <div class="header-content">
                <div class="site-branding">
                    <?php ihowz_theme_logo(); ?>
                </div>

                <div class="menubar-menu">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                    <nav id="site-navigation" class="main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => 'ihowz_theme_fallback_menu',
                            'walker'         => new IHowz_MegaMenu_Walker(),
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>

<?php
/**
 * Fallback menu if no menu is assigned
 */
function ihowz_theme_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url() . '">Home</a></li>';
    echo '<li><a href="' . home_url('/news') . '">News</a></li>';
    echo '<li><a href="' . home_url('/meetings') . '">Meetings</a></li>';
    echo '</ul>';
}
?>
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
                        <a href="https://www.facebook.com/ihowznews" class="header-top-row-social-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://x.com/i_howz" class="header-top-row-social-icon" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/company/ihowz" class="header-top-row-social-icon" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/channel/UCaXtxzbZ1Lxqs1sps1klCtA" class="header-top-row-social-icon" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/><polygon fill="#fff" points="9.545,15.568 15.818,12 9.545,8.432"/></svg>
                        </a>
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
                            <a href="<?php echo esc_url(home_url("/join-now/")); ?>" class="button header-join-today">Join Today</a>
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

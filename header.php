<?php
/**
 * Text-size accessibility preference.
 *
 * The visitor's chosen step is read from the ihowz_textsize cookie and applied
 * as a class on <html> server-side, before paint — so there is no flash of the
 * default size on reload. The cookie is only ever written by main.js once the
 * visitor has accepted the cookie-consent banner; reading it here needs no
 * consent. Typography is rem-based, so a single html { font-size: N% } rule
 * (see layout.css) scales the whole site.
 */
$ihowz_textsize_steps = array( 'xs', 'sm', 'md', 'lg', 'xl' );
$ihowz_textsize      = isset( $_COOKIE['ihowz_textsize'] ) ? sanitize_key( wp_unslash( $_COOKIE['ihowz_textsize'] ) ) : '';
$ihowz_textsize_class = in_array( $ihowz_textsize, $ihowz_textsize_steps, true ) ? ' ihowz-textsize-' . $ihowz_textsize : '';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr( trim( $ihowz_textsize_class ) ); ?>">
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
                        <!-- Social icons (configured in Appearance > Customize > Social Media) -->
                        <?php ihowz_render_social_icons( 14, 'header-top-row-social-icon' ); ?>
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
                                        <div class="login-form-group login-form-password">
                                            <label for="header-user-pass"><?php _e('Password', 'ihowz'); ?></label>
                                            <div class="password-input-wrapper">
                                                <input type="password" name="pwd" id="header-user-pass" required autocomplete="current-password">
                                                <button type="button" class="password-toggle-btn" aria-label="<?php esc_attr_e('Show password', 'ihowz'); ?>" aria-pressed="false" aria-controls="header-user-pass">
                                                    <svg class="password-toggle-icon password-toggle-icon-show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <svg class="password-toggle-icon password-toggle-icon-hide" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                                    </svg>
                                                </button>
                                            </div>
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
                                            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Problems logging in?', 'ihowz'); ?></a>
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

                    <div class="header-textsize-menu">
                        <button class="header-textsize-toggle header-textsize-decrease" type="button" aria-label="<?php esc_attr_e('Smaller text', 'ihowz'); ?>" aria-disabled="false">
                            <span class="header-textsize-label" aria-hidden="true">A&minus;</span>
                        </button>
                        <button class="header-textsize-toggle header-textsize-increase" type="button" aria-label="<?php esc_attr_e('Larger text', 'ihowz'); ?>" aria-disabled="false">
                            <span class="header-textsize-label" aria-hidden="true">A+</span>
                        </button>
                    </div>

                    <div class="header-search-menu">
                        <button class="header-search-toggle" type="button" aria-expanded="false" aria-controls="header-search-dropdown" aria-label="<?php esc_attr_e('Search iHowz', 'ihowz'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div id="header-search-dropdown" class="header-search-dropdown">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile menu backdrop (click to close the slide-in menu) -->
    <div class="menu-overlay" aria-hidden="true"></div>

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

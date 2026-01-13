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
                                    Welcome back <?php echo esc_html( $current_user->first_name . ' ' . $current_user->last_name ); ?>
                                    <span class="header-member-arrow">▼</span>
                                </button>
                                <div class="header-member-dropdown">
                                    <a href="/member-portal">Dashboard</a>
                                    <a href="/member-portal/?view=profile">Profile</a>
                                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <a href="/login" class="button header-login">Login</a>
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
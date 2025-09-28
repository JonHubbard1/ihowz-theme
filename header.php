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
        <!-- Top Gray Bar with Social Icons -->
        <div class="header-top-gray-bar">
            <div class="header-top-bar">
                <div class="header-top-row">
                    <div class="header-top-row-links">
                        <!-- Social icons would go here -->
                        <a href="#" class="header-top-row-social-icon">f</a>
                        <a href="#" class="header-top-row-social-icon">t</a>
                        <a href="#" class="header-top-row-social-icon">in</a>
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
                    <nav id="site-navigation" class="main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => 'ihowz_theme_fallback_menu',
                        ));
                        ?>
                    </nav>

                    <div class="header-buttons">
                        <a href="/login" class="button menubar-login">Login</a>
                        <a href="/join" class="button menubar-join-today">Join Today</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php
    // Add breadcrumb on non-front pages
    if (!is_front_page() && !is_home()) {
        echo '<div class="container">';
        ihowz_theme_breadcrumb();
        echo '</div>';
    }
    ?>

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
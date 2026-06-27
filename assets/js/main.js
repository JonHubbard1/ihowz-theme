/**
 * iHowz Theme JavaScript
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Mobile menu toggle (slide-in from the left)
        $('.menu-toggle').on('click', function() {
            var $btn = $(this);
            var opening = !$btn.hasClass('toggled');
            $btn.toggleClass('toggled', opening);
            $('.main-navigation').toggleClass('toggled', opening);
            $('.menu-overlay').toggleClass('active', opening);
            $('body').toggleClass('menu-open', opening);
            $btn.attr('aria-expanded', opening ? 'true' : 'false');

            // Position the panel's first item below the visible header.
            // The header is taller while the gray bar is showing (~130px)
            // than when scrolled/sticky (~88px), so a fixed CSS padding-top
            // can't clear both. Measure the menu bar's bottom edge at open
            // time and use it as the panel's top padding.
            if (opening) {
                var menuBar = $('.header-menu-bar')[0];
                if (menuBar) {
                    var headerBottom = Math.round(menuBar.getBoundingClientRect().bottom);
                    // Fallback to the CSS default if measurement fails.
                    if (headerBottom > 0) {
                        $('.main-navigation').css('padding-top', headerBottom + 'px');
                    }
                }
            } else {
                $('.main-navigation').css('padding-top', '');
            }
        });

        // Close the slide-in menu when the backdrop is clicked.
        $('.menu-overlay').on('click', function() {
            $('.menu-toggle').removeClass('toggled').attr('aria-expanded', 'false');
            $('.main-navigation').removeClass('toggled').css('padding-top', '');
            $('.menu-overlay').removeClass('active');
            $('body').removeClass('menu-open');
        });

        // MegaMenu accordion toggle (mobile only).
        // Evaluated at click time via matchMedia so it matches the CSS
        // max-width:1023px breakpoint exactly. jQuery's width() excludes the
        // scrollbar and can mismatch the CSS (attaching the accordion on a
        // 1024px desktop viewport), which broke desktop hover dropdowns and
        // stopped parent links navigating. On desktop this bails out and the
        // link navigates normally with CSS hover dropdowns.
        $('.menu-item-has-children > a, .megamenu-enabled > a').on('click', function(e) {
            if (!window.matchMedia('(max-width: 1023px)').matches) {
                return;
            }

            var $parent = $(this).parent();

            if ($parent.hasClass('menu-item-has-children') || $parent.hasClass('megamenu-enabled')) {
                e.preventDefault();

                // Close other open menus at the same level
                $parent.siblings('.menu-open').removeClass('menu-open').find('.sub-menu, .megamenu-dropdown').slideUp(200);

                // Toggle this menu
                $parent.toggleClass('menu-open');
                $parent.children('.sub-menu, .megamenu-dropdown').slideToggle(200);
            }
        });

        // Re-initialize mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() >= 1024) {
                // Desktop - remove mobile classes and inline styles
                $('.menu-item-has-children, .megamenu-enabled').removeClass('menu-open');
                $('.sub-menu, .megamenu-dropdown').removeAttr('style');
                $('.menu-toggle').removeClass('toggled').attr('aria-expanded', 'false');
                $('.main-navigation').removeClass('toggled').css('padding-top', '');
                $('.menu-overlay').removeClass('active');
                $('body').removeClass('menu-open');

                // Recalculate MegaMenu positions
                positionMegaMenus();
            }
        });

        // Position MegaMenus to start at viewport left edge
        function positionMegaMenus() {
            if ($(window).width() >= 1024) {
                $('.megamenu-enabled').each(function() {
                    var $menuItem = $(this);
                    var $dropdown = $menuItem.children('.megamenu-dropdown');

                    // Get the menu item's offset from the left edge of the viewport
                    var offsetLeft = $menuItem.offset().left;

                    // Apply negative left to position dropdown at viewport left: 0
                    $dropdown.css('left', -offsetLeft + 'px');
                });
            }
        }

        // Initialize MegaMenu positioning on page load
        positionMegaMenus();

        // Recalculate on window resize (debounced)
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                positionMegaMenus();
            }, 250);
        });

        // Fit nested flyout submenus inside the viewport: decide, on hover,
        // both the side (flip) and the height strategy (single column /
        // multi-column / scroll) together, because adding columns widens
        // the flyout and changes which side it should open on.
        //
        // CSS opens these flyouts to the right of their parent
        // (.megamenu-dropdown .sub-menu { left: 100%; }) with no collision
        // or height check, so a flyout can run off the right edge or off the
        // bottom of the screen. Here we measure on hover and:
        //   - if it fits vertically  -> single column, honour the flip;
        //   - if too tall, with horizontal room -> split into 2-3 columns,
        //     each capped to the viewport height;
        //   - if too tall, no room for 2+ columns -> single scrollable column;
        //   - if 3 columns still isn't enough -> the columned block scrolls.
        //
        // Works at every level (each .menu-item-has-children li is a
        // positioning context for its own .sub-menu, and the measurements
        // are level-agnostic). Mobile uses the accordion (matchMedia), where
        // flyouts are static, so this is desktop-only.
        var flyoutFitStyles = ['left', 'right', 'maxHeight', 'overflowY', 'overflowX', 'columnCount', 'columnGap', 'columnFill', 'width'];

        $('.megamenu-dropdown li.menu-item-has-children').on('mouseenter', function() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                return;
            }

            var $li = $(this);
            var $flyout = $li.children('.sub-menu');
            if (!$flyout.length) {
                return;
            }

            var el = $flyout[0];

            // Reset every inline style we may set so we measure the natural
            // (single-column, to-the-right) state, then reflow via offsetHeight
            // so the measurement is valid even though the flyout is
            // display:none until :hover.
            var reset = {};
            flyoutFitStyles.forEach(function(k) { reset[k] = ''; });
            $flyout.css(reset);

            var naturalHeight = el.offsetHeight;
            if (!naturalHeight) {
                return;
            }
            var naturalWidth = el.offsetWidth;

            // left: 100% places the flyout's top at the parent's top, so its
            // available vertical space is the viewport below that point.
            var flyoutTop = el.getBoundingClientRect().top;
            var availableH = window.innerHeight - flyoutTop - 12; // 12px breathing room

            var parentRight = $li[0].getBoundingClientRect().right;
            var parentLeft = $li[0].getBoundingClientRect().left;
            var spaceRight = window.innerWidth - parentRight;
            var spaceLeft = parentLeft;

            var colWidth = naturalWidth;
            var gap = 16; // 1rem, matches the megamenu grid gap

            // Prefer opening right; flip left only if the right lacks room
            // and the left has enough.
            function sideFor(width) {
                return (width > spaceRight && width <= spaceLeft) ? 'left' : 'right';
            }

            if (naturalHeight <= availableH) {
                // Fits vertically — single column, just honour the flip.
                if (sideFor(naturalWidth) === 'left') {
                    $flyout.css({ left: 'auto', right: '100%' });
                }
                return;
            }

            // Too tall — try columns (cap 3), else scroll.
            var neededCols = Math.ceil(naturalHeight / availableH);
            var maxColsRight = Math.floor((spaceRight + gap) / (colWidth + gap));
            var maxColsLeft = Math.floor((spaceLeft + gap) / (colWidth + gap));
            var colsRight = Math.min(neededCols, maxColsRight, 3);
            var colsLeft = Math.min(neededCols, maxColsLeft, 3);

            var cols = 0;
            var side = 'right';
            if (colsRight >= 2) {
                cols = colsRight;
                side = 'right';
            } else if (colsLeft >= 2) {
                cols = colsLeft;
                side = 'left';
            }

            if (cols >= 2) {
                // Multi-column: each column is capped at availableH via
                // column-fill:auto, and overflow-y:auto makes the block
                // scroll if 3 columns still isn't enough. An explicit width
                // is required so column-count doesn't derive tiny columns
                // from the narrow parent li.
                $flyout.css({
                    columnCount: cols,
                    columnGap: gap + 'px',
                    columnFill: 'auto',
                    maxHeight: availableH + 'px',
                    width: (cols * colWidth + (cols - 1) * gap) + 'px',
                    overflowY: 'auto',
                    overflowX: 'hidden',
                    left: side === 'left' ? 'auto' : '100%',
                    right: side === 'left' ? '100%' : ''
                });
            } else {
                // No horizontal room for 2+ columns — single scrollable column.
                var s = sideFor(naturalWidth);
                $flyout.css({
                    maxHeight: availableH + 'px',
                    overflowY: 'auto',
                    overflowX: 'hidden',
                    left: s === 'left' ? 'auto' : '100%',
                    right: s === 'left' ? '100%' : ''
                });
            }
        });

        // Clear inline fit styles when the pointer leaves the li subtree so
        // each hover re-measures cleanly. (mouseleave only fires once the
        // pointer leaves the li AND its descendants, so it won't fire while
        // moving inside the open flyout.)
        $('.megamenu-dropdown li.menu-item-has-children').on('mouseleave', function() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                return;
            }
            var $flyout = $(this).children('.sub-menu');
            if ($flyout.length) {
                var reset = {};
                flyoutFitStyles.forEach(function(k) { reset[k] = ''; });
                $flyout.css(reset);
            }
        });

        // Cap the top-level megamenu panel to the viewport height so a tall
        // panel scrolls instead of running off the bottom. The panel is
        // already a multi-column grid (column count set per-item in admin),
        // so we only scroll it — we don't re-column it. Desktop only.
        $('.megamenu-enabled').on('mouseenter', function() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                return;
            }
            var $panel = $(this).children('.megamenu-dropdown');
            if (!$panel.length) {
                return;
            }
            $panel.css({ maxHeight: '', overflowY: '', overflowX: '' });
            var panelTop = $panel[0].getBoundingClientRect().top;
            var availableH = window.innerHeight - panelTop - 12;
            $panel.css({ maxHeight: availableH + 'px', overflowY: 'auto', overflowX: 'hidden' });
        });

        $('.megamenu-enabled').on('mouseleave', function() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                return;
            }
            var $panel = $(this).children('.megamenu-dropdown');
            if ($panel.length) {
                $panel.css({ maxHeight: '', overflowY: '', overflowX: '' });
            }
        });

        // Smooth scrolling for anchor links
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });

        // Search form enhancements
        $('.search-form input[type="search"]').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().removeClass('focused');
        });

        // Login Dropdown Toggle
        var loginMenu = $('.header-login-menu');
        var loginButton = loginMenu.find('.header-login');
        var loginDropdown = loginMenu.find('.header-login-dropdown');

        // Toggle dropdown on button click
        loginButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var isActive = loginMenu.hasClass('active');
            loginMenu.toggleClass('active');
            loginButton.attr('aria-expanded', !isActive);

            // Focus the first input when opening
            if (!isActive) {
                setTimeout(function() {
                    loginDropdown.find('input[type="text"]').first().focus();
                }, 100);
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (loginMenu.hasClass('active') && !loginMenu.is(e.target) && loginMenu.has(e.target).length === 0) {
                loginMenu.removeClass('active');
                loginButton.attr('aria-expanded', 'false');
            }
        });

        // Close dropdown on Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && loginMenu.hasClass('active')) {
                loginMenu.removeClass('active');
                loginButton.attr('aria-expanded', 'false');
                loginButton.focus();
            }
        });

        // Header Search Dropdown Toggle
        var searchMenu = $('.header-search-menu');
        var searchButton = searchMenu.find('.header-search-toggle');
        var searchDropdown = searchMenu.find('.header-search-dropdown');

        searchButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var isActive = searchMenu.hasClass('active');
            searchMenu.toggleClass('active');
            searchButton.attr('aria-expanded', !isActive);

            if (!isActive) {
                setTimeout(function() {
                    searchDropdown.find('.search-field').first().focus();
                }, 100);
            }
        });

        $(document).on('click', function(e) {
            if (searchMenu.hasClass('active') && !searchMenu.is(e.target) && searchMenu.has(e.target).length === 0) {
                searchMenu.removeClass('active');
                searchButton.attr('aria-expanded', 'false');
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && searchMenu.hasClass('active')) {
                searchMenu.removeClass('active');
                searchButton.attr('aria-expanded', 'false');
                searchButton.focus();
            }
        });

        // Password visibility toggle
        $('.password-toggle-btn').on('click', function() {
            var $btn = $(this);
            var $input = $('#' + $btn.attr('aria-controls'));
            var isShown = $btn.attr('aria-pressed') === 'true';
            var strings = (typeof ihowz_ajax !== 'undefined' && ihowz_ajax.strings) ? ihowz_ajax.strings : {};

            $input.attr('type', isShown ? 'password' : 'text');
            $btn.attr('aria-pressed', String(!isShown));
            $btn.attr('aria-label', isShown ? (strings.show_password || 'Show password') : (strings.hide_password || 'Hide password'));
            $input.focus();
        });

        // Handle login form submission via AJAX
        $('#header-login-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('.login-submit-btn');
            var $errorDiv = $form.find('.login-form-error');
            var originalText = $submitBtn.text();

            // Clear previous errors
            $errorDiv.hide().text('');

            // Disable button and show loading state
            $submitBtn.prop('disabled', true).text('Signing in...');

            // Prepare form data
            var formData = {
                action: 'ihowz_ajax_login',
                log: $form.find('input[name="log"]').val(),
                pwd: $form.find('input[name="pwd"]').val(),
                rememberme: $form.find('input[name="rememberme"]').is(':checked') ? 'forever' : '',
                security: $form.find('input[name="security"]').val(),
                redirect_to: $form.find('input[name="redirect_to"]').val()
            };

            // Make AJAX request
            $.ajax({
                type: 'POST',
                url: ihowz_ajax.ajax_url || '/wp-admin/admin-ajax.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Login successful - redirect
                        window.location.href = response.data.redirect || formData.redirect_to || window.location.href;
                    } else {
                        // Show error message
                        $errorDiv.text(response.data.message || 'Login failed. Please check your credentials.').show();
                        $submitBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    // On AJAX error, fall back to standard form submission
                    $form.off('submit').submit();
                }
            });
        });

        // Add fade-in animation to post cards
        if ($('.posts-grid').length) {
            $('.post-card').each(function(index) {
                $(this).delay(100 * index).fadeIn();
            });
        }

        // Back to top button
        var backToTop = $('<button class="back-to-top" title="Back to Top">↑</button>');
        $('body').append(backToTop);

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });

        backToTop.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
            return false;
        });

        // Image lazy loading fallback
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }

        // Header scroll effect - Two-tier sticky behavior
        var header = $('.site-header');
        var grayBar = $('.header-top-gray-bar');
        var menuBar = $('.header-menu-bar');
        var body = $('body');
        var grayBarHeight = grayBar.length ? grayBar.outerHeight() : 80;

        $(window).on('scroll', function() {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > grayBarHeight) {
                // Hide gray bar and make menu bar sticky
                header.addClass('header-scrolled');
                body.addClass('header-sticky-active');
            } else {
                // Show gray bar and normal positioning
                header.removeClass('header-scrolled');
                body.removeClass('header-sticky-active');
            }
        });

        // Initialize on page load in case user refreshes while scrolled
        $(window).trigger('scroll');

        // Hero video autoplay - safety net only.
        // The block hero <video> already has autoplay+muted+playsinline, which
        // native-autoplays on Chrome, Firefox and Safari (muted). Do NOT call
        // .load() or force .play() on a video that is already playing — reloading
        // an already-playing video triggers Safari's green-frame compositor bug.
        // We only nudge .play() when the video is actually paused, and retry on
        // the first user interaction for iOS Low Power Mode (which blocks even
        // muted autoplay until interaction).
        var heroVideos = document.querySelectorAll('.hero-video');
        if (heroVideos.length) {
            heroVideos.forEach(function (heroVideo) {
                heroVideo.removeAttribute('controls');
                heroVideo.muted = true;
                heroVideo.defaultMuted = true;

                function attemptPlay() {
                    // Don't touch a video that is already playing.
                    if (heroVideo.paused === false || heroVideo.ended) {
                        return;
                    }
                    var playPromise = heroVideo.play();
                    if (playPromise && typeof playPromise.catch === 'function') {
                        playPromise.catch(function () {
                            // Autoplay blocked — the poster/backup image shows
                            // until the first interaction retries via playOnInteraction.
                        });
                    }
                }

                // Nudge once the browser has enough to play, and shortly after load.
                heroVideo.addEventListener('canplay', attemptPlay);
                heroVideo.addEventListener('canplaythrough', attemptPlay);
                setTimeout(attemptPlay, 500);

                // Low Power Mode / blocked autoplay: start on first interaction.
                function playOnInteraction() { attemptPlay(); }
                ['click', 'scroll', 'touchstart', 'keydown'].forEach(function (evt) {
                    document.addEventListener(evt, playOnInteraction, { once: true });
                });
            });
        }

    });

    // Window load
    $(window).on('load', function() {
        // Hide loading animations
        $('.loading').fadeOut();

        // Initialize any additional scripts
        initializeCustomScripts();
    });

    // Custom scripts initialization
    function initializeCustomScripts() {
        // Add any custom initialization here
        console.log('iHowz Theme loaded');
    }

})(jQuery);
/**
 * iHowz Theme JavaScript
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('toggled');
            $('.main-navigation').toggleClass('toggled');

            // Update aria-expanded attribute
            var expanded = $(this).attr('aria-expanded') === 'true';
            $(this).attr('aria-expanded', !expanded);
        });

        // MegaMenu Mobile Dropdown Toggle
        if ($(window).width() < 1024) {
            $('.menu-item-has-children > a, .megamenu-enabled > a').on('click', function(e) {
                // Only prevent default on mobile
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
        }

        // Re-initialize mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() >= 1024) {
                // Desktop - remove mobile classes and inline styles
                $('.menu-item-has-children, .megamenu-enabled').removeClass('menu-open');
                $('.sub-menu, .megamenu-dropdown').removeAttr('style');

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

        // Hero video loading - Safari autoplay fix
        var heroVideo = document.getElementById('hero-background-video');
        if (heroVideo) {
            // Remove controls attribute if present (Safari sometimes adds it)
            heroVideo.removeAttribute('controls');

            // Ensure muted (required for autoplay in Safari)
            heroVideo.muted = true;
            heroVideo.setAttribute('muted', '');
            heroVideo.setAttribute('playsinline', '');
            heroVideo.defaultMuted = true;
            heroVideo.volume = 0;

            var hasPlayed = false;

            // Function to attempt playing the video
            function attemptPlay() {
                if (hasPlayed) {
                    return;
                }

                var playPromise = heroVideo.play();

                if (playPromise !== undefined) {
                    playPromise.then(function() {
                        hasPlayed = true;
                        heroVideo.removeAttribute('controls');
                    }).catch(function() {
                        // Autoplay blocked - will retry on user interaction
                    });
                }
            }

            // Try to play when video is ready
            heroVideo.addEventListener('canplay', function() {
                attemptPlay();
            });

            heroVideo.addEventListener('canplaythrough', function() {
                attemptPlay();
            });

            // Start loading the video
            heroVideo.load();

            // Try to play after a short delay
            setTimeout(attemptPlay, 500);

            // Fallback: Play on any user interaction (required for Safari)
            function playOnInteraction() {
                if (!hasPlayed) {
                    attemptPlay();
                }
            }

            // Listen for user interactions
            document.addEventListener('click', playOnInteraction, { once: true });
            document.addEventListener('scroll', playOnInteraction, { once: true });
            document.addEventListener('touchstart', playOnInteraction, { once: true });
            document.addEventListener('keydown', playOnInteraction, { once: true });
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
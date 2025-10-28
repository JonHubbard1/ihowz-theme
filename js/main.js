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
            }
        });

        // Smooth scrolling for anchor links
        $('a[href*="#"]:not([href="#"])').click(function() {
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

        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });

        backToTop.click(function() {
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

        $(window).scroll(function() {
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
            console.log('Hero video found, initializing...');

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
            function attemptPlay(source) {
                if (hasPlayed) {
                    return;
                }

                console.log('Attempting to play video from: ' + source);
                console.log('Video readyState: ' + heroVideo.readyState + ' (0=nothing, 1=metadata, 2=current, 3=future, 4=enough)');

                var playPromise = heroVideo.play();

                if (playPromise !== undefined) {
                    playPromise.then(function() {
                        hasPlayed = true;
                        console.log('✓ Video playing successfully!');
                        // Remove controls if Safari adds them
                        heroVideo.removeAttribute('controls');
                    }).catch(function(error) {
                        console.log('✗ Video play blocked: ' + error.message);
                        if (source === 'init') {
                            console.log('Will retry on user interaction (click, scroll, or touch)');
                        }
                    });
                }
            }

            // Try multiple video events
            heroVideo.addEventListener('loadedmetadata', function() {
                console.log('✓ Video metadata loaded');
            });

            heroVideo.addEventListener('loadeddata', function() {
                console.log('✓ Video data loaded');
            });

            heroVideo.addEventListener('canplay', function() {
                console.log('✓ Video can play (readyState: ' + heroVideo.readyState + ')');
                attemptPlay('canplay');
            });

            heroVideo.addEventListener('canplaythrough', function() {
                console.log('✓ Video can play through');
                attemptPlay('canplaythrough');
            });

            // Log when video actually starts playing
            heroVideo.addEventListener('play', function() {
                console.log('✓ Video PLAY event fired');
            });

            heroVideo.addEventListener('playing', function() {
                console.log('✓ Video is PLAYING');
            });

            // Log video errors
            heroVideo.addEventListener('error', function(e) {
                console.error('✗ Video error:', e);
                if (heroVideo.error) {
                    var errorMessages = {
                        1: 'MEDIA_ERR_ABORTED - Video loading aborted',
                        2: 'MEDIA_ERR_NETWORK - Network error',
                        3: 'MEDIA_ERR_DECODE - Video decode error',
                        4: 'MEDIA_ERR_SRC_NOT_SUPPORTED - Video format not supported'
                    };
                    console.error(errorMessages[heroVideo.error.code] || 'Unknown error');
                }
            });

            // Start loading the video
            console.log('Starting video load...');
            heroVideo.load();

            // Try to play immediately (will likely fail but worth trying)
            setTimeout(function() {
                attemptPlay('init');
            }, 500);

            // Fallback: Play on any user interaction (required for Safari)
            function playOnInteraction(e) {
                console.log('User interaction detected: ' + e.type);
                if (!hasPlayed) {
                    attemptPlay('interaction-' + e.type);
                }
            }

            // Listen for user interactions
            document.addEventListener('click', playOnInteraction, { once: true });
            document.addEventListener('scroll', playOnInteraction, { once: true });
            document.addEventListener('touchstart', playOnInteraction, { once: true });
            document.addEventListener('keydown', playOnInteraction, { once: true });

            console.log('Video initialization complete.');
        } else {
            console.warn('Hero video element not found!');
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
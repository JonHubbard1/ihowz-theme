/**
 * iHowz Theme JavaScript
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('toggled');
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
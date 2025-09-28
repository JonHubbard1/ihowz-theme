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

        // Header scroll effect
        var header = $('.site-header');
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });

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
/**
 * About Stats Block - Counter Animation
 * Counts up plain numbers when they come into view
 */
(function() {
    'use strict';

    // Check if a string is a plain number (with optional + or , separators)
    function parseStatNumber(text) {
        if (!text) return null;

        // Trim whitespace
        text = text.trim();

        // Check for suffix like + at the end
        let suffix = '';
        if (text.endsWith('+')) {
            suffix = '+';
            text = text.slice(0, -1);
        }

        // Check for prefix like $ or £
        let prefix = '';
        if (text.match(/^[£$€]/)) {
            prefix = text[0];
            text = text.slice(1);
        }

        // Remove commas and spaces (thousand separators)
        text = text.replace(/[,\s]/g, '');

        // Check if remaining is a valid number
        const num = parseFloat(text);
        if (isNaN(num)) return null;

        // Check if it's an integer or has decimals
        const isInteger = Number.isInteger(num);

        return {
            value: num,
            prefix: prefix,
            suffix: suffix,
            isInteger: isInteger,
            hasThousands: num >= 1000
        };
    }

    // Format number with commas
    function formatNumber(num, isInteger, hasThousands) {
        if (isInteger) {
            num = Math.round(num);
        }

        if (hasThousands) {
            return num.toLocaleString('en-GB');
        }

        return isInteger ? num.toString() : num.toFixed(1);
    }

    // Animate counting
    function animateCount(element, parsed) {
        const duration = 2000; // 2 seconds
        const startTime = performance.now();
        const startValue = 0;
        const endValue = parsed.value;

        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = easeOutQuart(progress);
            const currentValue = startValue + (endValue - startValue) * easedProgress;

            element.textContent = parsed.prefix + formatNumber(currentValue, parsed.isInteger, parsed.hasThousands) + parsed.suffix;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    // Initialize counters
    function initCounters() {
        const statNumbers = document.querySelectorAll('.about-stats-number');

        if (!statNumbers.length) return;

        // Create intersection observer
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const originalText = element.getAttribute('data-original');
                    const parsed = parseStatNumber(originalText);

                    if (parsed) {
                        // Start with 0
                        element.textContent = parsed.prefix + '0' + parsed.suffix;
                        // Animate to final value
                        animateCount(element, parsed);
                    }

                    // Stop observing once animated
                    observer.unobserve(element);
                }
            });
        }, {
            threshold: 0.5, // Trigger when 50% visible
            rootMargin: '0px'
        });

        // Store original values and observe
        statNumbers.forEach(function(element) {
            const text = element.textContent;
            const parsed = parseStatNumber(text);

            if (parsed) {
                // Store original text
                element.setAttribute('data-original', text);
                element.setAttribute('data-countable', 'true');
                // Observe for intersection
                observer.observe(element);
            }
        });
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCounters);
    } else {
        initCounters();
    }
})();

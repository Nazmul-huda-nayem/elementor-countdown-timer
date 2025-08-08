jQuery(document).ready(function($) {
    
    // Initialize countdown functionality
    function initCountdown(element) {
        const $countdown = $(element);
        const targetDate = new Date($countdown.data('target-date')).getTime();
        const language = $countdown.data('language') || 'english';
        const expiredText = $countdown.data('expired-text') || 'Time Expired!';
        const showLabels = $countdown.data('show-labels') !== 'no';
        
        // Get custom labels or use defaults
        const customLabels = {
            days: $countdown.data('label-days') || (language === 'bangla' ? 'দিন' : 'Days'),
            hours: $countdown.data('label-hours') || (language === 'bangla' ? 'ঘন্টা' : 'Hours'),
            minutes: $countdown.data('label-minutes') || (language === 'bangla' ? 'মিনিট' : 'Minutes'),
            seconds: $countdown.data('label-seconds') || (language === 'bangla' ? 'সেকেন্ড' : 'Seconds')
        };
        
        // Check if target date is valid
        if (isNaN(targetDate)) {
            console.error('Invalid target date for countdown');
            return;
        }
        
        // Language labels
        const labels = {
            english: {
                days: 'Days',
                hours: 'Hours', 
                minutes: 'Minutes',
                seconds: 'Seconds'
            },
            bangla: {
                days: 'দিন',
                hours: 'ঘন্টা',
                minutes: 'মিনিট', 
                seconds: 'সেকেন্ড'
            }
        };
        
        // Bangla numerals mapping
        const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        
        // Convert English numbers to Bangla
        function convertToBangla(number) {
            return number.toString().split('').map(digit => {
                return isNaN(digit) ? digit : banglaNumbers[parseInt(digit)];
            }).join('');
        }
        
        // Add leading zero
        function padZero(num) {
            return num.toString().padStart(2, '0');
        }
        
        // Update countdown display
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            // Check if countdown has expired
            if (distance < 0) {
                $countdown.html(`<div class="countdown-expired">${expiredText}</div>`);
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Format numbers
            let displayDays = days.toString();
            let displayHours = padZero(hours);
            let displayMinutes = padZero(minutes);
            let displaySeconds = padZero(seconds);
            
            // Convert to Bangla if needed
            if (language === 'bangla') {
                displayDays = convertToBangla(days);
                displayHours = convertToBangla(padZero(hours));
                displayMinutes = convertToBangla(padZero(minutes));
                displaySeconds = convertToBangla(padZero(seconds));
            }
            
            // Build HTML
            let html = '';
            const timeUnits = [
                { value: displayDays, label: customLabels.days, key: 'days' },
                { value: displayHours, label: customLabels.hours, key: 'hours' },
                { value: displayMinutes, label: customLabels.minutes, key: 'minutes' },
                { value: displaySeconds, label: customLabels.seconds, key: 'seconds' }
            ];
            
            timeUnits.forEach(unit => {
                html += `
                    <div class="custom-countdown-item" data-unit="${unit.key}">
                        <span class="custom-countdown-number">${unit.value}</span>
                        ${showLabels ? `<span class="custom-countdown-label">${unit.label}</span>` : ''}
                    </div>
                `;
            });
            
            $countdown.html(html);
            
            // Add pulse animation when seconds change
            if (seconds !== $countdown.data('last-seconds')) {
                $countdown.find('[data-unit="seconds"]').addClass('pulse');
                setTimeout(() => {
                    $countdown.find('[data-unit="seconds"]').removeClass('pulse');
                }, 1000);
                $countdown.data('last-seconds', seconds);
            }
        }
        
        // Initial update
        updateCountdown();
        
        // Update every second
        const interval = setInterval(() => {
            updateCountdown();
            
            // Clear interval if expired
            const now = new Date().getTime();
            if (targetDate - now < 0) {
                clearInterval(interval);
            }
        }, 1000);
        
        // Store interval ID for cleanup
        $countdown.data('countdown-interval', interval);
    }
    
    // Clean up intervals
    function cleanupCountdown(element) {
        const $countdown = $(element);
        const interval = $countdown.data('countdown-interval');
        if (interval) {
            clearInterval(interval);
            $countdown.removeData('countdown-interval');
        }
    }
    
    // Initialize all countdowns on page load
    $('.custom-countdown-wrapper').each(function() {
        initCountdown(this);
    });
    
    // Elementor frontend integration
    $(window).on('elementor/frontend/init', function() {
        // Handle widget initialization in Elementor editor and frontend
        elementorFrontend.hooks.addAction('frontend/element_ready/custom-countdown.default', function($scope) {
            const $countdown = $scope.find('.custom-countdown-wrapper');
            if ($countdown.length) {
                // Clean up any existing countdown
                cleanupCountdown($countdown[0]);
                // Initialize new countdown
                initCountdown($countdown[0]);
            }
        });
    });
    
    // Special handling for Elementor editor
    $(document).ready(function() {
        // Check if we're in Elementor editor mode
        if (typeof elementor !== 'undefined' && elementor.isEditMode) {
            // Listen for widget changes in editor
            elementor.channels.editor.on('change', function() {
                setTimeout(function() {
                    $('.custom-countdown-wrapper').each(function() {
                        if (!$(this).data('countdown-interval')) {
                            initCountdown(this);
                        }
                    });
                }, 100);
            });
        }
    });
    
    // Handle dynamic content updates (for AJAX loaded content)
    $(document).on('DOMNodeInserted', function(e) {
        const $target = $(e.target);
        if ($target.hasClass('custom-countdown-wrapper')) {
            initCountdown($target[0]);
        } else {
            $target.find('.custom-countdown-wrapper').each(function() {
                initCountdown(this);
            });
        }
    });
    
    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        $('.custom-countdown-wrapper').each(function() {
            cleanupCountdown(this);
        });
    });
    
    // Utility function for manual initialization
    window.initCustomCountdown = function(selector) {
        $(selector).each(function() {
            initCountdown(this);
        });
    };
    
    // Utility function for manual cleanup
    window.cleanupCustomCountdown = function(selector) {
        $(selector).each(function() {
            cleanupCountdown(this);
        });
    };
    
    // Enhanced Elementor editor support
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
            if ($scope.find('.custom-countdown-wrapper').length) {
                $scope.find('.custom-countdown-wrapper').each(function() {
                    initCountdown(this);
                });
            }
        });
    }
    
    // Additional editor mode detection and handling
    function handleElementorEditor() {
        // Re-initialize countdowns when settings change
        $(document).on('input change', '.elementor-control input, .elementor-control select', function() {
            setTimeout(function() {
                $('.custom-countdown-wrapper').each(function() {
                    const $this = $(this);
                    // Clean up existing countdown
                    cleanupCountdown(this);
                    // Re-initialize with new settings
                    initCountdown(this);
                });
            }, 300);
        });
    }
    
    // Initialize editor handling
    if (window.location.href.indexOf('elementor') !== -1) {
        handleElementorEditor();
    }
});
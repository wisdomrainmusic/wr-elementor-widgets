(function($) {
    'use strict';

    function initWrTestimonialsScope(scope) {
        var $scope   = $(scope);
        var $wrapper = $scope.find('.wr-testimonials-wrapper').first();
        if (!$wrapper.length) return;

        var $slider = $wrapper.find('.wr-testimonials-slider').first();
        var $track  = $wrapper.find('.wr-testimonials-track').first();
        if (!$slider.length || !$track.length) return;

        var $cards  = $track.find('.wr-testimonial-card');
        if (!$cards.length) return;

        var $prev   = $slider.find('.wr-testimonials-arrow.prev');
        var $next   = $slider.find('.wr-testimonials-arrow.next');
        var $dotsWrap = $slider.find('.wr-testimonials-dots');

        var currentIndex = 0;
        var total        = $cards.length;
        var dots         = [];

        /* ------------------------------
         * DOTS OLUŞTUR
         * ------------------------------ */
        $dotsWrap.empty();
        for (var i = 0; i < total; i++) {
            var $dot = $('<button/>', {
                type: 'button',
                'class': 'wr-testimonials-dot' + (i === 0 ? ' is-active' : ''),
                'data-index': i
            });
            $dotsWrap.append($dot);
            dots.push($dot);
        }

        /* ------------------------------
         * SLIDER GÜNCELLE
         * ------------------------------ */
        function updateSlider() {
            var offset = -currentIndex * 100;
            $track.css('transform', 'translateX(' + offset + '%)');

            dots.forEach(function($dot, i) {
                $dot.toggleClass('is-active', i === currentIndex);
            });
        }

        function goTo(index) {
            if (index < 0) index = total - 1;
            if (index >= total) index = 0;
            currentIndex = index;
            updateSlider();
        }

        /* ------------------------------
         * OKLAR
         * ------------------------------ */
        $prev.on('click', function(e) {
            e.preventDefault();
            goTo(currentIndex - 1);
        });

        $next.on('click', function(e) {
            e.preventDefault();
            goTo(currentIndex + 1);
        });

        /* ------------------------------
         * DOT TIKLAMALARI
         * ------------------------------ */
        dots.forEach(function($dot) {
            $dot.on('click', function(e) {
                e.preventDefault();
                var idx = parseInt($(this).data('index'), 10);
                if (!isNaN(idx)) {
                    goTo(idx);
                }
            });
        });

        // İlk konum
        updateSlider();
    }

    // Normal sayfa yüklemesi
    $(document).ready(function() {
        $('.wr-testimonials-wrapper').each(function() {
            initWrTestimonialsScope(this);
        });
    });

    // Elementor edit modu
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/wr-testimonials.default',
            function($scope) {
                initWrTestimonialsScope($scope[0]);
            }
        );
    });

})(jQuery);

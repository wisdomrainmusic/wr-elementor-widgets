(function () {
    'use strict';

    function initSingleCategorySlider(root) {
        var slider = root.querySelector('.wr-category-slider.swiper');
        if (!slider || typeof Swiper === 'undefined') {
            return;
        }

        // Prevent double init
        if (slider.dataset.wrInitialized === 'true') {
            return;
        }
        slider.dataset.wrInitialized = 'true';

        var slidesDesktop = parseInt(slider.dataset.slidesDesktop || '4', 10);
        var slidesTablet  = parseInt(slider.dataset.slidesTablet || '3', 10);
        var slidesMobile  = parseInt(slider.dataset.slidesMobile || '2', 10);
        var spaceBetween  = parseInt(slider.dataset.spaceBetween || '24', 10);
        var autoplay      = slider.dataset.autoplay === 'yes';
        var delay         = parseInt(slider.dataset.autoplayDelay || '4000', 10);

        var navPrev = root.querySelector('.wr-cat-slider-prev');
        var navNext = root.querySelector('.wr-cat-slider-next');
        var pagination = root.querySelector('.wr-cat-slider-pagination');

        var config = {
            slidesPerView: slidesDesktop,
            spaceBetween: spaceBetween,
            loop: false,
            grabCursor: true,
            speed: 450,
            watchSlidesProgress: true,
            breakpoints: {
                0: {
                    slidesPerView: slidesMobile,
                    spaceBetween: 14
                },
                768: {
                    slidesPerView: slidesTablet,
                    spaceBetween: spaceBetween - 4
                },
                1025: {
                    slidesPerView: slidesDesktop,
                    spaceBetween: spaceBetween
                }
            }
        };

        if (autoplay) {
            config.autoplay = {
                delay: delay,
                disableOnInteraction: false
            };
        }

        if (navPrev && navNext) {
            config.navigation = {
                prevEl: navPrev,
                nextEl: navNext
            };
        }

        if (pagination) {
            config.pagination = {
                el: pagination,
                clickable: true
            };
        }

        new Swiper(slider, config);
    }

    function initWrCategorySliders(context) {
        var root = context || document;
        var wrappers = root.querySelectorAll('.wr-category-slider-wrapper');
        if (!wrappers.length) {
            return;
        }

        wrappers.forEach(function (wrapper) {
            initSingleCategorySlider(wrapper);
        });
    }

    // Standard DOM load (classic pages)
    document.addEventListener('DOMContentLoaded', function () {
        initWrCategorySliders(document);
    });

    // Elementor support
    if (window.jQuery && window.elementorFrontend) {
        jQuery(window).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/wr_category_slider.default',
                function ($scope) {
                    initWrCategorySliders($scope[0]);
                }
            );
        });
    }
})();

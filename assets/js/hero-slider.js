/**
 * WR Hero Slider — Final Stable Patch
 * OTURUM 9 — Adım 2
 *
 * Fixed:
 * - Elementor init mismatch
 * - Swiper container incorrect selector
 * - Undefined navigation elements
 * - Overlay not closing (init not triggered)
 */

function initWrHeroSlider($scope) {

    // Elementor widget wrapper
    const widget = $scope[0];
    if (!widget) return;

    // Swiper container inside widget
    const slider = widget.querySelector('.wr-hero-slider-swiper');
    if (!slider) return;

    // Init Swiper safely
    new Swiper(slider, {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: slider.querySelector('.swiper-pagination'),
            clickable: true,
        },
        navigation: {
            nextEl: slider.querySelector('.swiper-button-next'),
            prevEl: slider.querySelector('.swiper-button-prev'),
        },
        effect: 'slide',
    });
}


/**
 * 1) NORMAL FRONTEND INITIALIZATION
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.elementor-widget-wr-hero-slider')
        .forEach(widget => {
            initWrHeroSlider($(widget));
        });
});


/**
 * 2) ELEMENTOR EDITOR MODE INITIALIZATION
 */
window.addEventListener('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/wr-hero-slider.default',
        initWrHeroSlider
    );
});

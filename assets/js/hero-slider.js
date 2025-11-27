function initWrHeroSlider() {
    const sliders = document.querySelectorAll('.wr-hero-slider-swiper');

    sliders.forEach(slider => {
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
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initWrHeroSlider();
});

// Elementor editor mode detection
if (window.elementorFrontend) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/wr-hero-slider.default', function () {
        initWrHeroSlider();
    });
}

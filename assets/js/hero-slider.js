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

// Normal sayfada çalıştır
document.addEventListener('DOMContentLoaded', function () {
    initWrHeroSlider();
});

// Elementor Editor Modu İçin Zorunlu Hooklar
if (window.elementorFrontend) {

    const initHandler = function () {
        // Elementor widget render sonrası boyutlar otursun diye gecikme
        setTimeout(() => {
            initWrHeroSlider();
        }, 150);
    };

    // 1) Normal widget load
    window.elementorFrontend.hooks.addAction(
        'frontend/element_ready/wr-hero-slider.default',
        initHandler
    );

    // 2) Global widget load
    window.elementorFrontend.hooks.addAction(
        'frontend/element_ready/wr-hero-slider.global',
        initHandler
    );

    // 3) En kritik olan: widget'ın kendi ID'si
    window.elementorFrontend.hooks.addAction(
        'frontend/element_ready/wr-hero-slider.wr-hero-slider',
        initHandler
    );
}

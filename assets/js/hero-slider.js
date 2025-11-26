document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.wr-hero-slider-swiper');

    sliders.forEach(slider => {

        // --- Smart aspect-ratio hesaplama (ilk slide üzerinden) ---
        const firstSlide = slider.querySelector('.wr-hero-slide');
        if (firstSlide) {
            let bg = firstSlide.style.backgroundImage || window.getComputedStyle(firstSlide).backgroundImage;

            if (bg && bg !== 'none') {
                const match = bg.match(/url\(["']?(.*?)["']?\)/);
                if (match && match[1]) {
                    const img = new Image();
                    img.onload = function () {
                        if (this.width && this.height) {
                            const ratio = (this.height / this.width) * 100; // height/width * 100
                            slider.style.setProperty('--wr-hero-ratio', ratio + '%');
                        }
                    };
                    img.src = match[1];
                }
            }
        }

        // --- Swiper init ---
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
});

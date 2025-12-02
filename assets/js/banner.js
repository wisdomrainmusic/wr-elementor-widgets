/**
 * WR Banner Widget – Minimal, Vanilla JS
 * - Mobil odaklı (görsel merkezlenmiş, kayma yok)
 * - Elementor editor + frontend auto init
 */

(function () {
    'use strict';

    /**
     * Tek bir scope içinde (Elementor widget alanı) banner init.
     * Şimdilik sadece "data-init" flag’i ile hafif koruma yapıyoruz.
     * Gerekirse burada ileride küçük animasyonlar, ölçüm vb. eklenebilir.
     */
    function initWrBanner(scope) {
        var root = scope || document;
        var banners = root.querySelectorAll('.wr-banner-widget');

        if (!banners || !banners.length) {
            return;
        }

        banners.forEach(function (banner) {
            if (banner.dataset.wrBannerInit === '1') {
                return;
            }
            banner.dataset.wrBannerInit = '1';

            // iOS Safari için küçük safe-guard:
            // Görsel yüklendikten sonra "object-position" merkezleme garantisi.
            var img = banner.querySelector('.wr-banner-media img');
            if (img) {
                if (img.complete) {
                    normalizeImage(img);
                } else {
                    img.addEventListener('load', function () {
                        normalizeImage(img);
                    });
                }
            }
        });
    }

    function normalizeImage(img) {
        // İleride gerekirse aspect-ratio fix vs. eklenebilir.
        // Şimdilik sadece "object-position:center" garanti ediyoruz.
        img.style.objectPosition = 'center center';
    }

    /**
     * Frontend ready
     */
    function initOnDomReady() {
        initWrBanner(document);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOnDomReady);
    } else {
        initOnDomReady();
    }

    /**
     * Elementor Editor entegrasyonu (vanilla JS ile)
     */
    function initElementorIntegration() {
        if (
            typeof window.elementorFrontend === 'undefined' ||
            !window.elementorFrontend.hooks
        ) {
            return;
        }

        // Widget adı: get_name() → 'wr-banner'
        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/wr-banner.default',
            function (element) {
                // Elementor çoğu yerde jQuery nesnesi gönderir; her iki durumu da destekleyelim.
                var scope = element;

                if (Array.isArray(element) && element[0]) {
                    scope = element[0];
                } else if (element instanceof window.jQuery) {
                    scope = element[0];
                }

                initWrBanner(scope);
            }
        );
    }

    // Elementor frontend init için bekle
    // jQuery eventine bağımlı kalmamak için polling yerine sade bir check yapıyoruz.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initElementorIntegration);
    } else {
        initElementorIntegration();
    }
})();

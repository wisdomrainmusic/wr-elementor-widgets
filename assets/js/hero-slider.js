(function ($) {
  'use strict';

  const initHeroSlider = function ($scope) {
    const $slider = $scope.find('.wr-hero-slider');
    if (!$slider.length) {
      return;
    }

    if (typeof Swiper === 'undefined') {
      return;
    }

    $slider.each(function () {
      const $wrapper = $(this);
      const $swiperEl = $wrapper.find('.wr-hero-slider-swiper');

      if (!$swiperEl.length) {
        return;
      }

      if ($swiperEl[0].swiper) {
        $swiperEl[0].swiper.destroy(true, true);
      }

      const autoplayEnabled = $wrapper.data('autoplay') === true || $wrapper.data('autoplay') === 'true';
      const delay = parseInt($wrapper.data('delay'), 10) || 4000;
      const loop = $wrapper.data('loop') === true || $wrapper.data('loop') === 'true';
      const showArrows = $wrapper.data('arrows') === true || $wrapper.data('arrows') === 'true';
      const showDots = $wrapper.data('dots') === true || $wrapper.data('dots') === 'true';

      const swiperOptions = {
        loop,
        slidesPerView: 1,
        spaceBetween: 0,
        autoplay: autoplayEnabled ? { delay, disableOnInteraction: false } : false,
        pagination: showDots
          ? {
              el: $swiperEl.find('.swiper-pagination')[0],
              clickable: true,
            }
          : false,
        navigation: showArrows
          ? {
              nextEl: $swiperEl.find('.swiper-button-next')[0],
              prevEl: $swiperEl.find('.swiper-button-prev')[0],
            }
          : false,
      };

      if (!showDots) {
        $swiperEl.find('.swiper-pagination').hide();
      } else {
        $swiperEl.find('.swiper-pagination').show();
      }

      if (!showArrows) {
        $swiperEl.find('.swiper-button-next, .swiper-button-prev').hide();
      } else {
        $swiperEl.find('.swiper-button-next, .swiper-button-prev').show();
      }

      new Swiper($swiperEl[0], swiperOptions);
    });
  };

  $(function () {
    initHeroSlider($(document));
  });

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wr-hero-slider.default', initHeroSlider);
  });
})(jQuery);

(function ($) {
  'use strict';

  function initHeroSliderFull($scope) {
    var $widgets = $scope.find('.wr-hero-slider-full');
    if (!$widgets.length) return;
    if (typeof Swiper === 'undefined') return;

    $widgets.each(function () {
      var $root = $(this);
      var swiperEl = $root.find('.wr-hero-slider-full__swiper')[0];
      if (!swiperEl) return;

      if (swiperEl.swiper) {
        swiperEl.swiper.destroy(true, true);
      }

      var $tabs = $root.find('.wr-hero-slider-full__tab');
      var prevEl = $root.find('.wr-hero-slider-full__arrow--prev')[0];
      var nextEl = $root.find('.wr-hero-slider-full__arrow--next')[0];

      var swiper = new Swiper(swiperEl, {
        slidesPerView: 1,
        speed: 650,
        loop: false,
        navigation: nextEl && prevEl ? { nextEl: nextEl, prevEl: prevEl } : undefined,
        on: {
          slideChange: function () {
            updateTabs(swiper.realIndex);
          }
        }
      });

      function updateTabs(activeIndex) {
        if (!$tabs.length) return;
        $tabs.removeClass('is-active');
        var $active = $tabs.eq(activeIndex);
        if ($active.length) {
          $active.addClass('is-active');
        }
      }

      if ($tabs.length) {
        $tabs.each(function (idx) {
          $(this).off('click.wrHeroFull').on('click.wrHeroFull', function () {
            if (swiper) {
              swiper.slideTo(idx);
            }
          });
        });
      }

      updateTabs(swiper.realIndex || 0);
    });
  }

  $(function () {
    initHeroSliderFull($(document));
  });

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/hero-slider-full.default', initHeroSliderFull);
  });
})(jQuery);

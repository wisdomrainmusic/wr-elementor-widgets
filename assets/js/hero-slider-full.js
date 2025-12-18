(function ($) {
  'use strict';

  function initHeroSliderFull($scope) {
    var $widgets = $scope.find('.wr-hero-slider-full');
    if (!$widgets.length) return;
    if (typeof Swiper === 'undefined') return;

    $widgets.each(function () {
      var $root = $(this);
      var root = $root[0];
      var swiperEl = $root.find('.wr-hero-slider-full__swiper')[0];
      if (!swiperEl) return;

      if (swiperEl.swiper) {
        swiperEl.swiper.destroy(true, true);
      }

      var $tabs = $root.find('.wr-hero-slider-full__tab');
      var prevEl = $root.find('.wr-hero-slider-full__arrow--prev')[0];
      var nextEl = $root.find('.wr-hero-slider-full__arrow--next')[0];

      var loop = root && root.getAttribute('data-loop') === '1';
      var autoplayOn = root && root.getAttribute('data-autoplay') === '1';
      var delay = parseInt((root && root.getAttribute('data-delay')) || '5000', 10);
      var pauseHover = root && root.getAttribute('data-pause-hover') === '1';

      var config = {
        loop: loop,
        slidesPerView: 1,
        speed: 550,
        navigation: nextEl && prevEl ? { nextEl: nextEl, prevEl: prevEl } : undefined,
        on: {
          slideChange: function (sw) {
            updateTabs(sw.realIndex);
          }
        }
      };

      if (autoplayOn) {
        config.autoplay = {
          delay: isNaN(delay) ? 5000 : delay,
          disableOnInteraction: false,
          pauseOnMouseEnter: pauseHover
        };
      }

      var swiper = new Swiper(swiperEl, config);

      // --- Autoplay fallback (in case Swiper Autoplay module is not bundled) ---
      function clearFallback() {
        if (root.__wrAutoplayTimer) {
          clearInterval(root.__wrAutoplayTimer);
          root.__wrAutoplayTimer = null;
        }
      }

      function startFallback() {
        clearFallback();
        var d = isNaN(delay) ? 5000 : delay;

        root.__wrAutoplayTimer = setInterval(function () {
          if (!swiper || swiper.destroyed) return;

          if (loop) {
            swiper.slideNext();
            return;
          }

          // no loop: go next, if end jump to 0
          if (swiper.isEnd) {
            swiper.slideTo(0);
          } else {
            swiper.slideNext();
          }
        }, d);
      }

      if (autoplayOn) {
        // If Swiper has autoplay module, start it. Otherwise, use fallback.
        if (swiper.autoplay && typeof swiper.autoplay.start === 'function') {
          swiper.autoplay.start();
        } else {
          startFallback();

          if (pauseHover) {
            $root.off('mouseenter.wrHeroAuto mouseleave.wrHeroAuto')
              .on('mouseenter.wrHeroAuto', function () { clearFallback(); })
              .on('mouseleave.wrHeroAuto', function () { startFallback(); });
          }
        }
      } else {
        clearFallback();
      }

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
              if (swiper.params && swiper.params.loop && typeof swiper.slideToLoop === 'function') {
                swiper.slideToLoop(idx);
              } else {
                swiper.slideTo(idx);
              }
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

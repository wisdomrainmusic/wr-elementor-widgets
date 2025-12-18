(function ($) {
  "use strict";

  function parseJSONSafe(str) {
    try { return JSON.parse(str); } catch (e) { return null; }
  }

  function initOne($root) {
    var swiperEl = $root.find('.swiper')[0];
    if (!swiperEl || typeof Swiper === 'undefined') return;

    // Destroy if already initialized (Elementor editor re-renders)
    if (swiperEl.swiper) {
      swiperEl.swiper.destroy(true, true);
    }

    var settings = parseJSONSafe($root.attr('data-settings')) || {};
    var perView  = settings.perView  || { d:3, t:2, m:1 };
    var perGroup = settings.perGroup || { d:3, t:2, m:1 };

    var autoplay = false;
    if (settings.autoplay) {
      autoplay = { delay: settings.autoplayDelay || 3500, disableOnInteraction: false };
    }

    var nextEl = $root.find('.wr-tf-next')[0];
    var prevEl = $root.find('.wr-tf-prev')[0];
    var pagEl  = $root.find('.wr-tf-pagination')[0];

    new Swiper(swiperEl, {
      slidesPerView: perView.d,
      slidesPerGroup: perGroup.d,
      spaceBetween: settings.spaceBetween || 18,
      speed: settings.speed || 600,
      loop: !!settings.loop,
      watchOverflow: true,
      autoplay: autoplay,
      navigation: (nextEl && prevEl) ? { nextEl: nextEl, prevEl: prevEl } : undefined,
      pagination: (pagEl) ? { el: pagEl, clickable: true } : undefined,
      breakpoints: {
        0:    { slidesPerView: perView.m, slidesPerGroup: perGroup.m },
        768:  { slidesPerView: perView.t, slidesPerGroup: perGroup.t },
        1024: { slidesPerView: perView.d, slidesPerGroup: perGroup.d }
      }
    });
  }

  function initTestimonialsFull($scope) {
    var $widgets = $scope.find('.wr-tf');
    if (!$widgets.length) return;
    $widgets.each(function(){ initOne($(this)); });
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wr-testimonial-full.default', initTestimonialsFull);
  });
})(jQuery);

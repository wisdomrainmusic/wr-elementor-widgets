(function ($) {
  "use strict";

  function parseJSONSafe(str) {
    try { return JSON.parse(str); } catch(e){ return null; }
  }

  function initTestimonials($scope) {
    var $widget = $scope.find('.wr-testimonials');
    if (!$widget.length) return;

    // one widget instance at a time (Elementor can render multiple)
    $widget.each(function(){
      var $root = $(this);
      var swiperEl = $root.find('.swiper')[0];
      if (!swiperEl) return;

      // destroy if already inited
      if (swiperEl.swiper) {
        swiperEl.swiper.destroy(true, true);
      }

      var settings = parseJSONSafe($root.attr('data-settings')) || {};
      var spaceBetween = settings.spaceBetween || 18;
      var speed = settings.speed || 600;

      var perView = settings.perView || { d:3, t:2, m:1 };
      var perGroup = settings.perGroup || { d:3, t:2, m:1 };

      var autoplay = false;
      if (settings.autoplay) {
        autoplay = { delay: settings.autoplayDelay || 3500, disableOnInteraction: false };
      }

      var nextEl = $root.find('.wr-t-next')[0];
      var prevEl = $root.find('.wr-t-prev')[0];
      var pagEl  = $root.find('.wr-t-pagination')[0];

      new Swiper(swiperEl, {
        slidesPerView: perView.d,
        slidesPerGroup: perGroup.d,
        spaceBetween: spaceBetween,
        speed: speed,
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
    });
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wr-testimonials.default', initTestimonials);
  });

})(jQuery);

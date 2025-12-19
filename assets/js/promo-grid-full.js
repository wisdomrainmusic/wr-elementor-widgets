(function($){
  'use strict';

  function bindInstance($scope){
    $scope.find('video.wr-pgfull__video').each(function(){
      var video = this;
      var $v = $(video);
      if ($v.data('wrBound')) return;
      $v.data('wrBound', true);

      $v.on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        try {
          if (video.paused) video.play();
          else video.pause();
        } catch (_) {}
      });
    });
  }

  $(window).on('elementor/frontend/init', function(){
    if (window.elementorFrontend && elementorFrontend.hooks) {
      elementorFrontend.hooks.addAction(
        'frontend/element_ready/wr-promo-grid-full.default',
        function($scope){ bindInstance($scope); }
      );
    }
  });

  $(function(){ bindInstance($(document)); });

})(jQuery);

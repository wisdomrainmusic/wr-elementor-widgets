(function($){
    'use strict';

    function bindVideos($scope){
        $scope.find('.wr-pgfull__video').each(function(){
            var video = this;
            var $video = $(video);

            if ($video.data('wrVideoBound')) return;
            $video.data('wrVideoBound', true);

            $video.on('click', function(e){
                e.preventDefault();
                e.stopPropagation();

                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
            });
        });
    }

    $(window).on('elementor/frontend/init', function(){
        if (window.elementorFrontend) {
            elementorFrontend.hooks.addAction('frontend/element_ready/wr-promo-grid-full.default', function($scope){
                bindVideos($scope);
            });
        }
    });

    $(document).ready(function(){
        bindVideos($(document));
    });
})(jQuery);

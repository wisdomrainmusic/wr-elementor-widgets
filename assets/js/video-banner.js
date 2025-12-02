(function($){

    // Load YouTube API once
    if (!window.WR_YT_API_LOADED) {
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScript = document.getElementsByTagName('script')[0];
        firstScript.parentNode.insertBefore(tag, firstScript);
        window.WR_YT_API_LOADED = true;
    }

    let initialized = false;
    let players = {};

    window.onYouTubeIframeAPIReady = function(){
        initialized = true;
        initAllPlayers();
    };

    function initAllPlayers() {
        $('.wr-video-banner').each(function(){
            initPlayer($(this));
        });
    }

    function initPlayer($wrap) {

        if ($wrap.data('player-type') !== 'youtube') return; // Vimeo destek sonradan eklenir

        let id = $wrap.data('video-id');
        let aspect = $wrap.data('aspect');
        let container = $wrap.find('.wr-video-player')[0];

        players[id] = new YT.Player(container, {
            videoId: id,
            playerVars: {
                autoplay: 0,
                controls: 1,
                rel: 0,
                fs: 0,
                modestbranding: 1,
                playsinline: 1
            },
            events: {
                'onReady': function(event) {
                    resizePlayer($wrap, event.target, aspect);
                }
            }
        });

        $(window).on('resize', function(){
            if (players[id]) resizePlayer($wrap, players[id], aspect);
        });
    }

    function aspectRatio(aspect){
        return (aspect === 'vertical') ? (9/16) : (16/9);
    }

    function resizePlayer($wrap, player, aspect) {

        let containerWidth = $wrap.width();
        let containerHeight;

        // Auto height calculation
        if (aspect === 'vertical') {
            // 9:16 → dikey video
            containerHeight = containerWidth * (16/9);
        } else {
            // 16:9 → yatay video
            containerHeight = containerWidth * (9/16);
        }

        // Apply container height
        $wrap.css('height', containerHeight + 'px');

        // Fit video element inside
        let iframe = $(player.getIframe());
        iframe.css({
            width: containerWidth,
            height: containerHeight,
            top: 0,
            left: 0,
            position: 'absolute',
            transform: 'none'
        });
    }

    $(document).ready(function(){
        if (initialized) initAllPlayers();
    });

})(jQuery);

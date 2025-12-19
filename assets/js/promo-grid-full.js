(function () {
    'use strict';

    const handleVideoToggle = (video) => {
        if (!video) return;
        video.addEventListener('click', (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            if (video.paused) {
                video.play().catch(() => {});
            } else {
                video.pause();
            }
        });
    };

    const initWidget = (scope) => {
        const root = scope instanceof Element ? scope : (scope && scope[0] ? scope[0] : document);
        const wrapper = root.querySelector('.wr-pgfull');
        if (!wrapper) return;

        const tiles = wrapper.querySelectorAll('.wr-pgfull__tile');
        tiles.forEach((tile) => {
            const video = tile.querySelector('video.wr-pgfull__video');
            handleVideoToggle(video);
        });
    };

    const onElementorFrontendInit = () => {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/wr-promo-grid-full.default', initWidget);
        } else {
            document.querySelectorAll('.wr-pgfull').forEach((wrapper) => initWidget(wrapper));
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onElementorFrontendInit);
    } else {
        onElementorFrontendInit();
    }
})();

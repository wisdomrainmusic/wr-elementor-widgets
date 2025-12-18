/* ---------------------------------------------------------
   WR Campaign Bar JS
   - Elementor re-render marquee stabilize
---------------------------------------------------------- */

(function () {
  "use strict";

  function restartMarquee(scope) {
    const tracks = (scope || document).querySelectorAll('[data-wr-marquee]');
    tracks.forEach(track => {
      // animation restart (editor + bazı mobil webview freeze fix)
      track.style.animation = "none";
      // reflow
      track.offsetHeight; // eslint-disable-line no-unused-expressions
      track.style.animation = "";
      track.style.animationPlayState = "running";
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    restartMarquee(document);
  });

  // Elementor hooks (frontend + editor)
  document.addEventListener("elementor/frontend/init", function () {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;

    window.elementorFrontend.hooks.addAction(
      "frontend/element_ready/wr-campaign-bar.default",
      function ($scope) {
        const el = $scope && $scope[0] ? $scope[0] : document;
        restartMarquee(el);
      }
    );
  });
})();

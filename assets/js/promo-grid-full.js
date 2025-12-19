(function ($) {
  const initPromoGridFull = ($scope) => {
    // Placeholder for future interactive behaviors.
    return $scope;
  };

  $(window).on('elementor/frontend/init', () => {
    const widgetHandler = ( $element ) => {
      initPromoGridFull( $element );
    };

    elementorFrontend.hooks.addAction(
      'frontend/element_ready/wr-promo-grid-full.default',
      widgetHandler
    );
  });
})(jQuery);

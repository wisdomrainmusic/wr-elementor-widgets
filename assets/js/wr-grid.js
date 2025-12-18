(function ($) {
  "use strict";

  var gridSelector = '.wr-ajax-grid[data-widget="product-grid"]';

  function debugLog() {
    if (window.wrGridData && window.wrGridData.debug) {
      // eslint-disable-next-line no-console
      console.log.apply(console, arguments);
    }
  }

  function cfgFrom($grid) {
    var cfg = window.wrGridData || {};
    var $wrap = $grid.closest(".wr-product-grid-wrapper");

    var dataNonce =
      $grid.attr("data-nonce") ||
      $wrap.attr("data-nonce") ||
      "";

    return {
      ajax_url: cfg.ajax_url || (window.wrEwAjax && window.wrEwAjax.ajax_url) || "",
      nonce: dataNonce || cfg.nonce || "",
      debug: !!cfg.debug
    };
  }

  function buildSkeleton(count) {
    var html = "";
    for (var i = 0; i < count; i++) {
      html += '<div class="wr-product-item wr-skeleton">';
      html += '<div class="wr-skel-image"></div>';
      html += '<div class="wr-skel-line"></div>';
      html += '<div class="wr-skel-line short"></div>';
      html += "</div>";
    }
    return html;
  }

  function getGridFrom($el) {
    var $grid = $el.closest(gridSelector);
    if ($grid.length) return $grid;

    var $wrap = $el.closest(".wr-product-grid-wrapper");
    if ($wrap.length) {
      $grid = $wrap.find(gridSelector).first();
      if ($grid.length) return $grid;
    }
    return $();
  }

  function getPerPage($grid) {
    var $wrap = $grid.closest(".wr-product-grid-wrapper");
    var v = $grid.attr("data-per-page") || $wrap.attr("data-per-page") || 12;
    v = parseInt(v, 10);
    return !v || isNaN(v) ? 12 : v;
  }

  function getActiveCat($wrap) {
    var $a = $wrap.find(".wr-filter-sidebar .wr-cat-item.is-active");
    return $a.length ? parseInt($a.attr("data-cat") || 0, 10) : 0;
  }

  function stopLoading($grid) {
    $grid.removeClass("wr-loading");
  }

  function loadProducts($grid, cat, page) {
    if (!$grid.length) return;

    var cfg = cfgFrom($grid);
    if (!cfg.ajax_url) {
      debugLog("WR Grid: missing ajax_url");
      return;
    }

    var perPage = getPerPage($grid);
    var $items = $grid.find(".wr-product-items");
    var $pag = $grid.find(".wr-pagination");
    var previousItems = $items.html();
    var previousPagination = $pag.html();

    $grid.addClass("wr-loading");
    $items.html(buildSkeleton(perPage));

    $.ajax({
      url: cfg.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "wr_pg_filter_products",
        nonce: cfg.nonce,
        cat: cat || 0,
        page: page || 1,
        per_page: perPage
      },
      success: function (res) {
        if (!res || !res.success || !res.data) {
          debugLog("WR Grid: invalid response", res);
          $items.html(previousItems);
          $pag.html(previousPagination);
          stopLoading($grid);
          return;
        }

        $items.html(res.data.items_html || "");

        if (typeof res.data.pagination_html !== "undefined") {
          $pag.html(res.data.pagination_html || "");
        }

        stopLoading($grid);
      },
      error: function (xhr) {
        debugLog("WR Grid AJAX error", xhr && xhr.status, xhr && xhr.responseText);
        // eslint-disable-next-line no-console
        console.error("WR Grid AJAX error", xhr && xhr.status, xhr && xhr.responseText);
        $items.html(previousItems);
        $pag.html(previousPagination);
        stopLoading($grid);
      }
    });
  }

  // Category click
  $(document).on("click", ".wr-filter-sidebar .wr-cat-item[data-cat]", function (e) {
    e.preventDefault();

    var $item = $(this);
    var $grid = getGridFrom($item);
    if (!$grid.length) return;

    var $wrap = $grid.closest(".wr-product-grid-wrapper");
    $wrap.find(".wr-filter-sidebar .wr-cat-item").removeClass("is-active");
    $item.addClass("is-active");

    loadProducts($grid, parseInt($item.attr("data-cat") || 0, 10), 1);
  });

  // Pagination click
  $(document).on("click", gridSelector + " .wr-pagination a.page-numbers", function (e) {
    e.preventDefault();

    var $link = $(this);
    var $grid = getGridFrom($link);
    if (!$grid.length) return;

    var $wrap = $grid.closest(".wr-product-grid-wrapper");
    var cat = getActiveCat($wrap);

    var pageAttr = $link.attr("data-page");
    var page = parseInt(pageAttr || $link.text(), 10);
    if (!page || isNaN(page)) page = 1;

    loadProducts($grid, cat, page);
  });

  // Mobile sidebar toggle
  $(document).on("click", ".wr-filter-header", function () {
    $(this).closest(".wr-filter-sidebar").toggleClass("is-open");
  });
})(jQuery);

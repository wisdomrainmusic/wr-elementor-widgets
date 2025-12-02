(function($) {
    'use strict';

    var config = window.wrGridData || {};
    var gridSelector = '[data-widget="product-grid"]';
    var paginationSelector = gridSelector + ' .wr-pagination a';

    function buildSkeleton(count) {
        var html = '';
        for (var i = 0; i < count; i++) {
            html += '<div class="wr-product-item wr-skeleton">';
            html += '<div class="wr-skel-image"></div>';
            html += '<div class="wr-skel-line"></div>';
            html += '<div class="wr-skel-line short"></div>';
            html += '</div>';
        }
        return html;
    }

    function getWidgetGrid($element) {
        var $wrapper = $element.closest('.wr-product-grid-wrapper');
        if ($wrapper.length) {
            var $grid = $wrapper.find(gridSelector).first();
            if ($grid.length) {
                return $grid;
            }
        }

        return $element.closest(gridSelector);
    }

    function getActiveCategory($wrapper) {
        var $activeCat = $wrapper.find('.wr-filter-sidebar li.active');
        if ($activeCat.length) {
            return $activeCat.data('cat') || 0;
        }
        return 0;
    }

    function getPageFromLink($link) {
        var href = $link.attr('href') || '';
        var pageMatch = href.match(/(?:paged=|page\/)(\d+)/);
        var text = $link.text().trim();

        if (pageMatch && pageMatch[1]) {
            return parseInt(pageMatch[1], 10) || 1;
        }

        var textNumber = parseInt(text, 10);
        return isNaN(textNumber) ? 1 : textNumber;
    }

    function loadFilteredProducts($grid, cat, page) {
        if (!$grid.length || !config.ajax_url) return;

        var $items = $grid.find('.wr-product-items');
        var perPage = parseInt($items.data('per-page') || $grid.data('per-page') || 12, 10);

        $items
            .addClass('is-loading')
            .html(buildSkeleton(perPage));

        $.ajax({
            url: config.ajax_url,
            type: 'POST',
            data: {
                action: 'wr_filter_products',
                cat: cat || 0,
                page: page || 1,
                nonce: config.nonce
            },
            success: function(response) {
                $items
                    .removeClass('is-loading')
                    .hide()
                    .html(response)
                    .fadeIn(150);
            },
            error: function() {
                $items.removeClass('is-loading');
            }
        });
    }

    function loadGridPage($grid, page, cat) {
        if (!$grid.length || !config.ajax_url) return;

        $grid.addClass('wr-loading');

        $.ajax({
            url: config.ajax_url,
            type: 'POST',
            data: {
                action: 'wr_ajax_load_products',
                page: page || 1,
                cat: cat || 0,
                nonce: config.nonce
            },
            success: function(html) {
                $grid
                    .removeClass('wr-loading')
                    .html(html);
            },
            error: function() {
                $grid.removeClass('wr-loading');
            }
        });
    }

    $(document).on('click', '.wr-filter-sidebar li[data-cat]', function() {
        var $item = $(this);
        var $grid = getWidgetGrid($item);

        if (!$grid.length) return;

        var $wrapper = $grid.closest('.wr-product-grid-wrapper');
        $wrapper.find('.wr-filter-sidebar li').removeClass('active');
        $item.addClass('active');

        loadFilteredProducts($grid, $item.data('cat') || 0, 1);
    });

    $(document).on('click', paginationSelector, function(e) {
        var $link = $(this);
        var $grid = getWidgetGrid($link);

        if (!$grid.length) return;

        e.preventDefault();

        var page = getPageFromLink($link);
        var cat = getActiveCategory($grid.closest('.wr-product-grid-wrapper'));

        loadGridPage($grid, page, cat);
    });

    $(document).on('click', '.wr-filter-header', function() {
        var $header = $(this);
        var $wrapper = $header.closest('.wr-product-grid-wrapper');

        if (!$wrapper.find(gridSelector).length) return;

        $header.toggleClass('is-open');
        $header.siblings('ul').slideToggle(150);
    });
})(jQuery);

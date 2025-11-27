(function($) {
    'use strict';

    var config = window.wrGridData || {};
    var gridSelector = '[data-widget="product-grid"]';
    var paginationSelector = gridSelector + ' .wr-pagination a';
    var wishlistIds = [];

    function normalizeIds(ids) {
        return $.map(ids, function(id) {
            id = parseInt(id, 10);
            return isNaN(id) ? null : id;
        });
    }

    function getButtonProductId($btn) {
        var productId = $btn.data('product-id');

        if (typeof productId === 'undefined') {
            productId = $btn.data('id');
        }

        return parseInt(productId, 10);
    }

    function getStoredWishlist() {
        var stored = localStorage.getItem('wr_wishlist');
        if (!stored) return [];

        try {
            var parsed = JSON.parse(stored);
            return Array.isArray(parsed) ? normalizeIds(parsed) : [];
        } catch (e) {
            return [];
        }
    }

    function saveLocalWishlist() {
        localStorage.setItem('wr_wishlist', JSON.stringify(wishlistIds));
    }

    function updateWishlistCount() {
        $('.wr-wishlist-count').text(wishlistIds.length);
    }

    function applyWishlistState($scope) {
        var $context = $scope && $scope.length ? $scope : $(gridSelector);
        var $buttons = $context.find('.wr-wishlist-btn');

        $buttons.each(function() {
            var $btn = $(this);
            var id = getButtonProductId($btn);
            if (wishlistIds.indexOf(id) !== -1) {
                $btn.addClass('active');
            } else {
                $btn.removeClass('active');
            }
        });

        updateWishlistCount();
    }

    function fetchWishlist() {
        if (!config.logged_in) return;

        $.post(config.ajax_url, { action: 'wr_get_wishlist' }, function(response) {
            if ($.isArray(response)) {
                wishlistIds = normalizeIds(response);
                applyWishlistState();
            }
        });
    }

    function addWishlistId(id) {
        if (wishlistIds.indexOf(id) === -1) {
            wishlistIds.push(id);
        }
    }

    function removeWishlistId(id) {
        wishlistIds = wishlistIds.filter(function(item) {
            return item !== id;
        });
    }

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

                applyWishlistState($grid);
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

                applyWishlistState($grid);
            },
            error: function() {
                $grid.removeClass('wr-loading');
            }
        });
    }

    if (config.logged_in) {
        fetchWishlist();
    } else {
        wishlistIds = getStoredWishlist();
        applyWishlistState();
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

    $(document).on('click', gridSelector + ' .wr-wishlist-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = getButtonProductId($btn);

        if (!productId) return;

        if (config.logged_in) {
            var action = $btn.hasClass('active') ? 'wr_remove_from_wishlist' : 'wr_add_to_wishlist';

            $.post(config.ajax_url, { action: action, product_id: productId }, function(response) {
                if ($.isArray(response)) {
                    wishlistIds = normalizeIds(response);
                    applyWishlistState(getWidgetGrid($btn));
                }
            });
        } else {
            if ($btn.hasClass('active')) {
                removeWishlistId(productId);
            } else {
                addWishlistId(productId);
            }

            saveLocalWishlist();
            applyWishlistState(getWidgetGrid($btn));
        }
    });

    $(document).on('wr:sync-wishlist', function() {
        applyWishlistState();
    });

})(jQuery);

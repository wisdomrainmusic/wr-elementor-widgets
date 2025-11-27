(function($) {
    'use strict';

    var config = window.wrWishlistData || {};
    var wishlistIds = [];
    var storageKey = 'wr_wishlist';

    function normalizeIds(ids) {
        return $.map(ids || [], function(id) {
            var parsed = parseInt(id, 10);
            return isNaN(parsed) ? null : parsed;
        });
    }

    function loadLocal() {
        var stored = localStorage.getItem(storageKey);

        if (!stored) {
            wishlistIds = [];
            return wishlistIds;
        }

        try {
            var parsed = JSON.parse(stored);
            wishlistIds = Array.isArray(parsed) ? normalizeIds(parsed) : [];
        } catch (e) {
            wishlistIds = [];
        }

        return wishlistIds;
    }

    function saveLocal() {
        localStorage.setItem(storageKey, JSON.stringify(wishlistIds));
    }

    function updateCount() {
        $('.wr-wishlist-count').text(wishlistIds.length);
    }

    function applyState(root) {
        var $context = root ? $(root) : $(document);
        var ids = wishlistIds;

        $context.find('.wr-wishlist-btn').each(function() {
            var $btn = $(this);
            var productId = parseInt($btn.data('product-id'), 10) || parseInt($btn.data('id'), 10) || 0;

            if (ids.indexOf(productId) !== -1) {
                $btn.addClass('active');
            } else {
                $btn.removeClass('active');
            }
        });

        updateCount();
    }

    function setWishlist(ids, context) {
        wishlistIds = normalizeIds(ids || []);
        saveLocal();
        applyState(context);
    }

    function syncWithServer(payload, context) {
        if (!config.logged_in) {
            return $.Deferred().resolve(wishlistIds).promise();
        }

        var data = $.extend({}, payload || {}, {
            action: 'wr_update_wishlist',
            nonce: config.nonce,
            local_ids: wishlistIds
        });

        return $.post(config.ajax_url, data, function(response) {
            if ($.isArray(response)) {
                setWishlist(response, context);
            }
        });
    }

    function add(id, context) {
        if (!id) return wishlistIds;

        if (wishlistIds.indexOf(id) === -1) {
            wishlistIds.push(id);
        }

        if (config.logged_in) {
            return syncWithServer({ product_id: id, operation: 'add' }, context);
        }

        saveLocal();
        applyState(context);
        return wishlistIds;
    }

    function remove(id, context) {
        wishlistIds = wishlistIds.filter(function(item) {
            return item !== id;
        });

        if (config.logged_in) {
            return syncWithServer({ product_id: id, operation: 'remove' }, context);
        }

        saveLocal();
        applyState(context);
        return wishlistIds;
    }

    function toggle(id, context) {
        if (!id) return wishlistIds;

        if (wishlistIds.indexOf(id) !== -1) {
            return remove(id, context);
        }

        return add(id, context);
    }

    function bind(root) {
        var $context = root ? $(root) : $(document);

        $context.find('.wr-wishlist-btn').off('click.wrWishlist').on('click.wrWishlist', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var productId = parseInt($btn.data('product-id'), 10) || parseInt($btn.data('id'), 10) || 0;

            if (!productId) return;

            toggle(productId, $context);
        });
    }

    function init() {
        loadLocal();

        if (config.logged_in) {
            syncWithServer({}, document);
        } else {
            applyState(document);
        }

        bind(document);
    }

    $(document).ready(init);

    window.wrWishlist = {
        applyState: applyState,
        bind: bind,
        add: add,
        remove: remove,
        toggle: toggle,
        normalizeIds: normalizeIds,
        loadLocal: loadLocal,
        saveLocal: saveLocal,
        syncWithServer: syncWithServer
    };
})(jQuery);

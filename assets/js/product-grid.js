jQuery(function($){

    var wishlistIds = [];

    function normalizeIds(ids) {
        return $.map(ids, function(id){
            id = parseInt(id, 10);
            return isNaN(id) ? null : id;
        });
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

    function applyWishlistState() {
        $('.wr-wishlist-btn').each(function(){
            var $btn = $(this);
            var id = parseInt($btn.data('id'), 10);
            if (wishlistIds.indexOf(id) !== -1) {
                $btn.addClass('active');
            } else {
                $btn.removeClass('active');
            }
        });

        updateWishlistCount();
    }

    function fetchWishlist() {
        if (!wrpg.logged_in) return;

        $.post(wrpg.ajax_url, { action: 'wr_get_wishlist' }, function(response){
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
        wishlistIds = wishlistIds.filter(function(item){
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

    function loadProducts(cat, page) {
        page = page || 1;

        var $grid = $('.wr-product-items');
        if (!$grid.length) return;

        var perPage = $grid.data('per-page') || 12;

        $grid
            .addClass('is-loading')
            .html(buildSkeleton(perPage));

        $.ajax({
            url: wrpg.ajax_url,
            type: 'POST',
            data: {
                action: 'wr_filter_products',
                cat: cat || 0,
                page: page
            },
            success: function(response) {
                $grid
                    .removeClass('is-loading')
                    .hide()
                    .html(response)
                    .fadeIn(150);

                applyWishlistState();
            }
        });
    }

    if (wrpg.logged_in) {
        fetchWishlist();
    } else {
        wishlistIds = getStoredWishlist();
        applyWishlistState();
    }

    // Category filter click
    $(document).on('click', '.wr-filter-sidebar li[data-cat]', function(){
        var $li = $(this);
        var cat = $li.data('cat') || 0;

        $('.wr-filter-sidebar li').removeClass('active');
        $li.addClass('active');

        loadProducts(cat, 1);
    });

    // Pagination click
    $(document).on('click', '.wr-product-pagination .wr-page-btn', function(e){
        e.preventDefault();
        var page = $(this).data('page') || 1;
        var $activeCat = $('.wr-filter-sidebar li.active');
        var cat = $activeCat.length ? $activeCat.data('cat') : 0;

        loadProducts(cat, page);
    });

    // Mobile sidebar toggle
    $(document).on('click', '.wr-filter-header', function(){
        $(this).toggleClass('is-open');
        $(this).siblings('ul').slideToggle(150);
    });

    // Wishlist toggle
    $(document).on('click', '.wr-wishlist-btn', function(e){
        e.preventDefault();
        var $btn = $(this);
        var productId = parseInt($btn.data('id'), 10);

        if (!productId) return;

        if (wrpg.logged_in) {
            var action = $btn.hasClass('active') ? 'wr_remove_from_wishlist' : 'wr_add_to_wishlist';

            $.post(wrpg.ajax_url, { action: action, product_id: productId }, function(response){
                if ($.isArray(response)) {
                    wishlistIds = normalizeIds(response);
                    applyWishlistState();
                }
            });
        } else {
            if ($btn.hasClass('active')) {
                removeWishlistId(productId);
            } else {
                addWishlistId(productId);
            }

            saveLocalWishlist();
            applyWishlistState();
        }
    });

});

jQuery(function($){

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
            }
        });
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

});

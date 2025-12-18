(function($){
    'use strict';

    function getData($root){
        return {
            perPage: parseInt($root.data('perPage'), 10) || 9,
            nonce: $root.data('nonce') || '',
            btnText: $root.data('btnText') || 'SEÇENEKLER'
        };
    }

    function setActiveCat($root, cat){
        $root.attr('data-active-cat', cat);
        $root.find('.wr-pg-full__cat').removeClass('is-active');
        $root.find('.wr-pg-full__cat[data-cat="'+ cat +'"]').addClass('is-active');
    }

    function fetchProducts($root, cat, page){
        var data = getData($root);

        $root.addClass('is-loading');

        $.post(wrPgFullData.ajax_url, {
            action: 'wr_pg_full_filter',
            nonce: data.nonce,
            cat: cat,
            page: page,
            per_page: data.perPage,
            button_text: data.btnText
        })
        .done(function(resp){
            if(resp && resp.success && resp.data){
                $root.find('.wr-pg-full__grid-inner').html(resp.data.items_html);
                $root.find('.wr-pg-full__pagination').html(resp.data.pagination_html);
                setActiveCat($root, cat);
            } else if (resp && resp.data && resp.data.message && wrPgFullData.debug) {
                console.warn(resp.data.message);
            }
        })
        .fail(function(){
            if (wrPgFullData.debug) {
                console.error('WR PG Full AJAX failed');
            }
        })
        .always(function(){
            $root.removeClass('is-loading');
        });
    }

    function init(){
        $(document)
            .on('click', '.wr-pg-full .wr-pg-full__cat', function(e){
                e.preventDefault();
                var $btn  = $(this);
                var $root = $btn.closest('.wr-pg-full');
                var cat   = parseInt($btn.data('cat'), 10) || 0;
                fetchProducts($root, cat, 1);
            })
            .on('click', '.wr-pg-full .wr-pg-full__page', function(e){
                e.preventDefault();
                var $btn  = $(this);
                var $root = $btn.closest('.wr-pg-full');
                var cat   = parseInt($root.attr('data-active-cat'), 10) || 0;
                var page  = parseInt($btn.data('page'), 10) || 1;
                fetchProducts($root, cat, page);
            })
            .on('click', '.wr-pg-full .wr-pg-full__more-toggle', function(e){
                e.preventDefault();
                var $toggle = $(this);
                var $hidden = $toggle.closest('.wr-pg-full__cats-wrap').find('.wr-pg-full__cats-hidden');
                $hidden.toggleClass('is-open');
            })
            .on('click', '.wr-pg-full .wr-pg-full__sidebar-toggle', function(e){
                e.preventDefault();
                var $btn = $(this);
                var $sidebar = $btn.closest('.wr-pg-full__sidebar');
                $sidebar.toggleClass('is-open');
            });
    }

    $(init);

})(jQuery);

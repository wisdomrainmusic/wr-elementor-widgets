(function ($) {
    'use strict';

    function logDebug(message, data) {
        if (window.wrPgFullData && window.wrPgFullData.debug) {
            // eslint-disable-next-line no-console
            console.log('[WR PG Full]', message, data || '');
        }
    }

    function getRoot($el) {
        return $el.closest('.wr-pg-full');
    }

    function setActiveCategory($root, catId) {
        $root.find('.wr-pg-full__cat').removeClass('is-active');
        $root.find('.wr-pg-full__cat[data-cat="' + catId + '"]').addClass('is-active');
    }

    function showSkeleton($root) {
        const perPage = parseInt($root.data('per-page'), 10) || 6;
        const skeletons = [];
        for (let i = 0; i < perPage; i++) {
            skeletons.push('<div class="wr-pg-full__item wr-pg-full__item--skeleton"></div>');
        }
        $root.addClass('is-loading');
        $root.find('.wr-pg-full__grid').html(skeletons.join(''));
    }

    function fetchProducts($root, catId, page) {
        const perPage = parseInt($root.data('per-page'), 10) || 12;
        const columns = parseInt($root.data('columns'), 10) === 3 ? 3 : 4;
        const nonce = $root.data('nonce') || (window.wrPgFullData ? window.wrPgFullData.nonce : '');
        const showPrice = $root.data('show-price') === 'yes' ? 'yes' : 'no';
        const buttonText = $root.data('button-text') || 'SEÇENEKLER';

        showSkeleton($root);

        $.post(
            (window.wrPgFullData && window.wrPgFullData.ajax_url) || '',
            {
                action: 'wr_pg_full_filter',
                nonce: nonce,
                cat: catId,
                page: page,
                per_page: perPage,
                columns: columns,
                show_price: showPrice,
                button_text: buttonText,
            }
        )
            .done(function (response) {
                if (!response || !response.success) {
                    return;
                }

                const data = response.data;
                $root.removeClass('is-loading');
                $root.find('.wr-pg-full__grid').html(data.items_html || '');
                $root.find('.wr-pg-full__pagination').html(data.pagination_html || '');
                $root.removeClass('is-cols-3 is-cols-4').addClass('is-cols-' + columns);
                setActiveCategory($root, catId);
                logDebug('AJAX response', data);
            })
            .fail(function (jqXHR) {
                logDebug('AJAX error', jqXHR);
                $root.removeClass('is-loading');
            });
    }

    function toggleSidebar($root) {
        $root.find('.wr-pg-full__sidebar').toggleClass('is-open');
    }

    $(document).on('click', '.wr-pg-full__cat', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const $root = getRoot($btn);
        const catId = $btn.data('cat');
        if (!$root.length) {
            return;
        }
        fetchProducts($root, catId, 1);
    });

    $(document).on('click', '.wr-pg-full__pagination [data-page]', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const $root = getRoot($btn);
        const page = parseInt($btn.data('page'), 10) || 1;
        const activeCat = $root.find('.wr-pg-full__cat.is-active').data('cat') || 0;
        fetchProducts($root, activeCat, page);
    });

    $(document).on('click', '.wr-pg-full__more-toggle', function (e) {
        e.preventDefault();
        const $toggle = $(this);
        const $moreList = $toggle.next('.wr-pg-full__more-list');
        const isHidden = $moreList.prop('hidden');
        $moreList.prop('hidden', !isHidden);
        $toggle.toggleClass('is-open', isHidden);
    });

    $(document).on('click', '.wr-pg-full__sidebar-toggle', function (e) {
        e.preventDefault();
        toggleSidebar(getRoot($(this)));
    });
})(jQuery);

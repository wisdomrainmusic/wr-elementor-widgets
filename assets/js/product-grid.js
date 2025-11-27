jQuery(function($){

    $(document).on('click', '.wr-filter-sidebar li[data-cat]', function(){

        var cat = $(this).data('cat');

        $('.wr-filter-sidebar li').removeClass('active');
        $(this).addClass('active');

        $.ajax({
            url: wrpg.ajax_url,
            type: 'POST',
            data: {
                action: 'wr_filter_products',
                cat: cat
            },
            success: function(response){
                $('.wr-product-items').html(response);
            }
        });

    });

});

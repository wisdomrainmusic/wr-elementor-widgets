document.addEventListener('click', function(event) {
    const paginationLink = event.target.closest('.wr-pagination a');

    if (!paginationLink) {
        return;
    }

    const grid = document.querySelector('#wr-ajax-grid');

    if (!grid) {
        return;
    }

    event.preventDefault();

    const linkUrl = new URL(paginationLink.href, window.location.href);
    const page = linkUrl.searchParams.get('paged') || paginationLink.textContent.trim() || '1';

    grid.classList.add('wr-loading');

    fetch(wr_ajax.ajax_url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'wr_ajax_load_products',
            page: page,
        }),
    })
        .then(function(response) {
            return response.text();
        })
        .then(function(html) {
            grid.innerHTML = html;
            grid.classList.remove('wr-loading');
        })
        .catch(function() {
            grid.classList.remove('wr-loading');
        });
});

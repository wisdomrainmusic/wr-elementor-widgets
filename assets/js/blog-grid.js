/* -------------------------------------------------------------
   WR Blog Grid – AJAX Pagination + Category Filter
-------------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {

    const wrappers = document.querySelectorAll(".wr-blog-grid-wrapper");
    if (!wrappers.length) return;

    wrappers.forEach(function (wrapper) {
        initWrBlogGrid(wrapper);
    });

    function initWrBlogGrid(wrapper) {

        const widgetID   = wrapper.dataset.widgetId;
        const mode       = wrapper.dataset.mode || "auto";
        const perPage    = parseInt(wrapper.dataset.perpage || "6", 10);
        const parentCat  = parseInt(wrapper.dataset.parentCat || "0", 10);
        const excerptLen = parseInt(wrapper.dataset.excerpt || "18", 10);
        const showExcerpt   = wrapper.dataset.showExcerpt || "yes";
        const showReadMore  = wrapper.dataset.showReadmore || "yes";
        const readMoreText  = wrapper.dataset.readmoreText || "Read more";
        const ajaxUrl    = wrapper.dataset.ajaxUrl;
        const nonce      = wrapper.dataset.nonce;

        const gridEl       = document.getElementById("wr-blog-grid-" + widgetID);
        const paginationEl = document.getElementById("wr-blog-pagination-" + widgetID);
        const catList      = wrapper.querySelector(".wr-blog-category-list");

        if (!gridEl || !paginationEl || !ajaxUrl) {
            return;
        }

        let currentCategory = 0;

        /* -----------------------------------------
           CATEGORY CLICK
        ----------------------------------------- */
        if (catList) {
            catList.addEventListener("click", function (e) {

                const item = e.target.closest(".wr-blog-cat-item");
                if (!item) return;

                const catId = parseInt(item.dataset.catId || "0", 10);
                currentCategory = catId;

                // Active class
                catList.querySelectorAll(".wr-blog-cat-item").forEach(function (li) {
                    li.classList.remove("is-active");
                });
                item.classList.add("is-active");

                loadPage(1);
            });
        }

        /* -----------------------------------------
           PAGINATION CLICK (rebinding required)
        ----------------------------------------- */
        function bindPagination() {
            paginationEl.querySelectorAll(".wr-blog-page, .wr-blog-page-btn").forEach(function (btn) {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    const page = parseInt(btn.dataset.page || "1", 10);
                    loadPage(page);
                });
            });
        }

        // Bind initial pagination
        bindPagination();

        /* -----------------------------------------
           AJAX LOAD PAGE
        ----------------------------------------- */
        function loadPage(page) {
            if (mode !== "auto") return;

            wrapper.classList.add("wr-blog-grid-loading");

            const form = new FormData();
            form.append("action", "wr_blog_grid_pagination");
            form.append("nonce", nonce);
            form.append("widget_id", widgetID);
            form.append("page", page);
            form.append("category", currentCategory);
            form.append("per_page", perPage);
            form.append("parent_cat", parentCat);
            form.append("excerpt_length", excerptLen);
            form.append("show_excerpt", showExcerpt);
            form.append("show_read_more", showReadMore);
            form.append("read_more_text", readMoreText);

            fetch(ajaxUrl, {
                method: "POST",
                body: form
            })
                .then(res => res.json())
                .then(data => {

                    if (!data || !data.success) return;

                    if (data.data.items) {
                        gridEl.innerHTML = data.data.items;
                    }

                    if (data.data.pagination) {
                        paginationEl.innerHTML = data.data.pagination;
                        bindPagination(); // <-- RE-BIND HERE
                    }
                })
                .catch(err => {
                    console.error("WR Blog Grid AJAX error:", err);
                })
                .finally(() => {
                    wrapper.classList.remove("wr-blog-grid-loading");
                });
        }

    }

});

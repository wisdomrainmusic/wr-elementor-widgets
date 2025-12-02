/* ---------------------------------------------------------
   WR Tab Product Grid – Tabs + AJAX Load More (FINAL VERSION)
---------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {

    if (typeof wrEwAjax === "undefined" || !wrEwAjax.ajax_url) {
        return;
    }

    const wrappers = document.querySelectorAll(".wr-tab-product-grid-wrapper");
    if (!wrappers.length) return;

    wrappers.forEach(function (wrapper) {

        const widgetId      = wrapper.dataset.widgetId;
        const parentCat     = wrapper.dataset.parentCat;
        const perPage       = parseInt(wrapper.dataset.count || "6", 10);
        const initialSubcat = wrapper.dataset.initialSubcat;
        const hasMoreInit   = wrapper.dataset.hasMore === "1";

        const tabsContainer  = wrapper.querySelector(".wr-tpg-tabs");
        const tabs           = wrapper.querySelectorAll(".wr-tpg-tab");
        const gridWrapper    = wrapper.querySelector(".wr-tpg-grid-wrapper");
        const gridEl         = wrapper.querySelector("#wr-tpg-grid-" + widgetId);
        const loadingOverlay = wrapper.querySelector(".wr-tpg-loading-overlay");
        const loadMoreWrap   = wrapper.querySelector(".wr-tpg-loadmore-wrapper");
        const loadMoreBtn    = wrapper.querySelector(".wr-tpg-loadmore-btn");

        if (!gridEl || !tabsContainer || !tabs.length || !loadMoreBtn) return;

        // STATE
        let currentSubcat = initialSubcat || (tabs[0] && tabs[0].dataset.subcat);
        let currentPage   = 1;
        let isLoading     = false;

        // Initial load more visibility
        loadMoreWrap.style.display = hasMoreInit ? "block" : "none";

        function setLoading(state) {
            isLoading = state;
            if (state) {
                gridWrapper.classList.add("is-loading");
                loadMoreBtn.disabled = true;
            } else {
                gridWrapper.classList.remove("is-loading");
                loadMoreBtn.disabled = false;
            }
        }

        function setActiveTab(subcat) {
            tabs.forEach(tab => {
                if (tab.dataset.subcat === subcat) {
                    tab.classList.add("is-active");
                } else {
                    tab.classList.remove("is-active");
                }
            });
        }

        /* ---------------------------------------------------------
           AJAX Fetch
           mode = "replace" | "append"
        ---------------------------------------------------------- */
        function fetchProducts(subcatSlug, page, mode) {

            if (isLoading) return;
            setLoading(true);

            const formData = new FormData();
            formData.append("action", "wr_tab_product_grid_load");
            formData.append("parent_cat", parentCat);
            formData.append("subcat_slug", subcatSlug);
            formData.append("product_count", perPage);
            formData.append("page", page);

            fetch(wrEwAjax.ajax_url, {
                method: "POST",
                body: formData,
                credentials: "same-origin"
            })
            .then(response => response.json())
            .then(json => {

                if (!json || !json.success || !json.data) return;

                const html    = json.data.html || "";
                const hasMore = !!json.data.has_more;

                /* ---------------------------------------------------------
                   APPEND MODE (Load More)
                ----------------------------------------------------------- */
                if (mode === "append") {

                    if (html.trim().length > 0) {

                        const temp = document.createElement("div");
                        temp.innerHTML = html;

                        // UL içindeki tüm LI ürünlerini al
                        let productLis = temp.querySelectorAll("ul.products li.product");

                        // Eğer UL yoksa fallback → direkt li.product
                        if (!productLis.length) {
                            productLis = temp.querySelectorAll("li.product");
                        }

                        const targetUl = gridEl.querySelector(".wr-tpg-products");

                        productLis.forEach(li => {
                            targetUl.appendChild(li);
                        });
                    }

                } 
                /* ---------------------------------------------------------
                   REPLACE MODE (Tab değişimi)
                ----------------------------------------------------------- */
                else {
                    gridEl.innerHTML = html;
                }

                // State update
                currentPage   = page;
                currentSubcat = subcatSlug;

                // Load More görünümü
                loadMoreWrap.style.display = hasMore ? "block" : "none";

            })
            .catch(err => {
                console.error("WR Tab Product Grid AJAX Error:", err);
            })
            .finally(() => {
                setLoading(false);
            });
        }

        /* ---------------------------------------------------------
           TAB CLICK
        ---------------------------------------------------------- */
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                const subcat = this.dataset.subcat;
                if (!subcat || subcat === currentSubcat) return;

                setActiveTab(subcat);
                currentPage = 1;
                fetchProducts(subcat, 1, "replace");
            });
        });

        /* ---------------------------------------------------------
           LOAD MORE CLICK
        ---------------------------------------------------------- */
        loadMoreBtn.addEventListener("click", function () {
            const nextPage = currentPage + 1;
            fetchProducts(currentSubcat, nextPage, "append");
        });

    });
});

/* --------------------------------------------------------------
   WR Product Tabs – AJAX Switcher
   Folder: product-tabs
-------------------------------------------------------------- */

(function () {
    "use strict";

    function initWrProductTabs(scope) {
        var root = scope || document;
        var wrappers = root.querySelectorAll(".wr-product-tabs-wrapper");

        if (!wrappers.length) {
            return;
        }

        wrappers.forEach(function (wrapper) {
            var navTabs = wrapper.querySelectorAll(".wr-pt-tab");
            var gridEl = wrapper.querySelector(".wr-pt-products-grid");
            var ajaxUrl = wrapper.getAttribute("data-ajax-url");

            if (!navTabs.length || !gridEl || !ajaxUrl) {
                return;
            }

            navTabs.forEach(function (tab) {
                tab.addEventListener("click", function () {
                    if (tab.classList.contains("is-active")) {
                        return;
                    }

                    // Active tab değiştirme
                    navTabs.forEach(function (t) {
                        t.classList.remove("is-active");
                    });
                    tab.classList.add("is-active");

                    var queryType = tab.getAttribute("data-query-type") || "new_arrivals";
                    var count = tab.getAttribute("data-count") || "6";
                    var cat = tab.getAttribute("data-cat") || "";
                    var tag = tab.getAttribute("data-tag") || "";

                    wrapper.classList.add("is-loading");

                    var formData = new FormData();
                    formData.append("action", "wr_product_tabs_load");
                    formData.append("query_type", queryType);
                    formData.append("product_count", count);
                    formData.append("category", cat);
                    formData.append("tag", tag);

                    fetch(ajaxUrl, {
                        method: "POST",
                        body: formData,
                        credentials: "same-origin"
                    })
                        .then(function (response) {
                            return response.text();
                        })
                        .then(function (html) {
                            gridEl.innerHTML = html;
                        })
                        .catch(function () {
                            gridEl.innerHTML =
                                '<p class="wr-pt-message">Unable to load products for this tab.</p>';
                        })
                        .finally(function () {
                            wrapper.classList.remove("is-loading");
                        });
                });
            });
        });
    }

    // Normal frontend
    document.addEventListener("DOMContentLoaded", function () {
        initWrProductTabs(document);
    });

    // Elementor frontend (editör içinde tekrar çalıştırmak için)
    if (window.jQuery && window.elementorFrontend) {
        jQuery(window).on("elementor/frontend/init", function () {
            if (elementorFrontend.hooks) {
                elementorFrontend.hooks.addAction(
                    "frontend/element_ready/wr-product-tabs.default",
                    function ($scope) {
                        if ($scope && $scope[0]) {
                            initWrProductTabs($scope[0]);
                        }
                    }
                );
            }
        });
    }
})();

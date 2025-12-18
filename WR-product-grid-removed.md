# WR Product Grid Removal Notes

The WR Product Grid feature and its related assets/hooks were fully removed to prepare for a future rewrite.

## Deleted paths
- `widgets/product-grid/`
- `assets/js/wr-grid.js`
- `assets/css/product-grid.css`
- `includes/ajax-product-grid.php`
- `includes/product-grid.php`
- `includes/tab-product-grid.php`
- `elementor/product-grid/`
- `templates/content-product.php`

## Removed hooks and registrations
- Elementor widget registration for `product-grid` was deleted from `loader.php`.
- AJAX endpoints `wr_ajax_load_products`, `wr_tab_product_grid_load`, and `wr_filter_products` were removed with their callback files/actions.
- Product-grid-specific asset handling (`wr-grid-js`, `wr-product-grid-css`/JS, `wrGridData` localization) was removed from `loader.php`.

## Notes
- Wishlist rendering now reuses the shared `wr_render_product_card()` helper instead of the old product-grid card include.
- Shared product card rendering (`includes/render-product-card.php`) remains for other widgets such as the product carousel.

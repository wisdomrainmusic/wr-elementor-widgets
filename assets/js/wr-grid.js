.wr-product-grid-wrapper{
  display:grid;
  grid-template-columns:260px 1fr;
  gap:40px;
  align-items:flex-start;
}

.wr-filter-sidebar{
  border-radius:12px;
  overflow:hidden;
}

.wr-filter-header{
  display:none;
  font-weight:600;
  cursor:pointer;
  user-select:none;
  padding:10px 0;
}

.wr-cat-list-wrap{
  max-height:560px;
  overflow:auto;
}

.wr-cat-list{ list-style:none; margin:0; padding:0; }
.wr-cat-item{ cursor:pointer; padding:6px 0; transition:.2s ease; }
.wr-cat-item:hover{ transform:translateX(4px); }
.wr-cat-item.is-active{ font-weight:600; border-left:3px solid #000; padding-left:10px; }

.wr-ajax-grid.wr-loading{ opacity:.55; pointer-events:none; transition:opacity .2s ease; }

.wr-product-items{
  display:grid;
  gap:30px;
}

.wr-product-grid-wrapper[data-columns="2"] .wr-product-items{ grid-template-columns:repeat(2,1fr); }
.wr-product-grid-wrapper[data-columns="3"] .wr-product-items{ grid-template-columns:repeat(3,1fr); }
.wr-product-grid-wrapper[data-columns="4"] .wr-product-items{ grid-template-columns:repeat(4,1fr); }

.wr-pagination{
  margin-top:40px;
  display:flex;
  justify-content:center;
  align-items:center;
  gap:8px;
  flex-wrap:wrap;
}

.wr-pagination .page-numbers{
  display:inline-block;
  padding:10px 16px;
  border-radius:8px;
  background:#f5f5f5;
  color:#333;
  font-weight:600;
  transition:.2s;
}

.wr-pagination .page-numbers.current{ background:#1a73e8; color:#fff; }
.wr-pagination a.page-numbers:hover{ background:#e0e0e0; }

/* Skeleton */
.wr-product-item.wr-skeleton{
  padding:16px;
  background:#f4f4f4;
  border-radius:12px;
  animation: wr-skeleton-pulse 1.2s ease-in-out infinite;
}
.wr-skel-image{ width:100%; padding-top:75%; border-radius:10px; background:#e3e3e3; margin-bottom:10px; }
.wr-skel-line{ height:10px; border-radius:999px; background:#e3e3e3; margin-bottom:6px; }
.wr-skel-line.short{ width:60%; }
@keyframes wr-skeleton-pulse{ 0%{opacity:1} 50%{opacity:.5} 100%{opacity:1} }

/* Responsive */
@media (max-width: 900px){
  .wr-product-grid-wrapper{ grid-template-columns:1fr; gap:24px; }
  .wr-filter-header{ display:block; }
  .wr-cat-list-wrap{ display:none; }
  .wr-filter-sidebar.is-open .wr-cat-list-wrap{ display:block; }

  /* ✅ Mobilde sola yaslanma / container taşma fix */
  .wr-ajax-grid, .wr-product-items{
    width:100%;
    max-width:100%;
  }
}

@media (max-width: 1024px){
  .wr-product-items{ grid-template-columns: repeat(2, 1fr) !important; gap: 24px !important; }
}

@media (max-width: 767px){
  .wr-product-items{ grid-template-columns: 1fr !important; gap: 28px !important; }
}

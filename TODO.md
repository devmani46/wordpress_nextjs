# TODO: Fix Slider Repeater for About Page

## Tasks
- [x] Modify wp-content/themes/NRNA/inc/enqueue-scripts.php to conditionally enqueue scripts based on page template (home-tabs.js for template-home.php, about-tabs.js for template-about.php)
- [x] Update wp-content/themes/NRNA/assets/admin/about-tabs.js to handle about_slider_items repeater: add one item per click, remove with reindexing, use wp.media for images, plain JS only
- [x] Test in WordPress admin: ensure adding/removing sliders works correctly without double addition

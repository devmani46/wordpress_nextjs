<?php
// Enqueue theme styles and scripts
function nrna_enqueue_assets() {
    wp_enqueue_style('nrna-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-faq', get_template_directory_uri() . '/assets/css/faq.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-notice', get_template_directory_uri() . '/assets/css/notice.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-activities', get_template_directory_uri() . '/assets/css/activities.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_script('nrna-main', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'nrna_enqueue_assets');

// Enqueue admin scripts for meta boxes
function nrna_enqueue_admin_assets($hook) {
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script('jquery');
    }
}
add_action('admin_enqueue_scripts', 'nrna_enqueue_admin_assets');
?>

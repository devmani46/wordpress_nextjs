<?php
// Enqueue theme styles and scripts
function nrna_enqueue_assets()
{
    wp_enqueue_style('nrna-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-faq', get_template_directory_uri() . '/assets/css/faq.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-notice', get_template_directory_uri() . '/assets/css/notice.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-activities', get_template_directory_uri() . '/assets/css/activities.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-projects', get_template_directory_uri() . '/assets/css/projects.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('nrna-contact', get_template_directory_uri() . '/assets/css/contact.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_script('nrna-main', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'nrna_enqueue_assets');

// Enqueue admin scripts for meta boxes
function nrna_enqueue_admin_assets($hook)
{
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script('jquery');
        wp_enqueue_media();

        global $post;
        if ($post && get_page_template_slug($post->ID) === 'template-home.php') {
            wp_enqueue_script('nrna-home-tabs', get_template_directory_uri() . '/assets/admin/home-tabs.js', [], false, true);
            wp_enqueue_style('nrna-home-tabs', get_template_directory_uri() . '/assets/admin/home-tabs.css', [], false);
        } elseif ($post && get_page_template_slug($post->ID) === 'template-who-we-are.php') {
            wp_enqueue_script('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.js', [], '1.0.1', true);
            wp_enqueue_style('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.css', [], false);
        } elseif ($post && get_page_template_slug($post->ID) === 'template-organizational-structure.php') {
            wp_enqueue_script('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.js', [], '1.0.1', true);
            wp_enqueue_style('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.css', [], false);
        } elseif ($post && $post->post_type === 'events') {
            wp_enqueue_script('nrna-events-tabs', get_template_directory_uri() . '/assets/admin/events-tabs.js', [], false, true);
            wp_enqueue_style('nrna-events-tabs', get_template_directory_uri() . '/assets/admin/events-tabs.css', [], false);
        } elseif ($post && $post->post_type === 'who-we-are') {
            wp_enqueue_script('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.js', [], '1.0.1', true);
            wp_enqueue_style('nrna-about-tabs', get_template_directory_uri() . '/assets/admin/about-tabs.css', [], false);
        } elseif ($post && get_page_template_slug($post->ID) === 'template-committees-taskforces-subcommittees.php') {
            wp_enqueue_script('nrna-committees-tabs', get_template_directory_uri() . '/assets/admin/committees-tabs.js', [], false, true);
            wp_enqueue_style('nrna-home-tabs', get_template_directory_uri() . '/assets/admin/home-tabs.css', [], false);
        } elseif ($post && get_page_template_slug($post->ID) === 'template-contact.php') {
            wp_enqueue_script('nrna-contact-tabs', get_template_directory_uri() . '/assets/admin/contact-tabs.js', [], false, true);
            wp_enqueue_style('nrna-contact-tabs', get_template_directory_uri() . '/assets/admin/contact-tabs.css', [], false);
        } elseif ($post && get_page_template_slug($post->ID) === 'template-nrna-discount.php') {
            wp_enqueue_script('nrna-discount-tabs', get_template_directory_uri() . '/assets/admin/discount-tabs.js', [], false, true);
            wp_enqueue_style('nrna-discount-tabs', get_template_directory_uri() . '/assets/admin/discount-tabs.css', [], false);
        } elseif ($post && $post->post_type === 'regional_meetings') {
            wp_enqueue_script('nrna-regional-meetings-tabs', get_template_directory_uri() . '/assets/admin/regional-meetings-tabs.js', [], false, true);
            wp_enqueue_style('nrna-regional-meetings-tabs', get_template_directory_uri() . '/assets/admin/regional-meetings-tabs.css', [], false);
        } elseif ($post && $post->post_type === 'projects') {
            wp_enqueue_script('nrna-projects-tabs', get_template_directory_uri() . '/assets/admin/projects-tabs.js', [], false, true);
            wp_enqueue_style('nrna-projects-tabs', get_template_directory_uri() . '/assets/admin/projects-tabs.css', [], false);
        }
    }
}
add_action('admin_enqueue_scripts', 'nrna_enqueue_admin_assets');

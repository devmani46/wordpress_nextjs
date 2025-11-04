<?php
// Register Projects custom post type
function nrna_register_projects_cpt() {
    $labels = [
        'name'               => _x('Projects', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Project', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Projects', 'nrna'),
        'name_admin_bar'     => __('Project', 'nrna'),
        'add_new_item'       => __('Add New Project', 'nrna'),
        'edit_item'          => __('Edit Project', 'nrna'),
        'view_item'          => __('View Project', 'nrna'),
        'all_items'          => __('All Projects', 'nrna'),
        'search_items'       => __('Search Projects', 'nrna'),
        'not_found'          => __('No projects found.', 'nrna'),
        'not_found_in_trash' => __('No projects found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => false,
        'supports'           => ['title', 'editor', 'thumbnail'],
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 23,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'projects'],
    ];

    register_post_type('projects', $args);
}
add_action('init', 'nrna_register_projects_cpt');

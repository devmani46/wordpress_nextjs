<?php
// Register Resources custom post type
function nrna_register_resources_cpt() {
    $labels = [
        'name'               => _x('Resources', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Resource', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Resources', 'nrna'),
        'name_admin_bar'     => __('Resource', 'nrna'),
        'add_new_item'       => __('Add New Resource', 'nrna'),
        'edit_item'          => __('Edit Resource', 'nrna'),
        'view_item'          => __('View Resource', 'nrna'),
        'all_items'          => __('All Resources', 'nrna'),
        'search_items'       => __('Search Resources', 'nrna'),
        'not_found'          => __('No resources found.', 'nrna'),
        'not_found_in_trash' => __('No resources found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-download',
        'menu_position'      => 24,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'resources'],
        'taxonomies'         => ['resource_category'],
    ];

    register_post_type('resources', $args);
}
add_action('init', 'nrna_register_resources_cpt');

// Register Resource Category taxonomy
function nrna_register_resource_category_taxonomy() {
    $labels = [
        'name'              => _x('Resource Categories', 'taxonomy general name', 'nrna'),
        'singular_name'     => _x('Resource Category', 'taxonomy singular name', 'nrna'),
        'search_items'      => __('Search Categories', 'nrna'),
        'all_items'         => __('All Categories', 'nrna'),
        'parent_item'       => __('Parent Category', 'nrna'),
        'parent_item_colon' => __('Parent Category:', 'nrna'),
        'edit_item'         => __('Edit Category', 'nrna'),
        'update_item'       => __('Update Category', 'nrna'),
        'add_new_item'      => __('Add New Category', 'nrna'),
        'new_item_name'     => __('New Category Name', 'nrna'),
        'menu_name'         => __('Categories', 'nrna'),
    ];

    $args = [
        'hierarchical'          => true,
        'labels'                => $labels,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => ['slug' => 'resource-category'],
        'show_in_rest'          => true,
        'show_in_quick_edit'    => true,
        'show_in_nav_menus'     => true,
    ];

    register_taxonomy('resource_category', ['resources'], $args);
}
add_action('init', 'nrna_register_resource_category_taxonomy');

// Ensure the taxonomy meta box appears in Resources edit screen
function nrna_add_resource_category_metabox_back() {
    add_meta_box(
        'resource_categorydiv',
        __('Resource Categories', 'nrna'),
        'post_categories_meta_box',
        'resources',
        'side',
        'default',
        ['taxonomy' => 'resource_category']
    );
}
add_action('add_meta_boxes_resources', 'nrna_add_resource_category_metabox_back');

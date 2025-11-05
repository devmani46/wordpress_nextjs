<?php
// Register Downloads custom post type
function nrna_register_downloads_cpt() {
    $labels = [
        'name'               => _x('Downloads', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Download', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Downloads', 'nrna'),
        'name_admin_bar'     => __('Download', 'nrna'),
        'add_new_item'       => __('Add New Download', 'nrna'),
        'edit_item'          => __('Edit Download', 'nrna'),
        'view_item'          => __('View Download', 'nrna'),
        'all_items'          => __('All Downloads', 'nrna'),
        'search_items'       => __('Search Downloads', 'nrna'),
        'not_found'          => __('No downloads found.', 'nrna'),
        'not_found_in_trash' => __('No downloads found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-download',
        'menu_position'      => 24,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'downloads'],
        'taxonomies'         => ['download_category'],
    ];

    register_post_type('downloads', $args);
}
add_action('init', 'nrna_register_downloads_cpt');

// Register Download Category taxonomy
function nrna_register_download_category_taxonomy() {
    $labels = [
        'name'              => _x('Download Categories', 'taxonomy general name', 'nrna'),
        'singular_name'     => _x('Download Category', 'taxonomy singular name', 'nrna'),
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
        'show_ui'               => true,  
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => ['slug' => 'download-category'],
        'show_in_rest'          => true,  
        'show_in_quick_edit'    => true,
    ];

    register_taxonomy('download_category', ['downloads'], $args);
}
add_action('init', 'nrna_register_download_category_taxonomy');

// Ensure the taxonomy meta box appears in Downloads edit screen
function nrna_add_download_category_metabox_back() {
    add_meta_box(
        'download_categorydiv',
        __('Download Categories', 'nrna'),
        'post_categories_meta_box',
        'downloads',
        'side',
        'default',
        ['taxonomy' => 'download_category']
    );
}
add_action('add_meta_boxes_downloads', 'nrna_add_download_category_metabox_back');

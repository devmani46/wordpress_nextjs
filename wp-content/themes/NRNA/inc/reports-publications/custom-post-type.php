<?php
// Register Reports & Publications custom post type
function nrna_register_reports_publications_cpt() {
    $labels = [
        'name'               => _x('Reports & Publications', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Report & Publication', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Reports & Publications', 'nrna'),
        'name_admin_bar'     => __('Report & Publication', 'nrna'),
        'add_new_item'       => __('Add New Report & Publication', 'nrna'),
        'edit_item'          => __('Edit Report & Publication', 'nrna'),
        'view_item'          => __('View Report & Publication', 'nrna'),
        'all_items'          => __('All Reports & Publications', 'nrna'),
        'search_items'       => __('Search Reports & Publications', 'nrna'),
        'not_found'          => __('No reports & publications found.', 'nrna'),
        'not_found_in_trash' => __('No reports & publications found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'rest_base'          => 'reports-publications',
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-media-document',
        'menu_position'      => 25,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'reports-publications'],
        'taxonomies'         => ['reports_publications_category'],
    ];

    register_post_type('reports_publications', $args);
}
add_action('init', 'nrna_register_reports_publications_cpt');

// Register Reports & Publications Category taxonomy
function nrna_register_reports_publications_category_taxonomy() {
    $labels = [
        'name'              => _x('Reports & Publications Categories', 'taxonomy general name', 'nrna'),
        'singular_name'     => _x('Reports & Publications Category', 'taxonomy singular name', 'nrna'),
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
        'rewrite'               => ['slug' => 'reports-publications-category'],
        'show_in_rest'          => true,
        'show_in_quick_edit'    => true,
        'show_in_nav_menus'     => true,
    ];

    register_taxonomy('reports_publications_category', ['reports_publications'], $args);
}
add_action('init', 'nrna_register_reports_publications_category_taxonomy');

// Ensure the taxonomy meta box appears in Reports & Publications edit screen
function nrna_add_reports_publications_category_metabox_back() {
    add_meta_box(
        'reports_publications_categorydiv',
        __('Reports & Publications Categories', 'nrna'),
        'post_categories_meta_box',
        'reports_publications',
        'side',
        'default',
        ['taxonomy' => 'reports_publications_category']
    );
}
add_action('add_meta_boxes_reports_publications', 'nrna_add_reports_publications_category_metabox_back');

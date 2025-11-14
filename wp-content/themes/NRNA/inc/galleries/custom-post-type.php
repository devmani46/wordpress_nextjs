<?php
// Register Galleries custom post type
function nrna_register_galleries_cpt() {
    $labels = [
        'name'               => _x('Galleries', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Gallery', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Galleries', 'nrna'),
        'name_admin_bar'     => __('Gallery', 'nrna'),
        'add_new_item'       => __('Add New Gallery', 'nrna'),
        'edit_item'          => __('Edit Gallery', 'nrna'),
        'view_item'          => __('View Gallery', 'nrna'),
        'all_items'          => __('All Galleries', 'nrna'),
        'search_items'       => __('Search Galleries', 'nrna'),
        'not_found'          => __('No galleries found.', 'nrna'),
        'not_found_in_trash' => __('No galleries found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-images-alt2',
        'menu_position'      => 25,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'galleries'],
    ];

    register_post_type('galleries', $args);
}
add_action('init', 'nrna_register_galleries_cpt');

// Register meta fields for REST API
function nrna_register_galleries_meta_rest() {
    register_meta('post', 'gallery_images', array(
        'object_subtype' => 'galleries',
        'type' => 'array',
        'description' => 'Gallery Images',
        'single' => true,
        'show_in_rest' => array(
            'schema' => array(
                'type' => 'array',
                'items' => array(
                    'type' => 'integer',
                ),
            ),
        ),
    ));
}
add_action('init', 'nrna_register_galleries_meta_rest');

// Prepare REST API response to include meta fields
function nrna_prepare_galleries_rest($response, $post, $request) {
    $response->data['gallery_images'] = get_post_meta($post->ID, 'gallery_images', true);
    return $response;
}
add_filter('rest_prepare_galleries', 'nrna_prepare_galleries_rest', 10, 3);

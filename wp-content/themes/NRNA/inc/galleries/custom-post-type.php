<?php
// Register Galleries custom post type
function nrna_register_galleries_cpt() {
    $labels = [
        'name'               => _x('Images', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Image', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Images', 'nrna'),
        'name_admin_bar'     => __('Image', 'nrna'),
        'add_new_item'       => __('Add New Image', 'nrna'),
        'edit_item'          => __('Edit Image', 'nrna'),
        'view_item'          => __('View Image', 'nrna'),
        'all_items'          => __('All Images', 'nrna'),
        'search_items'       => __('Search Images', 'nrna'),
        'not_found'          => __('No images found.', 'nrna'),
        'not_found_in_trash' => __('No images found in Trash.', 'nrna'),
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
        'show_in_menu'       => 'gallery-menu',
    ];

    register_post_type('galleries', $args);
}
add_action('init', 'nrna_register_galleries_cpt');

// Register meta fields for REST API
function nrna_register_galleries_meta_rest() {
    register_meta('post', 'images', array(
        'object_subtype' => 'galleries',
        'type' => 'array',
        'description' => 'Images',
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
    $image_ids = get_post_meta($post->ID, 'images', true);
    $images = [];
    if (!empty($image_ids) && is_array($image_ids)) {
        foreach ($image_ids as $image_id) {
            $image_data = wp_get_attachment_image_src($image_id, 'full');
            if ($image_data) {
                $images[] = [
                    'id' => $image_id,
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                    'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                ];
            }
        }
    }
    $response->data['images'] = $images;
    return $response;
}
add_filter('rest_prepare_galleries', 'nrna_prepare_galleries_rest', 10, 3);

<?php
// Register Notice custom post type
function nrna_register_notice_cpt() {
    $labels = [
        'name'               => _x('Notices', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Notice', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Notices', 'nrna'),
        'name_admin_bar'     => __('Notice', 'nrna'),
        'add_new_item'       => __('Add New Notice', 'nrna'),
        'edit_item'          => __('View Notice', 'nrna'),
        'all_items'          => __('All Notices', 'nrna'),
        'search_items'       => __('Search Notices', 'nrna'),
        'not_found'          => __('No notices found.', 'nrna'),
        'not_found_in_trash' => __('No notices found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-megaphone',
        'menu_position'      => 21,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'notices'],
    ];

    register_post_type('notices', $args);
}
add_action('init', 'nrna_register_notice_cpt');

// Register meta fields for REST API
function nrna_register_notices_meta_rest() {
    register_meta('post', 'notice_content', array(
        'object_subtype' => 'notices',
        'type' => 'string',
        'description' => 'Notice Content',
        'single' => true,
        'show_in_rest' => true,
    ));

    register_meta('post', 'notice_related', array(
        'object_subtype' => 'notices',
        'type' => 'array',
        'description' => 'Related Notices',
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
add_action('init', 'nrna_register_notices_meta_rest');

// Prepare REST API response to include meta fields
function nrna_prepare_notices_rest($response, $post, $request) {
    $response->data['notice_content'] = get_post_meta($post->ID, 'notice_content', true);
    $response->data['notice_related'] = get_post_meta($post->ID, 'notice_related', false);
    return $response;
}
add_filter('rest_prepare_notices', 'nrna_prepare_notices_rest', 10, 3);

<?php
// Register News custom post type
function nrna_register_news_cpt() {
    $labels = [
        'name'               => _x('News', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('News', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('News', 'nrna'),
        'name_admin_bar'     => __('News', 'nrna'),
        'add_new_item'       => __('Add New News', 'nrna'),
        'edit_item'          => __('Edit News', 'nrna'),
        'view_item'          => __('View News', 'nrna'),
        'all_items'          => __('All News', 'nrna'),
        'search_items'       => __('Search News', 'nrna'),
        'not_found'          => __('No news found.', 'nrna'),
        'not_found_in_trash' => __('No news found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-admin-site',
        'menu_position'      => 22,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'news'],
    ];

    register_post_type('news', $args);
}
add_action('init', 'nrna_register_news_cpt');

// Register meta fields for REST API
function nrna_register_news_meta_rest() {
    register_meta('post', 'news_content', array(
        'object_subtype' => 'news',
        'type' => 'string',
        'description' => 'News Content',
        'single' => true,
        'show_in_rest' => true,
    ));

    register_meta('post', 'news_related', array(
        'object_subtype' => 'news',
        'type' => 'array',
        'description' => 'Related News',
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
add_action('init', 'nrna_register_news_meta_rest');

// Prepare REST API response to include meta fields
function nrna_prepare_news_rest($response, $post, $request) {
    $response->data['news_content'] = get_post_meta($post->ID, 'news_content', true);
    $response->data['news_related'] = get_post_meta($post->ID, 'news_related', false);
    return $response;
}
add_filter('rest_prepare_news', 'nrna_prepare_news_rest', 10, 3);

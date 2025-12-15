<?php

function nrna_register_search_api()
{
    register_rest_route('nrna/v1', '/search', [
        'methods' => 'GET',
        'callback' => 'nrna_handle_search',
        'permission_callback' => '__return_true',
    ]);
}

add_action('rest_api_init', 'nrna_register_search_api');

function nrna_handle_search($request)
{
    $keyword = sanitize_text_field($request->get_param('term'));
    $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
    $per_page = 8;

    if (empty($keyword)) {
        $response = new WP_REST_Response([], 200);
        $response->header('X-WP-Total', 0);
        $response->header('X-WP-TotalPages', 0);
        return $response;
    }

    $args = [
        's' => $keyword,
        'post_type' => 'any', // Search ALL post types
        'post_status' => 'publish',
        'paged' => $page,
        'posts_per_page' => $per_page,
    ];


    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_type_obj = get_post_type_object(get_post_type());
            $type_label = $post_type_obj ? $post_type_obj->labels->singular_name : get_post_type();

            // Custom labels for better UI
            if (get_post_type() == 'reports-publications') {
                $type_label = 'Report';
            } elseif (get_post_type() == 'regional_meeting') {
                $type_label = 'Regional Meeting';
            }

            // Get excerpt, fallback to trimmed content if no excerpt
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 30, '...');
            }

            $results[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => $excerpt,
                'url' => get_permalink(),
                'type' => $type_label,
                'date' => get_the_date(),
            ];
        }
        wp_reset_postdata();
    }

    $response = new WP_REST_Response($results, 200);
    $response->header('X-WP-Total', $query->found_posts);
    $response->header('X-WP-TotalPages', $query->max_num_pages);

    return $response;
}

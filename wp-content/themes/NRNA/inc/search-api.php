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
        'post_type' => 'any',
        'post_status' => 'publish',
        'paged' => $page,
        'posts_per_page' => $per_page,
    ];


    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_type = get_post_type();
            $post_type_obj = get_post_type_object($post_type);
            $type_label = $post_type_obj ? $post_type_obj->labels->singular_name : $post_type;

            if ($post_type == 'events') {
                $type_label = 'Event';
            } elseif ($post_type == 'notices') {
                $type_label = 'Notice';
            } elseif ($post_type == 'news') {
                $type_label = 'News';
            } elseif ($post_type == 'activities') {
                $type_label = 'Activity';
            } elseif ($post_type == 'faqs') {
                $type_label = 'FAQ';
            } elseif ($post_type == 'resources') {
                $type_label = 'Resource';
            } elseif ($post_type == 'projects') {
                $type_label = 'Project';
            } elseif ($post_type == 'video') {
                $type_label = 'Video';
            } elseif ($post_type == 'gallery') {
                $type_label = 'Photo Album';
            } elseif ($post_type == 'regional_meetings') {
                $type_label = 'Regional Meeting';
            } elseif ($post_type == 'executive_committee') {
                $type_label = 'Executive Committee';
            } elseif ($post_type == 'our_ncc') {
                $type_label = 'Our NCC';
            } elseif ($post_type == 'reports-publications') {
                $type_label = 'Report';
            } elseif ($post_type == 'page') {
                $type_label = 'Page';
            }


            $category_slug = '';
            if ($post_type == 'reports-publications' || $post_type == 'reports_publications') {
                $categories = get_the_terms(get_the_ID(), 'reports_publications_category');
                if ($categories && !is_wp_error($categories)) {
                    $category_slug = $categories[0]->slug;
                }
            }

            $frontend_url = nrna_convert_to_frontend_url(get_permalink(), $post_type, $category_slug);

            $excerpt = nrna_get_search_excerpt($post_type);

            $results[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => $excerpt,
                'url' => $frontend_url,
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

function nrna_convert_to_frontend_url($wp_url, $post_type, $category_slug = '')
{
    $wp_base = get_site_url();

    switch ($post_type) {
        case 'resources':
            return '/downloads';

        case 'faqs':
            return '/faqs';

        case 'event':
            return '/events';

        case 'project':
            return '/projects';

        case 'notice':
            return '/notices';

        case 'news':
            return '/news';

        case 'activity':
            return '/activities';

        case 'regional_meetings':
            $path = str_replace($wp_base, '', $wp_url);
            $path = rtrim($path, '/');
            $path = str_replace('/regional-meetings/', '/regional_meetings/', $path);
            return $path;

        case 'video':
            return '/videos';

        case 'gallery':
            $path = str_replace($wp_base, '', $wp_url);
            $path = rtrim($path, '/');
            $path = str_replace('/galleries/', '/photo-album/', $path);
            return $path;

        case 'reports-publications':
        case 'reports_publications':
            if (!empty($category_slug)) {
                return '/reports-publications-category/' . $category_slug;
            }
            return '/reports-publications';

        case 'executive_committee':
            return '/executivecommittee';

        case 'our_ncc':
            return '/ourncc';

        case 'page':
            $path = str_replace($wp_base, '', $wp_url);
            return rtrim($path, '/');

        default:
            $path = str_replace($wp_base, '', $wp_url);
            $path = rtrim($path, '/');
            $path = str_replace('/galleries/', '/photo-album/', $path);
            $path = str_replace('/regionalmeetings/', '/regional_meetings/', $path);
            return $path;
    }
}

function nrna_get_search_excerpt($post_type)
{
    $post_id = get_the_ID();

    $excerpt = get_the_excerpt();
    if (!empty($excerpt)) {
        return wp_trim_words(strip_tags($excerpt), 25, '...');
    }

    $content = get_the_content();
    if (!empty($content)) {
        $clean_content = strip_tags($content);
        $clean_content = preg_replace('/\s+/', ' ', $clean_content);
        if (!empty(trim($clean_content))) {
            return wp_trim_words($clean_content, 25, '...');
        }
    }

    $meta_excerpt = '';

    switch ($post_type) {
        case 'event':
            $meta_excerpt = get_post_meta($post_id, 'event_description', true);
            if (empty($meta_excerpt)) {
                $meta_excerpt = get_post_meta($post_id, 'event_overview_description', true);
            }
            break;

        case 'project':
            $meta_excerpt = get_post_meta($post_id, 'project_description', true);
            break;

        case 'notice':
            $meta_excerpt = get_post_meta($post_id, 'notice_content', true);
            break;

        case 'news':
            $meta_excerpt = get_post_meta($post_id, 'news_content', true);
            break;

        case 'activity':
            $meta_excerpt = get_post_meta($post_id, 'activity_content', true);
            break;

        case 'regional_meetings':
            $meta_excerpt = get_post_meta($post_id, 'rm_description', true);
            break;

        case 'faqs':
            $meta_excerpt = get_post_meta($post_id, 'answer', true);
            break;

        case 'resources':
            $meta_excerpt = get_post_meta($post_id, 'resource_description', true);
            break;

        case 'video':
            $meta_excerpt = get_post_meta($post_id, 'video_description', true);
            break;

        case 'gallery':
            $meta_excerpt = get_post_meta($post_id, 'gallery_description', true);
            break;

        case 'executive_committee':
            $meta_excerpt = get_post_meta($post_id, 'ec_bio', true);
            if (empty($meta_excerpt)) {
                $meta_excerpt = get_post_meta($post_id, 'ec_position', true);
            }
            break;

        case 'our_ncc':
            $meta_excerpt = get_post_meta($post_id, 'ncc_description', true);
            if (empty($meta_excerpt)) {
                $meta_excerpt = get_post_meta($post_id, 'ncc_address', true);
            }
            break;

        case 'reports-publications':
            $meta_excerpt = get_post_meta($post_id, 'report_description', true);
            break;

        default:
            $meta_excerpt = get_post_meta($post_id, 'description', true);
            break;
    }


    if (!empty($meta_excerpt)) {
        $clean_meta = strip_tags($meta_excerpt);
        $clean_meta = preg_replace('/\s+/', ' ', $clean_meta);
        if (!empty(trim($clean_meta))) {
            return wp_trim_words($clean_meta, 25, '...');
        }
    }

    // If still no descriotion, generate from title and date
    $title = get_the_title();
    $date = get_the_date('F j, Y');

    return "View details about {$title}. Published on {$date}.";
}

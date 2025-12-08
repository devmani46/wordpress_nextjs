<?php
// Register Projects custom post type
function nrna_register_projects_cpt()
{
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
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 23,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'projects'],
    ];

    register_post_type('projects', $args);
}
add_action('init', 'nrna_register_projects_cpt');

// Register meta fields for Projects
function nrna_register_projects_meta_fields()
{
    $fields = [
        'project_hero_title' => ['type' => 'string'],
        'project_date' => ['type' => 'string'],
        'project_sub_title' => ['type' => 'string'],
        'project_cta_link_1' => ['type' => 'string'],
        'project_cta_title_1' => ['type' => 'string'],
        'project_cta_link_2' => ['type' => 'string'],
        'project_cta_title_2' => ['type' => 'string'],
        'project_description' => ['type' => 'string'],
        'project_objective_title' => ['type' => 'string'],
        'project_objective_description' => ['type' => 'string'],
        'project_banner_title' => ['type' => 'string'],
        'project_banner_description' => ['type' => 'string'],
        'project_banner_cta_link' => ['type' => 'string'],
        'project_banner_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'projects',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_meta('post', 'project_locations', [
        'object_subtype' => 'projects',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'place' => ['type' => 'string'],
                'date' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'cta_link' => ['type' => 'string'],
                'cta_title' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_meta('post', 'project_image_gallery', [
        'object_subtype' => 'projects',
        'type' => 'array',
        'items' => [
            'type' => 'integer', // Attachment IDs
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_projects_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_projects_rest($response, $post, $request)
{
    if ($post->post_type !== 'projects') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'project_hero_title',
        'project_date',
        'project_sub_title',
        'project_cta_link_1',
        'project_cta_title_1',
        'project_cta_link_2',
        'project_cta_title_2',
        'project_description',
        'project_objective_title',
        'project_objective_description',
        'project_banner_title',
        'project_banner_description',
        'project_banner_cta_link',
        'project_banner_cta_title',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['project_locations'] = get_post_meta($post->ID, 'project_locations', true);
    $data['project_image_gallery'] = get_post_meta($post->ID, 'project_image_gallery', true);

    // Project Downloads
    $downloads = get_post_meta($post->ID, 'project_downloads', true);
    if (!is_array($downloads)) {
        $downloads = [];
    }
    $data['project_downloads'] = [];
    foreach ($downloads as $d) {
        $item = ['title' => $d['title'] ?? ''];
        if (!empty($d['file'])) {
            $item['file'] = [
                'id' => $d['file'],
                'url' => wp_get_attachment_url($d['file']),
                'filename' => basename(wp_get_attachment_url($d['file']))
            ];
        }
        $data['project_downloads'][] = $item;
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_projects', 'nrna_prepare_projects_rest', 10, 3);

// Register project_downloads REST field schema (optional if using register_meta but good for explict structure)
function nrna_register_project_downloads_rest_field()
{
    register_rest_field('projects', 'project_downloads', array(
        'get_callback' => function ($post) {
            // Already handled in prepare, but this ensures field exists if requested directly
            return null; // Logic in prepare_projects_rest handles the main response
        },
        'schema' => array(
            'description' => 'Project downloads',
            'type' => 'array',
            'items' => array(
                'type' => 'object',
                'properties' => array(
                    'title' => array('type' => 'string'),
                    'file' => array(
                        'type' => 'object',
                        'properties' => array(
                            'id' => array('type' => 'integer'),
                            'url' => array('type' => 'string'),
                            'filename' => array('type' => 'string'),
                        ),
                    ),
                ),
            ),
        ),
    ));
}
add_action('rest_api_init', 'nrna_register_project_downloads_rest_field');

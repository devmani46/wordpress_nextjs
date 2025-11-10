<?php
// Register Projects meta fields for REST API
function nrna_register_projects_meta_fields() {
    $fields = [
        'project_subtitle' => ['type' => 'string'],
        'project_description' => ['type' => 'string'],
        'project_objective' => ['type' => 'string'],
        'project_locations' => [
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'properties' => [
                    'place' => ['type' => 'string'],
                    'date' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                ],
            ],
        ],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('projects', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }
}
add_action('init', 'nrna_register_projects_meta_fields');

// Add Projects meta boxes
function nrna_add_projects_meta_boxes() {
    add_meta_box(
        'project_subtitle_box',
        __('Project Subtitle', 'nrna'),
        'nrna_render_project_subtitle_meta_box',
        'projects',
        'normal',
        'high'
    );
    add_meta_box(
        'project_description_box',
        __('Project Description', 'nrna'),
        'nrna_render_project_description_meta_box',
        'projects',
        'normal',
        'high'
    );
    add_meta_box(
        'project_objective_box',
        __('Project Objective', 'nrna'),
        'nrna_render_project_objective_meta_box',
        'projects',
        'normal',
        'high'
    );
    add_meta_box(
        'project_locations_box',
        __('Project Locations', 'nrna'),
        'nrna_render_project_locations_meta_box',
        'projects',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_projects_meta_boxes');

// Render Subtitle meta box
function nrna_render_project_subtitle_meta_box($post) {
    $subtitle = get_post_meta($post->ID, 'project_subtitle', true);
    echo '<label for="project_subtitle" style="display:block; font-weight:bold; margin-bottom:8px;">Subtitle:</label>';
    echo '<input type="text" id="project_subtitle" name="project_subtitle" value="' . esc_attr($subtitle) . '" style="width:100%;" />';
}

// Render Description meta box
function nrna_render_project_description_meta_box($post) {
    $description = get_post_meta($post->ID, 'project_description', true);
    echo '<label for="project_description" style="display:block; font-weight:bold; margin-bottom:8px;">Description:</label>';
    wp_editor($description, 'project_description', [
        'textarea_name' => 'project_description',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Render Objective meta box
function nrna_render_project_objective_meta_box($post) {
    $objective = get_post_meta($post->ID, 'project_objective', true);
    echo '<label for="project_objective" style="display:block; font-weight:bold; margin-bottom:8px;">Objective:</label>';
    wp_editor($objective, 'project_objective', [
        'textarea_name' => 'project_objective',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Render Locations meta box (repeatable)
function nrna_render_project_locations_meta_box($post) {
    $locations = get_post_meta($post->ID, 'project_locations', true);
    if (!is_array($locations)) {
        $locations = [];
    }

    echo '<div id="project-locations-container">';
    echo '<p class="description">Add locations where the project is being conducted.</p>';

    if (!empty($locations)) {
        foreach ($locations as $index => $location) {
            echo '<div class="location-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">';
            echo '<label>Place:</label><br>';
            echo '<input type="text" name="project_locations[' . $index . '][place]" value="' . esc_attr($location['place'] ?? '') . '" style="width:100%; margin-bottom:5px;" /><br>';
            echo '<label>Date:</label><br>';
            echo '<input type="text" name="project_locations[' . $index . '][date]" value="' . esc_attr($location['date'] ?? '') . '" style="width:100%; margin-bottom:5px;" placeholder="e.g., 2025" /><br>';
            echo '<label>Description:</label><br>';
            echo '<textarea name="project_locations[' . $index . '][description]" rows="3" style="width:100%;">' . esc_textarea($location['description'] ?? '') . '</textarea>';
            echo '<button type="button" class="remove-location-button button" style="margin-top: 5px;">Remove Location</button>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<button type="button" id="add-location-button" class="button">Add Location</button>';

    ?>
    <script>
    jQuery(document).ready(function($) {
        var locationIndex = <?php echo count($locations); ?>;

        $('#add-location-button').on('click', function() {
            var html = '<div class="location-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">' +
                '<label>Place:</label><br>' +
                '<input type="text" name="project_locations[' + locationIndex + '][place]" style="width:100%; margin-bottom:5px;" /><br>' +
                '<label>Date:</label><br>' +
                '<input type="text" name="project_locations[' + locationIndex + '][date]" style="width:100%; margin-bottom:5px;" placeholder="e.g., 2025" /><br>' +
                '<label>Description:</label><br>' +
                '<textarea name="project_locations[' + locationIndex + '][description]" rows="3" style="width:100%;"></textarea>' +
                '<button type="button" class="remove-location-button button" style="margin-top: 5px;">Remove Location</button>' +
                '</div>';
            $('#project-locations-container').append(html);
            locationIndex++;
        });

        $(document).on('click', '.remove-location-button', function() {
            $(this).closest('.location-item').remove();
        });
    });
    </script>
    <?php
}

// Save Projects meta
function nrna_save_projects_meta_boxes($post_id) {
    if (array_key_exists('project_subtitle', $_POST)) {
        update_post_meta($post_id, 'project_subtitle', sanitize_text_field($_POST['project_subtitle']));
    }
    if (array_key_exists('project_description', $_POST)) {
        update_post_meta($post_id, 'project_description', wp_kses_post($_POST['project_description']));
    }
    if (array_key_exists('project_objective', $_POST)) {
        update_post_meta($post_id, 'project_objective', wp_kses_post($_POST['project_objective']));
    }
    if (array_key_exists('project_locations', $_POST)) {
        $locations = [];
        foreach ($_POST['project_locations'] as $location) {
            if (!empty($location['place']) || !empty($location['date']) || !empty($location['description'])) {
                $locations[] = [
                    'place' => sanitize_text_field($location['place']),
                    'date' => sanitize_text_field($location['date']),
                    'description' => sanitize_textarea_field($location['description']),
                ];
            }
        }
        update_post_meta($post_id, 'project_locations', $locations);
    } else {
        delete_post_meta($post_id, 'project_locations');
    }
}
add_action('save_post', 'nrna_save_projects_meta_boxes');

// Clean up Projects admin screen
function nrna_remove_projects_meta_boxes() {
    remove_meta_box('slugdiv', 'projects', 'normal');
    remove_meta_box('authordiv', 'projects', 'normal');
    remove_meta_box('commentsdiv', 'projects', 'normal');
    remove_meta_box('revisionsdiv', 'projects', 'normal');
}
add_action('admin_menu', 'nrna_remove_projects_meta_boxes');

// Prepare Projects REST response to include all meta fields
function nrna_prepare_projects_rest_response($response, $post, $request) {
    $data = $response->get_data();

    // Add meta fields
    $data['project_subtitle'] = get_post_meta($post->ID, 'project_subtitle', true);
    $data['project_description'] = get_post_meta($post->ID, 'project_description', true);
    $data['project_objective'] = get_post_meta($post->ID, 'project_objective', true);
    $data['project_locations'] = get_post_meta($post->ID, 'project_locations', true);

    // Add featured image URL if exists
    if (has_post_thumbnail($post->ID)) {
        $data['featured_image_url'] = get_the_post_thumbnail_url($post->ID, 'full');
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_projects', 'nrna_prepare_projects_rest_response', 10, 3);

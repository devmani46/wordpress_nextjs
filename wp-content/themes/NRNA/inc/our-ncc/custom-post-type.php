<?php
// Register NCC Committees custom post type
function nrna_register_ncc_committees_cpt() {
    $labels = [
        'name'               => _x('Our NCC', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Our NCC', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Our NCC', 'nrna'),
        'name_admin_bar'     => __('Our NCC', 'nrna'),
        'add_new_item'       => __('Add New Our NCC', 'nrna'),
        'edit_item'          => __('Edit Our NCC', 'nrna'),
        'view_item'          => __('View Our NCC', 'nrna'),
        'all_items'          => __('All Our NCC', 'nrna'),
        'search_items'       => __('Search Our NCC', 'nrna'),
        'not_found'          => __('No Our NCC found.', 'nrna'),
        'not_found_in_trash' => __('No Our NCC found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['thumbnail'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 24,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'our-ncc'],
        'show_in_menu'       => true,
        'show_in_nav_menus'  => false,

    ];

    register_post_type('our_ncc', $args);
}
add_action('init', 'nrna_register_ncc_committees_cpt');

// Register meta fields for NCC Committees
function nrna_register_ncc_committees_meta_fields() {
    $fields = [
        'ncc_year_of_tenure' => ['type' => 'string'],
        'ncc_region' => ['type' => 'string'],
        'ncc_country_name' => ['type' => 'string'],
        'ncc_name' => ['type' => 'string'],
        'ncc_role' => ['type' => 'string'],
        'ncc_est_date' => ['type' => 'string'],
        'ncc_official_email' => ['type' => 'string'],
        'ncc_website' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'our_ncc',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }
}
add_action('init', 'nrna_register_ncc_committees_meta_fields');

// Add custom columns to admin list
function nrna_ncc_committees_columns($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['ncc_name'] = __('Name', 'nrna');
            $new_columns['ncc_region'] = __('Region', 'nrna');
            $new_columns['ncc_country_name'] = __('Country', 'nrna');
            $new_columns['ncc_role'] = __('Role', 'nrna');
            $new_columns['ncc_year_of_tenure'] = __('Year of Tenure', 'nrna');
            $new_columns['ncc_est_date'] = __('Est. Date', 'nrna');
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_our_ncc_posts_columns', 'nrna_ncc_committees_columns');

// Populate custom columns
function nrna_ncc_committees_column_content($column, $post_id) {
    switch ($column) {
        case 'ncc_name':
            echo esc_html(get_post_meta($post_id, 'ncc_name', true));
            break;
        case 'ncc_region':
            echo esc_html(get_post_meta($post_id, 'ncc_region', true));
            break;
        case 'ncc_country_name':
            echo esc_html(get_post_meta($post_id, 'ncc_country_name', true));
            break;
        case 'ncc_role':
            echo esc_html(get_post_meta($post_id, 'ncc_role', true));
            break;
        case 'ncc_year_of_tenure':
            echo esc_html(get_post_meta($post_id, 'ncc_year_of_tenure', true));
            break;
        case 'ncc_est_date':
            $date = get_post_meta($post_id, 'ncc_est_date', true);
            if ($date) {
                echo esc_html(date('Y-m-d', strtotime($date)));
            }
            break;
    }
}
add_action('manage_our_ncc_posts_custom_column', 'nrna_ncc_committees_column_content', 10, 2);

// Make columns sortable
function nrna_ncc_committees_sortable_columns($columns) {
    $columns['ncc_name'] = 'ncc_name';
    $columns['ncc_region'] = 'ncc_region';
    $columns['ncc_country_name'] = 'ncc_country_name';
    $columns['ncc_role'] = 'ncc_role';
    $columns['ncc_year_of_tenure'] = 'ncc_year_of_tenure';
    $columns['ncc_est_date'] = 'ncc_est_date';
    return $columns;
}
add_filter('manage_edit-our_ncc_sortable_columns', 'nrna_ncc_committees_sortable_columns');

// Handle sorting
function nrna_ncc_committees_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') === 'our_ncc') {
        $orderby = $query->get('orderby');

        switch ($orderby) {
            case 'ncc_name':
                $query->set('meta_key', 'ncc_name');
                $query->set('orderby', 'meta_value');
                break;
            case 'ncc_region':
                $query->set('meta_key', 'ncc_region');
                $query->set('orderby', 'meta_value');
                break;
            case 'ncc_country_name':
                $query->set('meta_key', 'ncc_country_name');
                $query->set('orderby', 'meta_value');
                break;
            case 'ncc_role':
                $query->set('meta_key', 'ncc_role');
                $query->set('orderby', 'meta_value');
                break;
            case 'ncc_year_of_tenure':
                $query->set('meta_key', 'ncc_year_of_tenure');
                $query->set('orderby', 'meta_value_num');
                break;
            case 'ncc_est_date':
                $query->set('meta_key', 'ncc_est_date');
                $query->set('orderby', 'meta_value');
                break;
        }
    }
}
add_action('pre_get_posts', 'nrna_ncc_committees_orderby');

// Prepare REST API response to include meta fields
function nrna_prepare_ncc_committees_rest($response, $post, $request) {
    if ($post->post_type !== 'our_ncc') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'ncc_year_of_tenure',
        'ncc_region',
        'ncc_country_name',
        'ncc_name',
        'ncc_role',
        'ncc_est_date',
        'ncc_official_email',
        'ncc_website',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_our_ncc', 'nrna_prepare_ncc_committees_rest', 10, 3);

// Set post title to ncc_name on save
function nrna_set_ncc_committees_title($post_id) {
    if (get_post_type($post_id) !== 'our_ncc') {
        return;
    }

    // Prevent infinite loop
    remove_action('save_post', 'nrna_set_ncc_committees_title', 20);

    $name = get_post_meta($post_id, 'ncc_name', true);
    if (!empty($name)) {
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $name,
        ]);
    }

    add_action('save_post', 'nrna_set_ncc_committees_title', 20);
}
add_action('save_post', 'nrna_set_ncc_committees_title', 20);

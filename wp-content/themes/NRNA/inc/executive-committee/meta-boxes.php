<?php
// Add meta boxes for committee role, institution, country, and hierarchy order
function nrna_add_executive_committee_meta_boxes() {
    add_meta_box(
        'committee_role_box',
        __('Committee Role', 'nrna'),
        'nrna_render_committee_role_meta_box',
        'executive_committee',
        'normal',
        'high'
    );
    add_meta_box(
        'committee_institution_box',
        __('Institution', 'nrna'),
        'nrna_render_committee_institution_meta_box',
        'executive_committee',
        'normal',
        'high'
    );
    add_meta_box(
        'committee_country_box',
        __('Country', 'nrna'),
        'nrna_render_committee_country_meta_box',
        'executive_committee',
        'normal',
        'high'
    );
    add_meta_box(
        'hierarchy_order_box',
        __('Hierarchy Order', 'nrna'),
        'nrna_render_hierarchy_order_meta_box',
        'executive_committee',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_executive_committee_meta_boxes');

// Render Committee Role meta box
function nrna_render_committee_role_meta_box($post) {
    $role = get_post_meta($post->ID, 'committee_role', true);
    echo '<label for="committee_role" style="display:block; font-weight:bold; margin-bottom:8px;">Role:</label>';
    echo '<input type="text" id="committee_role" name="committee_role" value="' . esc_attr($role) . '" style="width:100%;" placeholder="e.g., President, Vice President, Secretary" />';
}

// Render Committee Institution meta box
function nrna_render_committee_institution_meta_box($post) {
    $institution = get_post_meta($post->ID, 'committee_institution', true);
    echo '<label for="committee_institution" style="display:block; font-weight:bold; margin-bottom:8px;">Institution:</label>';
    echo '<input type="text" id="committee_institution" name="committee_institution" value="' . esc_attr($institution) . '" style="width:100%;" placeholder="e.g., NRNA USA" />';
}

// Render Committee Country meta box
function nrna_render_committee_country_meta_box($post) {
    $country = get_post_meta($post->ID, 'committee_country', true);
    echo '<label for="committee_country" style="display:block; font-weight:bold; margin-bottom:8px;">Country:</label>';
    echo '<input type="text" id="committee_country" name="committee_country" value="' . esc_attr($country) . '" style="width:100%;" placeholder="e.g., United States" />';
}

// Render Hierarchy Order meta box
function nrna_render_hierarchy_order_meta_box($post) {
    $order = get_post_meta($post->ID, 'hierarchy_order', true);
    echo '<label for="hierarchy_order" style="display:block; font-weight:bold; margin-bottom:8px;">Hierarchy Order (lower number = higher position):</label>';
    echo '<input type="number" id="hierarchy_order" name="hierarchy_order" value="' . esc_attr($order) . '" style="width:100%;" min="1" placeholder="e.g., 1, 2, 3..." />';
    echo '<p class="description">Use this to control the display order. Lower numbers appear first.</p>';
}

// Save meta boxes
function nrna_save_executive_committee_meta_boxes($post_id) {
    if (array_key_exists('committee_role', $_POST)) {
        update_post_meta($post_id, 'committee_role', sanitize_text_field($_POST['committee_role']));
    }
    if (array_key_exists('committee_institution', $_POST)) {
        update_post_meta($post_id, 'committee_institution', sanitize_text_field($_POST['committee_institution']));
    }
    if (array_key_exists('committee_country', $_POST)) {
        update_post_meta($post_id, 'committee_country', sanitize_text_field($_POST['committee_country']));
    }
    if (array_key_exists('hierarchy_order', $_POST)) {
        update_post_meta($post_id, 'hierarchy_order', intval($_POST['hierarchy_order']));
    }
}
add_action('save_post', 'nrna_save_executive_committee_meta_boxes');

// Clean up admin screen
function nrna_remove_executive_committee_meta_boxes() {
    remove_meta_box('slugdiv', 'executive_committee', 'normal');
    remove_meta_box('authordiv', 'executive_committee', 'normal');
    remove_meta_box('commentsdiv', 'executive_committee', 'normal');
    remove_meta_box('revisionsdiv', 'executive_committee', 'normal');
}
add_action('admin_menu', 'nrna_remove_executive_committee_meta_boxes');

// Add custom columns to admin list
function nrna_add_executive_committee_columns($columns) {
    $columns['committee_role'] = __('Role', 'nrna');
    $columns['committee_institution'] = __('Institution', 'nrna');
    $columns['committee_country'] = __('Country', 'nrna');
    $columns['hierarchy_order'] = __('Order', 'nrna');
    return $columns;
}
add_filter('manage_executive_committee_posts_columns', 'nrna_add_executive_committee_columns');

// Populate custom columns
function nrna_populate_executive_committee_columns($column, $post_id) {
    if ($column === 'committee_role') {
        $role = get_post_meta($post_id, 'committee_role', true);
        echo esc_html($role);
    }
    if ($column === 'committee_institution') {
        $institution = get_post_meta($post_id, 'committee_institution', true);
        echo esc_html($institution);
    }
    if ($column === 'committee_country') {
        $country = get_post_meta($post_id, 'committee_country', true);
        echo esc_html($country);
    }
    if ($column === 'hierarchy_order') {
        $order = get_post_meta($post_id, 'hierarchy_order', true);
        echo esc_html($order);
    }
}
add_action('manage_executive_committee_posts_custom_column', 'nrna_populate_executive_committee_columns', 10, 2);

// Make hierarchy_order column sortable
function nrna_make_executive_committee_columns_sortable($columns) {
    $columns['hierarchy_order'] = 'hierarchy_order';
    return $columns;
}
add_filter('manage_edit-executive_committee_sortable_columns', 'nrna_make_executive_committee_columns_sortable');

// Handle sorting by hierarchy_order
function nrna_executive_committee_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('orderby') === 'hierarchy_order') {
        $query->set('meta_key', 'hierarchy_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'nrna_executive_committee_orderby');

<?php
// Register FAQ custom post type
function nrna_register_faq_cpt()
{
    $labels = [
        'name'               => _x('FAQs', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('FAQ', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('FAQs', 'nrna'),
        'name_admin_bar'     => __('FAQ', 'nrna'),
        'add_new_item'       => __('Add New FAQ', 'nrna'),
        'edit_item'          => __('Edit FAQ', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-editor-help',
        'menu_position'      => 20,
        'show_in_menu'       => 'nrna-area-menu',
    ];

    register_post_type('faqs', $args);
}
add_action('init', 'nrna_register_faq_cpt');

// Add custom columns to FAQ admin listing
function nrna_faq_custom_columns($columns)
{
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        // Add category column after title
        if ($key === 'title') {
            $new_columns['category'] = __('Category', 'nrna');
        }
    }
    return $new_columns;
}
add_filter('manage_faqs_posts_columns', 'nrna_faq_custom_columns');

// Populate the category column
function nrna_faq_custom_column_content($column, $post_id)
{
    if ($column === 'category') {
        $category = get_post_meta($post_id, 'category', true);
        if ($category) {
            echo esc_html($category);
        } else {
            echo '—';
        }
    }
}
add_action('manage_faqs_posts_custom_column', 'nrna_faq_custom_column_content', 10, 2);

// Make category column sortable
function nrna_faq_sortable_columns($columns)
{
    $columns['category'] = 'category';
    return $columns;
}
add_filter('manage_edit-faqs_sortable_columns', 'nrna_faq_sortable_columns');

// Handle category column sorting
function nrna_faq_category_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ('category' === $orderby) {
        $query->set('meta_key', 'category');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'nrna_faq_category_orderby');

<?php
/**
 * Plugin Name: NRNA Executive Committee
 * Description: Custom post type for managing executive committee members with photo, name, role, and hierarchy.
 * Version: 1.0.0
 * Author: NRNA
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Executive Committee custom post type
function nrna_register_executive_committee_cpt() {
    $labels = [
        'name'               => _x('Executive Committee', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Committee Member', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Executive Committee', 'nrna'),
        'name_admin_bar'     => __('Committee Member', 'nrna'),
        'add_new_item'       => __('Add New Committee Member', 'nrna'),
        'edit_item'          => __('Edit Committee Member', 'nrna'),
        'view_item'          => __('View Committee Member', 'nrna'),
        'all_items'          => __('All Committee Members', 'nrna'),
        'search_items'       => __('Search Committee Members', 'nrna'),
        'not_found'          => __('No committee members found.', 'nrna'),
        'not_found_in_trash' => __('No committee members found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 25,
        'has_archive'        => false,
        'rewrite'            => ['slug' => 'executive-committee'],
    ];

    register_post_type('executive_committee', $args);
}
add_action('init', 'nrna_register_executive_committee_cpt');

// Add meta boxes for committee role and hierarchy order
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

// Shortcode for frontend display
function nrna_executive_committee_shortcode($atts) {
    $atts = shortcode_atts([
        'limit' => -1,
    ], $atts);

    $args = [
        'post_type' => 'executive_committee',
        'posts_per_page' => $atts['limit'],
        'meta_key' => 'hierarchy_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No committee members found.</p>';
    }

    ob_start();
    ?>
    <div class="nrna-executive-committee">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="committee-member">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="member-photo">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>
                <div class="member-info">
                    <h3 class="member-name"><?php the_title(); ?></h3>
                    <?php
                    $role = get_post_meta(get_the_ID(), 'committee_role', true);
                    if ($role) {
                        echo '<p class="member-role">' . esc_html($role) . '</p>';
                    }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('executive_committee', 'nrna_executive_committee_shortcode');

// Register meta fields for REST API
function nrna_register_executive_committee_meta_rest() {
    register_rest_field('executive_committee', 'committee_role', [
        'get_callback' => function($post) {
            return get_post_meta($post['id'], 'committee_role', true);
        },
        'schema' => [
            'type' => 'string',
            'description' => 'Committee role',
        ],
    ]);

    register_rest_field('executive_committee', 'hierarchy_order', [
        'get_callback' => function($post) {
            return (int) get_post_meta($post['id'], 'hierarchy_order', true);
        },
        'schema' => [
            'type' => 'integer',
            'description' => 'Hierarchy order',
        ],
    ]);
}
add_action('rest_api_init', 'nrna_register_executive_committee_meta_rest');

// Enqueue frontend styles
function nrna_executive_committee_enqueue_styles() {
    wp_enqueue_style(
        'nrna-executive-committee-styles',
        plugin_dir_url(__FILE__) . 'css/executive-committee.css',
        [],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'nrna_executive_committee_enqueue_styles');

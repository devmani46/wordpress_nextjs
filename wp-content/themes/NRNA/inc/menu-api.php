<?php

/**
 * Custom REST API endpoint for exposing WordPress menus
 */

// Register the REST API endpoint
add_action('rest_api_init', 'nrna_register_menu_api');

function nrna_register_menu_api()
{
    register_rest_route('wp/v1', '/menu/(?P<location>[a-zA-Z0-9-_]+)', array(
        'methods' => 'GET',
        'callback' => 'nrna_get_menu_items',
        'permission_callback' => '__return_true', // Public access
    ));
}

function nrna_get_menu_items($request)
{
    $location = $request->get_param('location');
    $menu_id = null;

    // Check if the parameter matches a registered theme location
    $menu_locations = get_nav_menu_locations();
    if (isset($menu_locations[$location])) {
        $menu_id = $menu_locations[$location];
    } else {
        // Fallback: Try to find menu by slug, ID, or name
        $menu_object = wp_get_nav_menu_object($location);
        if ($menu_object) {
            $menu_id = $menu_object->term_id;
        }
    }

    // If no menu found, return error
    if (!$menu_id) {
        return new WP_Error('menu_not_found', 'Menu not found', array('status' => 404));
    }

    $menu_items = wp_get_nav_menu_items($menu_id);

    if (!$menu_items) {
        return array();
    }

    // Build the tree structure
    $menu_tree = nrna_build_menu_tree($menu_items);

    return $menu_tree;
}

function nrna_build_menu_tree($menu_items)
{
    $tree = array();
    $menu_items_by_id = array();

    // Index items by ID
    foreach ($menu_items as $item) {
        $slug = ($item->object == 'page') ? get_post_field('post_name', $item->object_id) : '';
        $menu_items_by_id[$item->ID] = array(
            'id' => $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'description' => $item->description,
            'classes' => $item->classes,
            'menu_item_parent' => $item->menu_item_parent,
            'slug' => $slug,
            'children' => array(),
        );
    }

    // Build the tree
    foreach ($menu_items_by_id as $id => &$item) {
        if ($item['menu_item_parent'] == 0) {
            // Root level
            $tree[] = &$item;
        } else {
            // Child item
            if (isset($menu_items_by_id[$item['menu_item_parent']])) {
                $menu_items_by_id[$item['menu_item_parent']]['children'][] = &$item;
            }
        }
    }

    return $tree;
}

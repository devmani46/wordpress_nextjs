<?php
// Include the custom post type and meta boxes
include_once 'custom-post-type.php';
include_once 'meta-boxes.php';

// Add Committees page meta boxes only for pages with specific template
function nrna_add_committees_meta_boxes_conditional() {
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-committees-taskforces-subcommittees.php') {
        nrna_add_committees_meta_boxes();
    }
}
add_action('add_meta_boxes', 'nrna_add_committees_meta_boxes_conditional', 10);

<?php

/**
 * Privacy Policy Meta Box
 * Replicates home-page slider pattern for Privacy Policy page
 */

// Register Privacy Policy meta fields for REST API
function nrna_register_privacy_policy_meta_fields()
{
    register_post_meta('page', 'policy_items', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_privacy_policy_meta_fields');

// Add Privacy Policy meta box
function nrna_add_privacy_policy_meta_box()
{
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-privacy-policy.php') {
        add_meta_box(
            'privacy_policy_meta_box',
            __('Privacy Policy Content', 'nrna'),
            'nrna_render_privacy_policy_meta_box',
            'page',
            'normal',
            'high'
        );
        // Remove default meta boxes for cleaner UI
        remove_meta_box('postdivrich', 'page', 'normal');
        remove_meta_box('slugdiv', 'page', 'normal');
        remove_meta_box('authordiv', 'page', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
        remove_meta_box('revisionsdiv', 'page', 'normal');
    }
}
add_action('add_meta_boxes', 'nrna_add_privacy_policy_meta_box');

// Render Privacy Policy meta box
function nrna_render_privacy_policy_meta_box($post)
{
    wp_nonce_field('nrna_privacy_policy_meta_box', 'nrna_privacy_policy_meta_box_nonce');

    $policy_items = get_post_meta($post->ID, 'policy_items', true);
    if (!is_array($policy_items)) $policy_items = [];

    echo '<div class="home-meta-tabs">';
    echo '<h3 style="margin-bottom: 20px;">Privacy Policy Items</h3>';
?>
    <div class="repeater-container" data-repeater="policy_items">
        <?php foreach ($policy_items as $index => $item): ?>
            <div class="repeater-item">
                <p><label>Title:</label><br><input type="text" name="policy_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="policy_items[<?php echo $index; ?>][description]" rows="6" class="wide-textarea"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea></p>
                <button type="button" class="remove-item button">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="add-item button" data-repeater="policy_items">Add Policy</button>
<?php
    echo '</div>';
}

// Save Privacy Policy meta box
function nrna_save_privacy_policy_meta_box($post_id)
{
    if (!isset($_POST['nrna_privacy_policy_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_privacy_policy_meta_box_nonce'], 'nrna_privacy_policy_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Save policy items
    if (!isset($_POST['policy_items'])) {
        delete_post_meta($post_id, 'policy_items');
        return;
    }

    $data = $_POST['policy_items'];
    $sanitized = array_filter(array_map(function ($item) {
        $clean = [];
        if (isset($item['title'])) $clean['title'] = sanitize_text_field($item['title']);
        if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
        return !empty($clean) ? $clean : null;
    }, (array)$data));

    update_post_meta($post_id, 'policy_items', $sanitized);
}
add_action('save_post', 'nrna_save_privacy_policy_meta_box');

// Prepare Privacy Policy REST response
function nrna_prepare_privacy_policy_page_rest_response($response, $post, $request)
{
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-privacy-policy.php') {
        return $response;
    }

    $data = $response->get_data();

    // Add policy items to response
    $policy_items = get_post_meta($post->ID, 'policy_items', true);
    if (is_array($policy_items) && !empty($policy_items)) {
        $data['policy_items'] = $policy_items;
    }

    // Filter meta to only include policy_items if it exists there, or empty it to avoid clutter
    if (isset($data['meta'])) {
        $data['meta'] = array_intersect_key($data['meta'], ['policy_items' => true]);
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_privacy_policy_page_rest_response', 10, 3);

// Enqueue admin scripts for Privacy Policy page
function nrna_enqueue_privacy_policy_admin_scripts($hook)
{
    global $post;

    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    if (!$post || get_page_template_slug($post->ID) !== 'template-privacy-policy.php') return;

    wp_enqueue_media();
    wp_enqueue_style('nrna-home-tabs', get_template_directory_uri() . '/assets/admin/home-tabs.css');
    wp_enqueue_script('nrna-home-tabs', get_template_directory_uri() . '/assets/admin/home-tabs.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'nrna_enqueue_privacy_policy_admin_scripts');
?>
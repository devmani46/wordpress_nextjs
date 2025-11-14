<?php
function nrna_register_organizational_structure_meta_fields() {
    $fields = [
        'organizational_structure_title' => ['type' => 'string'],
        'organizational_structure_image' => ['type' => 'integer'],
        'organizational_structure_stat_title' => ['type' => 'string'],
        'organizational_structure_stat_description' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }
}
add_action('init', 'nrna_register_organizational_structure_meta_fields');

function nrna_add_organizational_structure_meta_box() {
    global $post;
    if ($post && $post->ID && get_page_template_slug($post->ID) !== 'template-organizational-structure.php') return;

    add_meta_box(
        'organizational_structure_meta_box',
        __('Organizational Structure Page Content', 'nrna'),
        'nrna_render_organizational_structure_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_organizational_structure_meta_box');

function nrna_render_organizational_structure_meta_box($post) {
    if ($post->ID != 0 && get_page_template_slug($post->ID) !== 'template-organizational-structure.php') {
        echo '<p>Please select the "Organizational Structure Page" template to edit these fields.</p>';
        return;
    }

    wp_nonce_field('nrna_organizational_structure_meta_box', 'nrna_organizational_structure_meta_box_nonce');

    $title = get_post_meta($post->ID, 'organizational_structure_title', true);
    $image = get_post_meta($post->ID, 'organizational_structure_image', true);
    $stat_title = get_post_meta($post->ID, 'organizational_structure_stat_title', true);
    $stat_description = get_post_meta($post->ID, 'organizational_structure_stat_description', true);
    ?>
    <p><label>Title:</label><br>
<?php wp_editor($title, 'organizational_structure_title', [
        'media_buttons' => false,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]); ?>
    <p><label>Image:</label><br>
    <input type="hidden" name="organizational_structure_image" value="<?php echo esc_attr($image); ?>" class="image-id">
    <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>" style="max-width: 200px; height: auto;">
    <button type="button" class="upload-image button">Upload Image</button></p>
    <p><label>Stat Title:</label><br><input type="text" name="organizational_structure_stat_title" value="<?php echo esc_attr($stat_title); ?>" class="wide-input"></p>
    <p><label>Stat Description:</label><br><textarea name="organizational_structure_stat_description" rows="3" class="wide-textarea"><?php echo esc_textarea($stat_description); ?></textarea></p>
    <?php
}

function nrna_save_organizational_structure_meta_box($post_id) {
    if (!isset($_POST['nrna_organizational_structure_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_organizational_structure_meta_box_nonce'], 'nrna_organizational_structure_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'organizational_structure_title' => 'wp_kses_post',
        'organizational_structure_image' => 'intval',
        'organizational_structure_stat_title' => 'sanitize_text_field',
        'organizational_structure_stat_description' => 'sanitize_textarea_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }
}
add_action('save_post', 'nrna_save_organizational_structure_meta_box');

function nrna_prepare_organizational_structure_page_rest_response($response, $post, $request) {
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-organizational-structure.php') {
        return $response;
    }

    $data = $response->get_data();

    // Filter meta fields to only include organizational_structure-related fields
    $filtered_meta = [];
    foreach ($data['meta'] as $key => $value) {
        if (strpos($key, 'organizational_structure_') === 0) {
            $filtered_meta[$key] = $value;
        }
    }
    $data['meta'] = $filtered_meta;

    // Single image field
    $image_id = get_post_meta($post->ID, 'organizational_structure_image', true);
    if ($image_id) {
        $data['organizational_structure_image_url'] = wp_get_attachment_image_url($image_id, 'full');
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_organizational_structure_page_rest_response', 10, 3);
?>

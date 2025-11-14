<?php
function nrna_register_about_meta_fields() {
    $fields = [
        'who_we_are_hero_title' => ['type' => 'string'],
        'who_we_are_hero_description' => ['type' => 'string'],
        'who_we_are_vision_title' => ['type' => 'string'],
        'who_we_are_vision_description' => ['type' => 'string'],
        'who_we_are_vision_image' => ['type' => 'integer'],
        'who_we_are_goals_title' => ['type' => 'string'],
        'who_we_are_goals_description' => ['type' => 'string'],
        'who_we_are_goals_image' => ['type' => 'integer'],
        'who_we_are_certificate_title' => ['type' => 'string'],
        'who_we_are_certificate_description' => ['type' => 'string'],
        'who_we_are_certificate_image' => ['type' => 'integer'],
        'who_we_are_message_title' => ['type' => 'string'],
        'who_we_are_message_description' => ['type' => 'string'],
        'who_we_are_message_image' => ['type' => 'integer'],
        'who_we_are_message_representative_name' => ['type' => 'string'],
        'who_we_are_message_representative_role' => ['type' => 'string'],
        'who_we_are_team_title' => ['type' => 'string'],
        'who_we_are_team_description' => ['type' => 'string'],
        'who_we_are_team_cta_link' => ['type' => 'string'],
        'who_we_are_team_cta_title' => ['type' => 'string'],
        'who_we_are_team_image' => ['type' => 'integer'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_post_meta('page', 'who_we_are_slider_items', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'image' => ['type' => 'integer'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_about_meta_fields');



function nrna_add_about_meta_box() {
    global $post;
    if ($post && $post->ID && get_page_template_slug($post->ID) !== 'template-about.php') return;

    add_meta_box(
        'about_page_meta_box',
        __('About Page Content', 'nrna'),
        'nrna_render_about_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_about_meta_box');

function nrna_render_about_meta_box($post) {
    if ($post->ID != 0 && get_page_template_slug($post->ID) !== 'template-about.php') {
        echo '<p>Please select the "About Page" template to edit these fields.</p>';
        return;
    }

    wp_nonce_field('nrna_about_meta_box', 'nrna_about_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero',
        'slider' => 'Slider',
        'vision' => 'Our Vision in Action',
        'goals' => 'Turning Goals into Reality',
        'certificate' => 'Certificate',
        'message' => 'Message',
        'team' => 'The Team'
    ];

    echo '<div class="about-meta-tabs">';
    echo '<div class="tab-buttons">';
    foreach ($tabs as $key => $label) {
        echo '<button type="button" class="tab-button" data-tab="' . esc_attr($key) . '">' . esc_html($label) . '</button>';
    }
    echo '</div>';

    echo '<div class="tab-content">';

    foreach ($tabs as $key => $label) {
        echo '<div class="tab-pane" id="tab-' . esc_attr($key) . '">';

        switch ($key) {

    case 'hero':
        $title = get_post_meta($post->ID, 'who_we_are_hero_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_hero_description', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_hero_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_hero_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <?php
        break;

    case 'slider':
        $slider_items = get_post_meta($post->ID, 'who_we_are_slider_items', true);
        if (!is_array($slider_items)) $slider_items = [];
        ?>
        <div class="repeater-container" data-repeater="who_we_are_slider_items">
            <?php foreach ($slider_items as $index => $item): ?>
                <div class="repeater-item">
                    <p><label>Title:</label><br><input type="text" name="who_we_are_slider_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Description:</label><br><?php wp_editor($item['description'] ?? '', 'who_we_are_slider_items_' . $index . '_description', [
                        'media_buttons' => true,
                        'textarea_rows' => 10,
                        'teeny' => false,
                        'quicktags' => true,
                    ]); ?></p>
                    <p><label>Image:</label><br>
                    <input type="hidden" name="who_we_are_slider_items[<?php echo $index; ?>][image]" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="image-id">
                    <img src="<?php echo ($item['image'] ?? '') ? esc_url(wp_get_attachment_image_url($item['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($item['image'] ?? '') ? 'has-image' : ''; ?>">
                    <button type="button" class="upload-image button">Upload Image</button></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="who_we_are_slider_items">Add Slider</button>
        <?php
        break;

    case 'vision':
        $title = get_post_meta($post->ID, 'who_we_are_vision_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_vision_description', true);
        $image = get_post_meta($post->ID, 'who_we_are_vision_image', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_vision_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_vision_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="who_we_are_vision_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <?php
        break;

    case 'goals':
        $title = get_post_meta($post->ID, 'who_we_are_goals_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_goals_description', true);
        $image = get_post_meta($post->ID, 'who_we_are_goals_image', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_goals_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_goals_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="who_we_are_goals_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <?php
        break;

    case 'certificate':
        $title = get_post_meta($post->ID, 'who_we_are_certificate_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_certificate_description', true);
        $image = get_post_meta($post->ID, 'who_we_are_certificate_image', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_certificate_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_certificate_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="who_we_are_certificate_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <?php
        break;

    case 'message':
        $title = get_post_meta($post->ID, 'who_we_are_message_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_message_description', true);
        $image = get_post_meta($post->ID, 'who_we_are_message_image', true);
        $name = get_post_meta($post->ID, 'who_we_are_message_representative_name', true);
        $role = get_post_meta($post->ID, 'who_we_are_message_representative_role', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_message_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_message_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="who_we_are_message_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <p><label>Representative Name:</label><br><input type="text" name="who_we_are_message_representative_name" value="<?php echo esc_attr($name); ?>" class="wide-input"></p>
        <p><label>Representative Role:</label><br><input type="text" name="who_we_are_message_representative_role" value="<?php echo esc_attr($role); ?>" class="wide-input"></p>
        <?php
        break;

    case 'team':
        $title = get_post_meta($post->ID, 'who_we_are_team_title', true);
        $description = get_post_meta($post->ID, 'who_we_are_team_description', true);
        $cta_link = get_post_meta($post->ID, 'who_we_are_team_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'who_we_are_team_cta_title', true);
        $image = get_post_meta($post->ID, 'who_we_are_team_image', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="who_we_are_team_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><?php wp_editor($description, 'who_we_are_team_description', [
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?></p>
        <p><label>CTA Link:</label><br><input type="url" name="who_we_are_team_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="who_we_are_team_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="who_we_are_team_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <?php
        break;
}
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_about_meta_box($post_id) {
    if (!isset($_POST['nrna_about_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_about_meta_box_nonce'], 'nrna_about_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'who_we_are_hero_title' => 'sanitize_text_field',
        'who_we_are_hero_description' => 'wp_kses_post',
        'who_we_are_vision_title' => 'sanitize_text_field',
        'who_we_are_vision_description' => 'wp_kses_post',
        'who_we_are_vision_image' => 'intval',
        'who_we_are_goals_title' => 'sanitize_text_field',
        'who_we_are_goals_description' => 'wp_kses_post',
        'who_we_are_goals_image' => 'intval',
        'who_we_are_certificate_title' => 'sanitize_text_field',
        'who_we_are_certificate_description' => 'wp_kses_post',
        'who_we_are_certificate_image' => 'intval',
        'who_we_are_message_title' => 'sanitize_text_field',
        'who_we_are_message_description' => 'wp_kses_post',
        'who_we_are_message_image' => 'intval',
        'who_we_are_message_representative_name' => 'sanitize_text_field',
        'who_we_are_message_representative_role' => 'sanitize_text_field',
        'who_we_are_team_title' => 'sanitize_text_field',
        'who_we_are_team_description' => 'wp_kses_post',
        'who_we_are_team_cta_link' => 'esc_url_raw',
        'who_we_are_team_cta_title' => 'sanitize_text_field',
        'who_we_are_team_image' => 'intval',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['who_we_are_slider_items'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        foreach ((array)$data as $item) {
            $clean = [];
            if (isset($item['title'])) $clean['title'] = sanitize_text_field($item['title']);
            if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
            if (isset($item['image'])) $clean['image'] = intval($item['image']);
            if (!empty($clean)) $sanitized[] = $clean;
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
add_action('save_post', 'nrna_save_about_meta_box');

function nrna_prepare_about_page_rest_response($response, $post, $request) {
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-about.php') {
        return $response;
    }

    $data = $response->get_data();

    // Filter meta fields to only include who_we_are-related fields (include who_we_are_ prefixed)
    $filtered_meta = [];
    foreach ($data['meta'] as $key => $value) {
        if (strpos($key, 'who_we_are_') === 0) {
            $filtered_meta[$key] = $value;
        }
    }
    $data['meta'] = $filtered_meta;

    // Single image fields
    $single_image_fields = ['who_we_are_vision_image', 'who_we_are_goals_image', 'who_we_are_certificate_image', 'who_we_are_message_image', 'who_we_are_team_image'];
    foreach ($single_image_fields as $field) {
        $image_id = get_post_meta($post->ID, $field, true);
        if ($image_id) {
            $data[$field . '_url'] = wp_get_attachment_image_url($image_id, 'full');
        }
    }

    // Array fields with images
    $array_image_fields = [
        'who_we_are_slider_items' => 'image',
    ];

    foreach ($array_image_fields as $field => $subfield) {
        $items = get_post_meta($post->ID, $field, true);
        if (is_array($items)) {
            foreach ($items as &$item) {
                if (isset($item[$subfield]) && $item[$subfield]) {
                    $item[$subfield . '_url'] = wp_get_attachment_image_url($item[$subfield], 'full');
                }
            }
            $data[$field] = $items;
        }
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_about_page_rest_response', 10, 3);
?>

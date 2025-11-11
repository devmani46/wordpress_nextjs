<?php
// Add Who We Are meta boxes
function nrna_add_who_we_are_meta_boxes() {
    add_meta_box(
        'who_we_are_meta_box',
        __('Who We Are Content', 'nrna'),
        'nrna_render_who_we_are_meta_box',
        'who-we-are',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_who_we_are_meta_boxes');

// Render Who We Are meta box with tabs
function nrna_render_who_we_are_meta_box($post) {
    wp_nonce_field('nrna_who_we_are_meta_box', 'nrna_who_we_are_meta_box_nonce');

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

// Save Who We Are meta box
function nrna_save_who_we_are_meta_box($post_id) {
    if (!isset($_POST['nrna_who_we_are_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_who_we_are_meta_box_nonce'], 'nrna_who_we_are_meta_box')) return;
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
add_action('save_post', 'nrna_save_who_we_are_meta_box');

// Clean up Who We Are admin screen
function nrna_remove_who_we_are_meta_boxes() {
    remove_meta_box('slugdiv', 'who-we-are', 'normal');
    remove_meta_box('authordiv', 'who-we-are', 'normal');
    remove_meta_box('commentsdiv', 'who-we-are', 'normal');
    remove_meta_box('revisionsdiv', 'who-we-are', 'normal');
}
add_action('admin_menu', 'nrna_remove_who_we_are_meta_boxes');

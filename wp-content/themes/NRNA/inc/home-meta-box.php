<?php
function nrna_add_home_meta_box() {
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-home.php') {
        add_meta_box(
            'home_page_meta_box',
            __('Home Page Content', 'nrna'),
            'nrna_render_home_meta_box',
            'page',
            'normal',
            'high'
        );
        remove_meta_box('postdivrich', 'page', 'normal');
        remove_meta_box('slugdiv', 'page', 'normal');
        remove_meta_box('authordiv', 'page', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
        remove_meta_box('revisionsdiv', 'page', 'normal');
    }
}
add_action('add_meta_boxes', 'nrna_add_home_meta_box');

function nrna_render_home_meta_box($post) {
    wp_nonce_field('nrna_home_meta_box', 'nrna_home_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero',
        'slider' => 'Slider',
        'about-us' => 'About Us',
        'why-choose-us' => 'Why Choose Us',
        'get-involved' => 'Get Involved',
        'stay-updated' => 'Stay Updated',
        'latest-news' => 'Latest News and Update',
        'our-initiatives' => 'Our Initiatives',
        'join-the-journey' => 'Join the Journey'
    ];

    echo '<div class="home-meta-tabs">';
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
                $title = get_post_meta($post->ID, 'hero_title', true);
                $description = get_post_meta($post->ID, 'hero_description', true);
                $cta_link = get_post_meta($post->ID, 'hero_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'hero_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="hero_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="hero_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>CTA Link:</label><br><input type="url" name="hero_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="hero_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <?php
                break;

            case 'slider':
                $slider_items = get_post_meta($post->ID, 'slider_items', true);
                if (!is_array($slider_items)) $slider_items = [];
                ?>
                <div class="repeater-container" data-repeater="slider_items">
                    <?php foreach ($slider_items as $index => $item): ?>
                        <div class="repeater-item">
                            <p><label>Title:</label><br><input type="text" name="slider_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Image:</label><br>
                            <input type="hidden" name="slider_items[<?php echo $index; ?>][image]" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="image-id">
                            <img src="<?php echo ($item['image'] ?? '') ? esc_url(wp_get_attachment_image_url($item['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($item['image'] ?? '') ? 'has-image' : ''; ?>">
                            <button type="button" class="upload-image button">Upload Image</button></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="slider_items">Add Slider Item</button>
                <?php
                break;

            case 'about-us':
                $title = get_post_meta($post->ID, 'about_title', true);
                $description = get_post_meta($post->ID, 'about_description', true);
                $image = get_post_meta($post->ID, 'about_image', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="about_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="about_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>Image:</label><br>
                <input type="hidden" name="about_image" value="<?php echo esc_attr($image); ?>" class="image-id">
                <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
                <button type="button" class="upload-image button">Upload Image</button></p>
                <?php
                break;

            case 'why-choose-us':
                $title = get_post_meta($post->ID, 'why_title', true);
                $description = get_post_meta($post->ID, 'why_description', true);
                $features = get_post_meta($post->ID, 'why_features', true);
                if (!is_array($features)) $features = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="why_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="why_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <div class="repeater-container" data-repeater="why_features">
                    <?php foreach ($features as $index => $feature): ?>
                        <div class="repeater-item">
                            <p><label>Title:</label><br><input type="text" name="why_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Description:</label><br><textarea name="why_features[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($feature['description'] ?? ''); ?></textarea></p>
                            <p><label>Image:</label><br>
                            <input type="hidden" name="why_features[<?php echo $index; ?>][image]" value="<?php echo esc_attr($feature['image'] ?? ''); ?>" class="image-id">
                            <img src="<?php echo ($feature['image'] ?? '') ? esc_url(wp_get_attachment_image_url($feature['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($feature['image'] ?? '') ? 'has-image' : ''; ?>">
                            <button type="button" class="upload-image button">Upload Image</button></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="why_features">Add Feature</button>
                <?php
                break;

            case 'get-involved':
                $title = get_post_meta($post->ID, 'involved_title', true);
                $description = get_post_meta($post->ID, 'involved_description', true);
                $button_text = get_post_meta($post->ID, 'involved_button_text', true);
                $button_link = get_post_meta($post->ID, 'involved_button_link', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="involved_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="involved_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>Button Text:</label><br><input type="text" name="involved_button_text" value="<?php echo esc_attr($button_text); ?>" class="wide-input"></p>
                <p><label>Button Link:</label><br><input type="url" name="involved_button_link" value="<?php echo esc_attr($button_link); ?>" class="wide-input"></p>
                <?php
                break;

            case 'stay-updated':
                $title = get_post_meta($post->ID, 'updated_title', true);
                $description = get_post_meta($post->ID, 'updated_description', true);
                $button_text = get_post_meta($post->ID, 'updated_button_text', true);
                $button_link = get_post_meta($post->ID, 'updated_button_link', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="updated_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="updated_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>Button Text:</label><br><input type="text" name="updated_button_text" value="<?php echo esc_attr($button_text); ?>" class="wide-input"></p>
                <p><label>Button Link:</label><br><input type="url" name="updated_button_link" value="<?php echo esc_attr($button_link); ?>" class="wide-input"></p>
                <?php
                break;

            case 'latest-news':
                $title = get_post_meta($post->ID, 'news_title', true);
                $description = get_post_meta($post->ID, 'news_description', true);
                $button_text = get_post_meta($post->ID, 'news_button_text', true);
                $button_link = get_post_meta($post->ID, 'news_button_link', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="news_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="news_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>Button Text:</label><br><input type="text" name="news_button_text" value="<?php echo esc_attr($button_text); ?>" class="wide-input"></p>
                <p><label>Button Link:</label><br><input type="url" name="news_button_link" value="<?php echo esc_attr($button_link); ?>" class="wide-input"></p>
                <?php
                break;

            case 'our-initiatives':
                $title = get_post_meta($post->ID, 'initiatives_title', true);
                $description = get_post_meta($post->ID, 'initiatives_description', true);
                $items = get_post_meta($post->ID, 'initiatives_items', true);
                if (!is_array($items)) $items = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="initiatives_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="initiatives_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <div class="repeater-container" data-repeater="initiatives_items">
                    <?php foreach ($items as $index => $item): ?>
                        <div class="repeater-item">
                            <p><label>Title:</label><br><input type="text" name="initiatives_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Description:</label><br><textarea name="initiatives_items[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea></p>
                            <p><label>Image:</label><br>
                            <input type="hidden" name="initiatives_items[<?php echo $index; ?>][image]" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="image-id">
                            <img src="<?php echo ($item['image'] ?? '') ? esc_url(wp_get_attachment_image_url($item['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($item['image'] ?? '') ? 'has-image' : ''; ?>">
                            <button type="button" class="upload-image button">Upload Image</button></p>
                            <p><label>Button Text:</label><br><input type="text" name="initiatives_items[<?php echo $index; ?>][button_text]" value="<?php echo esc_attr($item['button_text'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Button Link:</label><br><input type="url" name="initiatives_items[<?php echo $index; ?>][button_link]" value="<?php echo esc_attr($item['button_link'] ?? ''); ?>" class="wide-input"></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="initiatives_items">Add Initiative</button>
                <?php
                break;

            case 'join-the-journey':
                $title = get_post_meta($post->ID, 'journey_title', true);
                $description = get_post_meta($post->ID, 'journey_description', true);
                $image = get_post_meta($post->ID, 'journey_image', true);
                $button_text = get_post_meta($post->ID, 'journey_button_text', true);
                $button_link = get_post_meta($post->ID, 'journey_button_link', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="journey_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="journey_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <p><label>Image:</label><br>
                <input type="hidden" name="journey_image" value="<?php echo esc_attr($image); ?>" class="image-id">
                <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
                <button type="button" class="upload-image button">Upload Image</button></p>
                <p><label>Button Text:</label><br><input type="text" name="journey_button_text" value="<?php echo esc_attr($button_text); ?>" class="wide-input"></p>
                <p><label>Button Link:</label><br><input type="url" name="journey_button_link" value="<?php echo esc_attr($button_link); ?>" class="wide-input"></p>
                <?php
                break;
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_home_meta_box($post_id) {
    if (!isset($_POST['nrna_home_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_home_meta_box_nonce'], 'nrna_home_meta_box')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = [
        'hero_title' => 'sanitize_text_field',
        'hero_description' => 'wp_kses_post',
        'hero_cta_link' => 'esc_url_raw',
        'hero_cta_title' => 'sanitize_text_field',
        'about_title' => 'sanitize_text_field',
        'about_description' => 'wp_kses_post',
        'about_image' => 'intval',
        'why_title' => 'sanitize_text_field',
        'why_description' => 'wp_kses_post',
        'involved_title' => 'sanitize_text_field',
        'involved_description' => 'wp_kses_post',
        'involved_button_text' => 'sanitize_text_field',
        'involved_button_link' => 'esc_url_raw',
        'updated_title' => 'sanitize_text_field',
        'updated_description' => 'wp_kses_post',
        'updated_button_text' => 'sanitize_text_field',
        'updated_button_link' => 'esc_url_raw',
        'news_title' => 'sanitize_text_field',
        'news_description' => 'wp_kses_post',
        'news_button_text' => 'sanitize_text_field',
        'news_button_link' => 'esc_url_raw',
        'initiatives_title' => 'sanitize_text_field',
        'initiatives_description' => 'wp_kses_post',
        'journey_title' => 'sanitize_text_field',
        'journey_description' => 'wp_kses_post',
        'journey_image' => 'intval',
        'journey_button_text' => 'sanitize_text_field',
        'journey_button_link' => 'esc_url_raw',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['slider_items', 'why_features', 'initiatives_items'];
    foreach ($array_fields as $field) {
        if (isset($_POST[$field]) && is_array($_POST[$field])) {
            $sanitized = [];
            foreach ($_POST[$field] as $item) {
                $sanitized_item = [];
                if (isset($item['title'])) $sanitized_item['title'] = sanitize_text_field($item['title']);
                if (isset($item['description'])) $sanitized_item['description'] = wp_kses_post($item['description']);
                if (isset($item['image'])) $sanitized_item['image'] = intval($item['image']);
                if (isset($item['button_text'])) $sanitized_item['button_text'] = sanitize_text_field($item['button_text']);
                if (isset($item['button_link'])) $sanitized_item['button_link'] = esc_url_raw($item['button_link']);
                if (!empty($sanitized_item)) $sanitized[] = $sanitized_item;
            }
            update_post_meta($post_id, $field, $sanitized);
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
add_action('save_post', 'nrna_save_home_meta_box');

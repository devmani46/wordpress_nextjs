<?php
function nrna_register_home_meta_fields() {
    $fields = [
        'hero_title' => ['type' => 'string'],
        'hero_description' => ['type' => 'string'],
        'hero_cta_link' => ['type' => 'string'],
        'hero_cta_title' => ['type' => 'string'],
        'about_title' => ['type' => 'string'],
        'about_image_1' => ['type' => 'integer'],
        'about_image_2' => ['type' => 'integer'],
        'about_image_3' => ['type' => 'integer'],
        'why_title' => ['type' => 'string'],
        'why_description' => ['type' => 'string'],
        'why_cta_link' => ['type' => 'string'],
        'why_cta_title' => ['type' => 'string'],
        'why_video_link' => ['type' => 'string'],
        'why_years_of_services' => ['type' => 'integer'],
        'involved_title' => ['type' => 'string'],
        'involved_description' => ['type' => 'string'],
        'stay_updated_title' => ['type' => 'string'],
        'stay_updated_description' => ['type' => 'string'],
        'latest_news_title' => ['type' => 'string'],
        'latest_news_description' => ['type' => 'string'],
        'our_initiatives_title' => ['type' => 'string'],
        'our_initiatives_description' => ['type' => 'string'],
        'journey_title' => ['type' => 'string'],
        'journey_description' => ['type' => 'string'],
        'journey_cta_link' => ['type' => 'string'],
        'journey_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_post_meta('page', 'slider_items', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'image' => ['type' => 'integer'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'about_stats', [
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

    register_post_meta('page', 'why_images', [
        'type' => 'array',
        'items' => ['type' => 'integer'],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'why_features', [
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

    register_post_meta('page', 'involved_actions', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'cta_link' => ['type' => 'string'],
                'cta_title' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_home_meta_fields');

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
        <button type="button" class="add-item button" data-repeater="slider_items">Add Slider</button>
        <?php
        break;

    case 'about-us':
        $title = get_post_meta($post->ID, 'about_title', true);
        $image1 = get_post_meta($post->ID, 'about_image_1', true);
        $image2 = get_post_meta($post->ID, 'about_image_2', true);
        $image3 = get_post_meta($post->ID, 'about_image_3', true);
        $stats = get_post_meta($post->ID, 'about_stats', true);
        if (!is_array($stats)) $stats = [];
        ?>
        <p><label>Title:</label><br><input type="text" name="about_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Image 1:</label><br>
        <input type="hidden" name="about_image_1" value="<?php echo esc_attr($image1); ?>" class="image-id">
        <img src="<?php echo $image1 ? esc_url(wp_get_attachment_image_url($image1, 'medium')) : ''; ?>" class="image-preview <?php echo $image1 ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <p><label>Image 2:</label><br>
        <input type="hidden" name="about_image_2" value="<?php echo esc_attr($image2); ?>" class="image-id">
        <img src="<?php echo $image2 ? esc_url(wp_get_attachment_image_url($image2, 'medium')) : ''; ?>" class="image-preview <?php echo $image2 ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <p><label>Image 3:</label><br>
        <input type="hidden" name="about_image_3" value="<?php echo esc_attr($image3); ?>" class="image-id">
        <img src="<?php echo $image3 ? esc_url(wp_get_attachment_image_url($image3, 'medium')) : ''; ?>" class="image-preview <?php echo $image3 ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <div class="repeater-container" data-repeater="about_stats">
            <?php foreach ($stats as $index => $stat): ?>
                <div class="repeater-item">
                    <p><label>Stat Title:</label><br><input type="text" name="about_stats[<?php echo $index; ?>][title]" value="<?php echo esc_attr($stat['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Stat Description:</label><br><textarea name="about_stats[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($stat['description'] ?? ''); ?></textarea></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="about_stats">Add Stat</button>
        <?php
        break;

    case 'why-choose-us':
        $title = get_post_meta($post->ID, 'why_title', true);
        $description = get_post_meta($post->ID, 'why_description', true);
        $cta_link = get_post_meta($post->ID, 'why_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'why_cta_title', true);
        $images = get_post_meta($post->ID, 'why_images', true);
        if (!is_array($images)) $images = array_fill(0, 5, '');
        $features = get_post_meta($post->ID, 'why_features', true);
        if (!is_array($features)) $features = [];
        $video_link = get_post_meta($post->ID, 'why_video_link', true);
        $years = get_post_meta($post->ID, 'why_years_of_services', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="why_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="why_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <p><label>CTA Link:</label><br><input type="url" name="why_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="why_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <h4>Images (exactly 5):</h4>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <p><label>Image <?php echo $i+1; ?>:</label><br>
            <input type="hidden" name="why_images[<?php echo $i; ?>]" value="<?php echo esc_attr($images[$i]); ?>" class="image-id">
            <img src="<?php echo $images[$i] ? esc_url(wp_get_attachment_image_url($images[$i], 'medium')) : ''; ?>" class="image-preview <?php echo $images[$i] ? 'has-image' : ''; ?>">
            <button type="button" class="upload-image button">Upload Image</button></p>
        <?php endfor; ?>
        <div class="repeater-container" data-repeater="why_features">
            <?php foreach ($features as $index => $feature): ?>
                <div class="repeater-item">
                    <p><label>Feature Title:</label><br><input type="text" name="why_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Feature Description:</label><br><textarea name="why_features[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($feature['description'] ?? ''); ?></textarea></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="why_features">Add Feature</button>
        <p><label>Video Link:</label><br><input type="url" name="why_video_link" value="<?php echo esc_attr($video_link); ?>" class="wide-input"></p>
        <p><label>Years of Services:</label><br><input type="number" name="why_years_of_services" value="<?php echo esc_attr($years); ?>" class="wide-input"></p>
        <?php
        break;

    case 'get-involved':
        $title = get_post_meta($post->ID, 'involved_title', true);
        $description = get_post_meta($post->ID, 'involved_description', true);
        $actions = get_post_meta($post->ID, 'involved_actions', true);
        if (!is_array($actions)) $actions = array_fill(0, 3, ['title' => '', 'description' => '', 'cta_link' => '', 'cta_title' => '']);
        ?>
        <p><label>Title:</label><br><input type="text" name="involved_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="involved_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <h4>Actions (3 fixed):</h4>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="action-item">
                <p><label>Action <?php echo $i+1; ?> Title:</label><br><input type="text" name="involved_actions[<?php echo $i; ?>][title]" value="<?php echo esc_attr($actions[$i]['title']); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="involved_actions[<?php echo $i; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($actions[$i]['description']); ?></textarea></p>
                <p><label>CTA Link:</label><br><input type="url" name="involved_actions[<?php echo $i; ?>][cta_link]" value="<?php echo esc_attr($actions[$i]['cta_link']); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="involved_actions[<?php echo $i; ?>][cta_title]" value="<?php echo esc_attr($actions[$i]['cta_title']); ?>" class="wide-input"></p>
            </div>
        <?php endfor; ?>
        <?php
        break;

    case 'stay-updated':
    case 'latest-news':
    case 'our-initiatives':
        $prefix = str_replace('-', '_', $key);
        $title = get_post_meta($post->ID, $prefix . '_title', true);
        $description = get_post_meta($post->ID, $prefix . '_description', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="<?php echo $prefix; ?>_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="<?php echo $prefix; ?>_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <?php
        break;

    case 'join-the-journey':
        $title = get_post_meta($post->ID, 'journey_title', true);
        $description = get_post_meta($post->ID, 'journey_description', true);
        $cta_link = get_post_meta($post->ID, 'journey_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'journey_cta_title', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="journey_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="journey_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <p><label>CTA Link:</label><br><input type="url" name="journey_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="journey_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <?php
        break;
}
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_home_meta_box($post_id) {
    if (!isset($_POST['nrna_home_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_home_meta_box_nonce'], 'nrna_home_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'hero_title' => 'sanitize_text_field',
        'hero_description' => 'wp_kses_post',
        'hero_cta_link' => 'esc_url_raw',
        'hero_cta_title' => 'sanitize_text_field',

        'about_title' => 'sanitize_text_field',
        'about_image_1' => 'intval',
        'about_image_2' => 'intval',
        'about_image_3' => 'intval',

        'why_title' => 'sanitize_text_field',
        'why_description' => 'wp_kses_post',
        'why_cta_link' => 'esc_url_raw',
        'why_cta_title' => 'sanitize_text_field',
        'why_video_link' => 'esc_url_raw',
        'why_years_of_services' => 'intval',

        'involved_title' => 'sanitize_text_field',
        'involved_description' => 'wp_kses_post',

        'stay_updated_title' => 'sanitize_text_field',
        'stay_updated_description' => 'wp_kses_post',

        'latest_news_title' => 'sanitize_text_field',
        'latest_news_description' => 'wp_kses_post',

        'our_initiatives_title' => 'sanitize_text_field',
        'our_initiatives_description' => 'wp_kses_post',

        'journey_title' => 'sanitize_text_field',
        'journey_description' => 'wp_kses_post',
        'journey_cta_link' => 'esc_url_raw',
        'journey_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['slider_items', 'about_stats', 'why_features', 'involved_actions', 'why_images'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        if ($field === 'why_images') {
            for ($i = 0; $i < 5; $i++) {
                $sanitized[$i] = intval($data[$i] ?? 0);
            }

        } elseif ($field === 'involved_actions') {
            for ($i = 0; $i < 3; $i++) {
                $item = $data[$i] ?? [];
                $sanitized[$i] = [
                    'title' => sanitize_text_field($item['title'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'cta_link' => esc_url_raw($item['cta_link'] ?? ''),
                    'cta_title' => sanitize_text_field($item['cta_title'] ?? ''),
                ];
            }

        } else {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['title'])) $clean['title'] = sanitize_text_field($item['title']);
                if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
                if (isset($item['image'])) $clean['image'] = intval($item['image']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
add_action('save_post', 'nrna_save_home_meta_box');

function nrna_prepare_page_rest_response($response, $post, $request) {
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-home.php') {
        return $response;
    }

    $data = $response->get_data();

    // Filter meta fields to only include home-related fields (exclude about_ prefixed)
    $filtered_meta = [];
    foreach ($data['meta'] as $key => $value) {
        if (strpos($key, 'about_') !== 0) {
            $filtered_meta[$key] = $value;
        }
    }
    $data['meta'] = $filtered_meta;

    // Add about_title outside meta
    $about_title = get_post_meta($post->ID, 'about_title', true);
    if ($about_title) {
        $data['about_title'] = $about_title;
    }

    // Single image fields
    $single_image_fields = ['about_image_1', 'about_image_2', 'about_image_3'];
    foreach ($single_image_fields as $field) {
        $image_id = get_post_meta($post->ID, $field, true);
        if ($image_id) {
            $data[$field . '_url'] = wp_get_attachment_image_url($image_id, 'full');
        }
    }

    // Array fields
    $array_fields = ['slider_items', 'about_stats', 'why_images', 'why_features', 'involved_actions'];

    foreach ($array_fields as $field) {
        $items = get_post_meta($post->ID, $field, true);
        if (is_array($items) && !empty($items)) {
            if ($field === 'slider_items') {
                // Add image URLs for slider_items
                foreach ($items as &$item) {
                    if (isset($item['image']) && $item['image']) {
                        $item['image_url'] = wp_get_attachment_image_url($item['image'], 'full');
                    }
                }
            } elseif ($field === 'why_images') {
                // Add URLs array for why_images
                $urls = [];
                foreach ($items as $id) {
                    if ($id) {
                        $urls[] = wp_get_attachment_image_url($id, 'full');
                    }
                }
                $data[$field . '_urls'] = $urls;
            }
            // Add the array itself
            $data[$field] = $items;
        }
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_page_rest_response', 10, 3);


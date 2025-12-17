<?php
function nrna_register_discount_meta_fields()
{
    $fields = [
        'discount_hero_title' => ['type' => 'string'],
        'discount_hero_description' => ['type' => 'string'],
        'discount_hero_cta1_link' => ['type' => 'string'],
        'discount_hero_cta1_title' => ['type' => 'string'],
        'discount_hero_cta2_link' => ['type' => 'string'],
        'discount_hero_cta2_title' => ['type' => 'string'],
        'discount_hero_image1' => ['type' => 'integer'],
        'discount_hero_image2' => ['type' => 'integer'],
        'discount_hero_image3' => ['type' => 'integer'],
        'discount_hero_banner_title' => ['type' => 'string'],
        'discount_hero_banner_description' => ['type' => 'string'],
        'discount_how_title' => ['type' => 'string'],
        'discount_how_description' => ['type' => 'string'],
        'discount_join_title' => ['type' => 'string'],
        'discount_join_description' => ['type' => 'string'],
        'discount_join_cta_link' => ['type' => 'string'],
        'discount_join_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_post_meta('page', 'discount_hero_stats', [
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

    register_post_meta('page', 'discount_how_banner', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'label' => ['type' => 'string'],
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'discount_partners', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'category' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'offer_text' => ['type' => 'string'],
                'partner_type' => ['type' => 'string'],
                'photo' => ['type' => 'integer'],
                'cta_link' => ['type' => 'string'],
                'cta_title' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'discount_join_points', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'discount_join_stats', [
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
add_action('init', 'nrna_register_discount_meta_fields');

function nrna_add_discount_meta_box()
{
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-nrna-discount.php') {
        add_meta_box(
            'discount_page_meta_box',
            __('NRNA Discount Page Content', 'nrna'),
            'nrna_render_discount_meta_box',
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
add_action('add_meta_boxes', 'nrna_add_discount_meta_box');

function nrna_render_discount_meta_box($post)
{
    wp_nonce_field('nrna_discount_meta_box', 'nrna_discount_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero Section',
        'how-it-works' => 'How It Works',
        'browse-partners' => 'Browse Partner Discount',
        'join-nrna' => 'Join NRNA'
    ];

    echo '<div class="discount-meta-tabs">';
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
                $title = get_post_meta($post->ID, 'discount_hero_title', true);
                $description = get_post_meta($post->ID, 'discount_hero_description', true);
                $cta1_link = get_post_meta($post->ID, 'discount_hero_cta1_link', true);
                $cta1_title = get_post_meta($post->ID, 'discount_hero_cta1_title', true);
                $cta2_link = get_post_meta($post->ID, 'discount_hero_cta2_link', true);
                $cta2_title = get_post_meta($post->ID, 'discount_hero_cta2_title', true);
                $image1 = get_post_meta($post->ID, 'discount_hero_image1', true);
                $image2 = get_post_meta($post->ID, 'discount_hero_image2', true);
                $image3 = get_post_meta($post->ID, 'discount_hero_image3', true);
                $banner_title = get_post_meta($post->ID, 'discount_hero_banner_title', true);
                $banner_description = get_post_meta($post->ID, 'discount_hero_banner_description', true);
                $stats = get_post_meta($post->ID, 'discount_hero_stats', true);
                if (!is_array($stats)) $stats = [];
?>
                <p><label>Title:</label><br>
                    <?php wp_editor($title, 'discount_hero_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_hero_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br>
                    <?php wp_editor($description, 'discount_hero_description', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_hero_description',
                    ]); ?>
                </p>
                <p><label>CTA 1 Link:</label><br><input type="url" name="discount_hero_cta1_link" value="<?php echo esc_attr($cta1_link); ?>" class="wide-input"></p>
                <p><label>CTA 1 Title:</label><br><input type="text" name="discount_hero_cta1_title" value="<?php echo esc_attr($cta1_title); ?>" class="wide-input"></p>
                <p><label>CTA 2 Link:</label><br><input type="url" name="discount_hero_cta2_link" value="<?php echo esc_attr($cta2_link); ?>" class="wide-input"></p>
                <p><label>CTA 2 Title:</label><br><input type="text" name="discount_hero_cta2_title" value="<?php echo esc_attr($cta2_title); ?>" class="wide-input"></p>
                <p><label>Image 1:</label><br>
                    <input type="hidden" name="discount_hero_image1" value="<?php echo esc_attr($image1); ?>" class="image-id">
                    <img src="<?php echo $image1 ? esc_url(wp_get_attachment_image_url($image1, 'medium')) : ''; ?>" class="image-preview <?php echo $image1 ? 'has-image' : ''; ?>">
                    <button type="button" class="upload-image button">Upload Image</button>
                </p>
                <p><label>Image 2:</label><br>
                    <input type="hidden" name="discount_hero_image2" value="<?php echo esc_attr($image2); ?>" class="image-id">
                    <img src="<?php echo $image2 ? esc_url(wp_get_attachment_image_url($image2, 'medium')) : ''; ?>" class="image-preview <?php echo $image2 ? 'has-image' : ''; ?>">
                    <button type="button" class="upload-image button">Upload Image</button>
                </p>
                <p><label>Image 3:</label><br>
                    <input type="hidden" name="discount_hero_image3" value="<?php echo esc_attr($image3); ?>" class="image-id">
                    <img src="<?php echo $image3 ? esc_url(wp_get_attachment_image_url($image3, 'medium')) : ''; ?>" class="image-preview <?php echo $image3 ? 'has-image' : ''; ?>">
                    <button type="button" class="upload-image button">Upload Image</button>
                </p>
                <p><label>Banner Title:</label><br>
                    <?php wp_editor($banner_title, 'discount_hero_banner_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_hero_banner_title',
                    ]); ?>
                </p>
                <p><label>Banner Description:</label><br>
                    <?php wp_editor($banner_description, 'discount_hero_banner_description', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_hero_banner_description',
                    ]); ?>
                </p>
                <div class="repeater-container" data-repeater="discount_hero_stats">
                    <?php foreach ($stats as $index => $stat): ?>
                        <div class="repeater-item">
                            <p><label>Stat Title:</label><br>
                                <?php wp_editor($stat['title'] ?? '', 'discount_hero_stats_' . $index . '_title', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_hero_stats[' . $index . '][title]',
                                ]); ?>
                            </p>
                            <p><label>Stat Description:</label><br>
                                <?php wp_editor($stat['description'] ?? '', 'discount_hero_stats_' . $index . '_description', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_hero_stats[' . $index . '][description]',
                                ]); ?>
                            </p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="discount_hero_stats">Add Stat</button>
            <?php
                break;

            case 'how-it-works':
                $title = get_post_meta($post->ID, 'discount_how_title', true);
                $description = get_post_meta($post->ID, 'discount_how_description', true);
                $banner = get_post_meta($post->ID, 'discount_how_banner', true);
                if (!is_array($banner)) $banner = [];
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($title, 'discount_how_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_how_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br>
                    <?php wp_editor($description, 'discount_how_description', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_how_description',
                    ]); ?>
                </p>
                <div class="repeater-container" data-repeater="discount_how_banner">
                    <?php foreach ($banner as $index => $item): ?>
                        <div class="repeater-item">
                            <p><label>Label:</label><br><input type="text" name="discount_how_banner[<?php echo $index; ?>][label]" value="<?php echo esc_attr($item['label'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Title:</label><br>
                                <?php wp_editor($item['title'] ?? '', 'discount_how_banner_' . $index . '_title', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_how_banner[' . $index . '][title]',
                                ]); ?>
                            </p>
                            <p><label>Description:</label><br>
                                <?php wp_editor($item['description'] ?? '', 'discount_how_banner_' . $index . '_description', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_how_banner[' . $index . '][description]',
                                ]); ?>
                            </p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="discount_how_banner">Add Banner Item</button>
            <?php
                break;

            case 'browse-partners':
                $partners = get_post_meta($post->ID, 'discount_partners', true);
                if (!is_array($partners)) $partners = [];
            ?>
                <div class="repeater-container" data-repeater="discount_partners">
                    <?php foreach ($partners as $index => $partner): ?>
                        <div class="repeater-item">
                            <p><label>Category:</label><br><input type="text" name="discount_partners[<?php echo $index; ?>][category]" value="<?php echo esc_attr($partner['category'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Name:</label><br>
                                <?php wp_editor($partner['name'] ?? '', 'discount_partners_' . $index . '_name', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_partners[' . $index . '][name]',
                                ]); ?>
                            </p>
                            <p><label>Description:</label><br>
                                <?php wp_editor($partner['description'] ?? '', 'discount_partners_' . $index . '_description', [
                                    'media_buttons' => false,
                                    'textarea_rows' => 3,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => 'discount_partners[' . $index . '][description]',
                                ]); ?>
                            </p>
                            <p><label>Offer Text:</label><br><input type="text" name="discount_partners[<?php echo $index; ?>][offer_text]" value="<?php echo esc_attr($partner['offer_text'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Partner Type:</label><br>
                                <select name="discount_partners[<?php echo $index; ?>][partner_type]">
                                    <option value="verified" <?php selected($partner['partner_type'] ?? '', 'verified'); ?>>Verified</option>
                                    <option value="unverified" <?php selected($partner['partner_type'] ?? '', 'unverified'); ?>>Unverified</option>
                                </select>
                            </p>
                            <p><label>Photo:</label><br>
                                <input type="hidden" name="discount_partners[<?php echo $index; ?>][photo]" value="<?php echo esc_attr($partner['photo'] ?? ''); ?>" class="image-id">
                                <img src="<?php echo ($partner['photo'] ?? '') ? esc_url(wp_get_attachment_image_url($partner['photo'], 'medium')) : ''; ?>" class="image-preview <?php echo ($partner['photo'] ?? '') ? 'has-image' : ''; ?>">
                                <button type="button" class="upload-image button">Upload Image</button>
                            </p>
                            <p><label>CTA Link:</label><br><input type="url" name="discount_partners[<?php echo $index; ?>][cta_link]" value="<?php echo esc_attr($partner['cta_link'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>CTA Title:</label><br><input type="text" name="discount_partners[<?php echo $index; ?>][cta_title]" value="<?php echo esc_attr($partner['cta_title'] ?? ''); ?>" class="wide-input"></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="discount_partners">Add Partner</button>
            <?php
                break;

            case 'join-nrna':
                $title = get_post_meta($post->ID, 'discount_join_title', true);
                $description = get_post_meta($post->ID, 'discount_join_description', true);
                $points = get_post_meta($post->ID, 'discount_join_points', true);
                if (!is_array($points)) $points = [];
                $cta_link = get_post_meta($post->ID, 'discount_join_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'discount_join_cta_title', true);
                $stats = get_post_meta($post->ID, 'discount_join_stats', true);
                if (!is_array($stats)) $stats = [];
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($title, 'discount_join_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_join_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br>
                    <?php wp_editor($description, 'discount_join_description', [
                        'media_buttons' => false,
                        'textarea_rows' => 3,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'discount_join_description',
                    ]); ?>
                </p>
                <div class="repeater-container" data-repeater="discount_join_points">
                    <?php foreach ($points as $index => $point): ?>
                        <div class="repeater-item">
                            <div class="repeater-item">
                                <p><label>Title:</label><br>
                                    <?php wp_editor($point['title'] ?? '', 'discount_join_points_' . $index . '_title', [
                                        'media_buttons' => false,
                                        'textarea_rows' => 3,
                                        'teeny' => false,
                                        'quicktags' => true,
                                        'textarea_name' => 'discount_join_points[' . $index . '][title]',
                                    ]); ?>
                                </p>
                                <button type="button" class="remove-item button">Remove</button>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <button type="button" class="add-item button" data-repeater="discount_join_points">Add Point</button>
                        <p><label>CTA Link:</label><br><input type="url" name="discount_join_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                        <p><label>CTA Title:</label><br><input type="text" name="discount_join_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                        <div class="repeater-container" data-repeater="discount_join_stats">
                            <?php foreach ($stats as $index => $stat): ?>
                                <div class="repeater-item">
                                    <div class="repeater-item">
                                        <p><label>Stat Title:</label><br>
                                            <?php wp_editor($stat['title'] ?? '', 'discount_join_stats_' . $index . '_title', [
                                                'media_buttons' => false,
                                                'textarea_rows' => 3,
                                                'teeny' => false,
                                                'quicktags' => true,
                                                'textarea_name' => 'discount_join_stats[' . $index . '][title]',
                                            ]); ?>
                                        </p>
                                        <p><label>Stat Description:</label><br>
                                            <?php wp_editor($stat['description'] ?? '', 'discount_join_stats_' . $index . '_description', [
                                                'media_buttons' => false,
                                                'textarea_rows' => 3,
                                                'teeny' => false,
                                                'quicktags' => true,
                                                'textarea_name' => 'discount_join_stats[' . $index . '][description]',
                                            ]); ?>
                                        </p>
                                        <button type="button" class="remove-item button">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                                <button type="button" class="add-item button" data-repeater="discount_join_stats">Add Stat</button>
                <?php
                break;
        }
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_discount_meta_box($post_id)
{
    if (!isset($_POST['nrna_discount_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_discount_meta_box_nonce'], 'nrna_discount_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'discount_hero_title' => 'wp_kses_post',
        'discount_hero_description' => 'wp_kses_post',
        'discount_hero_cta1_link' => 'esc_url_raw',
        'discount_hero_cta1_title' => 'sanitize_text_field',
        'discount_hero_cta2_link' => 'esc_url_raw',
        'discount_hero_cta2_title' => 'sanitize_text_field',
        'discount_hero_image1' => 'intval',
        'discount_hero_image2' => 'intval',
        'discount_hero_image3' => 'intval',
        'discount_hero_banner_title' => 'wp_kses_post',
        'discount_hero_banner_description' => 'wp_kses_post',
        'discount_how_title' => 'wp_kses_post',
        'discount_how_description' => 'wp_kses_post',
        'discount_join_title' => 'wp_kses_post',
        'discount_join_description' => 'wp_kses_post',
        'discount_join_cta_link' => 'esc_url_raw',
        'discount_join_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['discount_hero_stats', 'discount_how_banner', 'discount_partners', 'discount_join_points', 'discount_join_stats'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        if ($field === 'discount_partners') {
            foreach ((array)$data as $item) {
                $clean = [
                    'category' => sanitize_text_field($item['category'] ?? ''),
                    'name' => wp_kses_post($item['name'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'offer_text' => sanitize_text_field($item['offer_text'] ?? ''),
                    'partner_type' => sanitize_text_field($item['partner_type'] ?? ''),
                    'photo' => intval($item['photo'] ?? 0),
                    'cta_link' => esc_url_raw($item['cta_link'] ?? ''),
                    'cta_title' => sanitize_text_field($item['cta_title'] ?? ''),
                ];
                if (!empty(array_filter($clean))) $sanitized[] = $clean;
            }
        } else {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['title'])) $clean['title'] = wp_kses_post($item['title']);
                if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
                if (isset($item['label'])) $clean['label'] = sanitize_text_field($item['label']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
add_action('save_post', 'nrna_save_discount_meta_box');

function nrna_prepare_discount_page_rest_response($response, $post, $request)
{
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-nrna-discount.php') {
        return $response;
    }

    $data = $response->get_data();

    // Single image fields
    $single_image_fields = ['discount_hero_image1', 'discount_hero_image2', 'discount_hero_image3'];
    foreach ($single_image_fields as $field) {
        $image_id = get_post_meta($post->ID, $field, true);
        if ($image_id) {
            $data[$field . '_url'] = wp_get_attachment_image_url($image_id, 'full');
        }
    }

    // Array fields
    $array_fields = ['discount_hero_stats', 'discount_how_banner', 'discount_partners', 'discount_join_points', 'discount_join_stats'];

    foreach ($array_fields as $field) {
        $items = get_post_meta($post->ID, $field, true);
        if (is_array($items) && !empty($items)) {
            if ($field === 'discount_partners') {
                // Add image URLs for partners
                foreach ($items as &$item) {
                    if (isset($item['photo']) && $item['photo']) {
                        $item['photo_url'] = wp_get_attachment_image_url($item['photo'], 'full');
                    }
                }
            }
            $data[$field] = $items;
        }
    }

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_discount_page_rest_response', 10, 3);

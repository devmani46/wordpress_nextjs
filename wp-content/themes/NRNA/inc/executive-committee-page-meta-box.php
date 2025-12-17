<?php
function nrna_register_executive_committee_page_meta_fields()
{
    $fields = [
        'executive_committee_banner_title' => ['type' => 'string'],
        'executive_committee_banner_cta_link' => ['type' => 'string'],
        'executive_committee_banner_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }
}
add_action('init', 'nrna_register_executive_committee_page_meta_fields');

function nrna_add_executive_committee_page_meta_box()
{
    global $post;
    if ($post && $post->ID && get_page_template_slug($post->ID) !== 'template-executive-committee.php') return;

    add_meta_box(
        'executive_committee_page_meta_box',
        __('Executive Committee Page Content', 'nrna'),
        'nrna_render_executive_committee_page_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_executive_committee_page_meta_box');

function nrna_render_executive_committee_page_meta_box($post)
{
    if ($post->ID != 0 && get_page_template_slug($post->ID) !== 'template-executive-committee.php') {
        echo '<p>Please select the "Executive Committee" template to edit these fields.</p>';
        return;
    }

    wp_nonce_field('nrna_executive_committee_page_meta_box', 'nrna_executive_committee_page_meta_box_nonce');

    $banner_title = get_post_meta($post->ID, 'executive_committee_banner_title', true);
    $banner_cta_link = get_post_meta($post->ID, 'executive_committee_banner_cta_link', true);
    $banner_cta_title = get_post_meta($post->ID, 'executive_committee_banner_cta_title', true);
?>
    <h3 style="margin-top: 20px; padding: 10px; background: #f0f0f1; border-left: 4px solid #2271b1;">Banner Section</h3>
    <p><label>Banner Title:</label><br>
        <?php wp_editor($banner_title, 'executive_committee_banner_title', [
            'media_buttons' => false,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        ]); ?>
    </p>
    <p><label>Banner CTA Link:</label><br><input type="url" name="executive_committee_banner_cta_link" value="<?php echo esc_attr($banner_cta_link); ?>" class="wide-input"></p>
    <p><label>Banner CTA Title:</label><br><input type="text" name="executive_committee_banner_cta_title" value="<?php echo esc_attr($banner_cta_title); ?>" class="wide-input"></p>
<?php
}

function nrna_save_executive_committee_page_meta_box($post_id)
{
    if (!isset($_POST['nrna_executive_committee_page_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_executive_committee_page_meta_box_nonce'], 'nrna_executive_committee_page_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'executive_committee_banner_title' => 'wp_kses_post',
        'executive_committee_banner_cta_link' => 'esc_url_raw',
        'executive_committee_banner_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }
}
add_action('save_post', 'nrna_save_executive_committee_page_meta_box');

function nrna_prepare_executive_committee_page_rest_response($response, $post, $request)
{
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-executive-committee.php') {
        return $response;
    }

    $data = $response->get_data();

    // Filter meta fields to only include executive_committee-related fields
    $filtered_meta = [];
    foreach ($data['meta'] as $key => $value) {
        if (strpos($key, 'executive_committee_') === 0) {
            $filtered_meta[$key] = $value;
        }
    }
    $data['meta'] = $filtered_meta;

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_executive_committee_page_rest_response', 10, 3);
?>
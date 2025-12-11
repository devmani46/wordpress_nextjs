<?php
// Add NCC Committees meta boxes
function nrna_add_ncc_committees_meta_boxes()
{
    add_meta_box(
        'ncc_committees_meta_box',
        __('Our NCC Details', 'nrna'),
        'nrna_render_ncc_committees_meta_box',
        'our_ncc',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_ncc_committees_meta_boxes');

// Render NCC Committees meta box
function nrna_render_ncc_committees_meta_box($post)
{
    wp_nonce_field('nrna_ncc_committees_meta_box', 'nrna_ncc_committees_meta_box_nonce');

    $year_of_tenure = get_post_meta($post->ID, 'ncc_year_of_tenure', true);
    $region = get_post_meta($post->ID, 'ncc_region', true);
    $country_name = get_post_meta($post->ID, 'ncc_country_name', true);
    $name = get_post_meta($post->ID, 'ncc_name', true);
    $role = get_post_meta($post->ID, 'ncc_role', true);
    $est_date = get_post_meta($post->ID, 'ncc_est_date', true);
    $official_email = get_post_meta($post->ID, 'ncc_official_email', true);
    $website = get_post_meta($post->ID, 'ncc_website', true);

    $regions = [
        'African Region',
        'American Region',
        'Asia Pacific Region',
        'Europe Region',
        'Middle East Region',
        'Oceania Region'
    ];

?>
    <h3 style="margin-top: 20px; padding: 10px; background: #f0f0f1; border-left: 4px solid #2271b1;">NCC Details</h3>
    <table class="form-table">
        <tr>
            <th><label for="ncc_year_of_tenure">Year of Tenure:</label></th>
            <td><input type="number" id="ncc_year_of_tenure" name="ncc_year_of_tenure" value="<?php echo esc_attr($year_of_tenure); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_region">Region:</label></th>
            <td>
                <select id="ncc_region" name="ncc_region" class="regular-text">
                    <option value="">Select Region</option>
                    <?php foreach ($regions as $region_option): ?>
                        <option value="<?php echo esc_attr($region_option); ?>" <?php selected($region, $region_option); ?>><?php echo esc_html($region_option); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ncc_country_name">Country Name:</label></th>
            <td><input type="text" id="ncc_country_name" name="ncc_country_name" value="<?php echo esc_attr($country_name); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_name">Name:</label></th>
            <td><input type="text" id="ncc_name" name="ncc_name" value="<?php echo esc_attr($name); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_role">Role:</label></th>
            <td><input type="text" id="ncc_role" name="ncc_role" value="<?php echo esc_attr($role); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_est_date">Our NCC Est. Date:</label></th>
            <td><input type="date" id="ncc_est_date" name="ncc_est_date" value="<?php echo esc_attr($est_date); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_official_email">Official Email:</label></th>
            <td><input type="email" id="ncc_official_email" name="ncc_official_email" value="<?php echo esc_attr($official_email); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="ncc_website">Website:</label></th>
            <td><input type="url" id="ncc_website" name="ncc_website" value="<?php echo esc_attr($website); ?>" class="regular-text"></td>
        </tr>
    </table>
<?php
}

// Save NCC Committees meta box
function nrna_save_ncc_committees_meta_box($post_id)
{
    if (!isset($_POST['nrna_ncc_committees_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_ncc_committees_meta_box_nonce'], 'nrna_ncc_committees_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'ncc_year_of_tenure' => 'intval',
        'ncc_region' => 'sanitize_text_field',
        'ncc_country_name' => 'sanitize_text_field',
        'ncc_name' => 'sanitize_text_field',
        'ncc_role' => 'sanitize_text_field',
        'ncc_est_date' => 'sanitize_text_field',
        'ncc_official_email' => 'sanitize_email',
        'ncc_website' => 'esc_url_raw',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }
}
add_action('save_post', 'nrna_save_ncc_committees_meta_box');

// Clean up NCC Committees admin screen
function nrna_remove_ncc_committees_meta_boxes()
{
    remove_meta_box('slugdiv', 'our_ncc', 'normal');
    remove_meta_box('authordiv', 'our_ncc', 'normal');
    remove_meta_box('commentsdiv', 'our_ncc', 'normal');
    remove_meta_box('revisionsdiv', 'our_ncc', 'normal');
}
add_action('admin_menu', 'nrna_remove_ncc_committees_meta_boxes');

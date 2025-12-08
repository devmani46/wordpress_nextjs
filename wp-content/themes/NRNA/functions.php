<?php

/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

// Load all includes
require_once $inc . 'index.php';

// Theme setup
function nrna_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'nrna'),
    ]);
}
add_action('after_setup_theme', 'nrna_theme_setup');

// Add News & Events parent menu
function nrna_add_news_events_menu()
{
    add_menu_page(
        'News & Events',
        'News & Events',
        'manage_options',
        'news-events-menu',
        '',
        'dashicons-admin-site',
        20
    );
}
add_action('admin_menu', 'nrna_add_news_events_menu');

// Add Gallery parent menu
function nrna_add_gallery_menu()
{
    add_menu_page(
        'Gallery',
        'Gallery',
        'manage_options',
        'gallery-menu',
        '',
        'dashicons-images-alt2',
        25
    );
}
add_action('admin_menu', 'nrna_add_gallery_menu');

// Add Home page parent menu
function nrna_add_home_menu()
{
    add_menu_page(
        'Home Page',
        'Home Page',
        'manage_options',
        'home-page-menu',
        '',
        'dashicons-admin-home',
        19
    );
    // home page
    $home_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-home.php',
        'number' => 1
    ]);

    if (!empty($home_page)) {
        $home_link = 'post.php?post=' . $home_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'home-page-menu',
        'Home Page',
        'Home Page',
        'manage_options',
        $home_link
    );
    // Remove the default "Home Page" submenu created by add_menu_page
    remove_submenu_page('home-page-menu', 'home-page-menu');
}
add_action('admin_menu', 'nrna_add_home_menu');

// Add About Us parent menu
function nrna_add_about_us_menu()
{
    add_menu_page(
        'About Us',
        'About Us',
        'manage_options',
        'about-us-menu',
        '',
        'dashicons-info',
        21
    );

    // 1. Who We Are (Page)
    // Find page by template
    $who_we_are_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-who-we-are.php',
        'number' => 1
    ]);

    if (!empty($who_we_are_page)) {
        $who_we_are_link = 'post.php?post=' . $who_we_are_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Who We Are',
        'Who We Are',
        'manage_options',
        $who_we_are_link
    );

    // 2. Executive Committee (CPT)
    add_submenu_page(
        'about-us-menu',
        'Executive Committee',
        'Executive Committee',
        'manage_options',
        'edit.php?post_type=executive_committee'
    );

    // 3. Our NCCs (CPT)
    add_submenu_page(
        'about-us-menu',
        'Our NCCs',
        'Our NCCs',
        'manage_options',
        'edit.php?post_type=our_ncc'
    );

    // 4. Committees, Taskforces & Subcommittees (Page)
    $committees_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-committees-taskforces-subcommittees.php',
        'number' => 1
    ]);

    $committees_link = 'post-new.php?post_type=page';
    if (!empty($committees_page)) {
        $committees_link = 'post.php?post=' . $committees_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Committees & Taskforces',
        'Committees & Taskforces',
        'manage_options',
        $committees_link
    );

    // 5. NRNA Organizational Structure (Page)
    $org_structure_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-organizational-structure.php',
        'number' => 1
    ]);

    $org_structure_link = 'post-new.php?post_type=page';
    if (!empty($org_structure_page)) {
        $org_structure_link = 'post.php?post=' . $org_structure_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Organizational Structure',
        'Organizational Structure',
        'manage_options',
        $org_structure_link
    );

    // Remove the default "About Us" submenu created by add_menu_page
    remove_submenu_page('about-us-menu', 'about-us-menu');
}
add_action('admin_menu', 'nrna_add_about_us_menu');

// Add NRNA Area parent menu
function nrna_add_nrna_area_menu()
{
    add_menu_page(
        'NRNA Area',
        'NRNA Area',
        'manage_options',
        'nrna-area-menu',
        '',
        'dashicons-networking',
        22
    );

    // 1. Contact Us (Page)
    // Find page by template
    $contact_us_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-contact.php',
        'number' => 1
    ]);

    $contact_us_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($contact_us_page)) {
        $contact_us_link = 'post.php?post=' . $contact_us_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'Contact Us',
        'Contact Us',
        'manage_options',
        $contact_us_link
    );

    // 3. NRNA Discount (Page)
    // Find page by template
    $nrna_discount_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-nrna-discount.php',
        'number' => 1
    ]);

    $nrna_discount_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($nrna_discount_page)) {
        $nrna_discount_link = 'post.php?post=' . $nrna_discount_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'NRNA Discount',
        'NRNA Discount',
        'manage_options',
        $nrna_discount_link
    );

    // 3. Privacy Policy (Page)
    // Find page by template
    $privacy_policy_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-privacy-policy.php',
        'number' => 1
    ]);

    $privacy_policy_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($privacy_policy_page)) {
        $privacy_policy_link = 'post.php?post=' . $privacy_policy_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'Privacy Policy',
        'Privacy Policy',
        'manage_options',
        $privacy_policy_link
    );

    // 4. Terms and Conditions (Page)
    // Find page by template
    $terms_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-terms-and-conditions.php',
        'number' => 1
    ]);

    $terms_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($terms_page)) {
        $terms_link = 'post.php?post=' . $terms_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'Terms and Conditions',
        'Terms and Conditions',
        'manage_options',
        $terms_link
    );

    // Remove the default "NRNA Area" submenu created by add_menu_page
    remove_submenu_page('nrna-area-menu', 'nrna-area-menu');
}
add_action('admin_menu', 'nrna_add_nrna_area_menu');

/**
 * Register REST API endpoint for contact form submissions
 */
function nrna_register_contact_endpoint()
{
    register_rest_route('nrna/v1', '/contact', [
        'methods' => 'POST',
        'callback' => 'nrna_handle_contact_submission',
        'permission_callback' => '__return_true', // Allow public access
    ]);
}
add_action('rest_api_init', 'nrna_register_contact_endpoint');

/**
 * Handle contact form submission
 */
function nrna_handle_contact_submission($request)
{
    // Get parameters from request
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $email = sanitize_email($request->get_param('email'));
    $phone = sanitize_text_field($request->get_param('phone'));
    $message = sanitize_textarea_field($request->get_param('message'));

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
        return new WP_Error(
            'missing_fields',
            'Please fill in all required fields.',
            ['status' => 400]
        );
    }

    // Validate email format
    if (!is_email($email)) {
        return new WP_Error(
            'invalid_email',
            'Please enter a valid email address.',
            ['status' => 400]
        );
    }

    // Prepare email content
    $to = get_option('admin_email'); // Send to site admin email
    $subject = 'New Contact Form Submission from ' . $first_name . ' ' . $last_name;

    $email_message = "You have received a new contact form submission:\n\n";
    $email_message .= "Name: " . $first_name . " " . $last_name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Phone: " . ($phone ? $phone : 'Not provided') . "\n\n";
    $email_message .= "Message:\n" . $message . "\n";

    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // Send email
    $email_sent = wp_mail($to, $subject, $email_message, $headers);

    // Store submission in database (optional)
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_submissions';

    // Create table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50),
        message text NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Insert submission
    $wpdb->insert(
        $table_name,
        [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        ],
        ['%s', '%s', '%s', '%s', '%s']
    );

    // Debug logging
    error_log('Contact form - Insert ID: ' . $wpdb->insert_id);
    error_log('Contact form - Email sent: ' . ($email_sent ? 'yes' : 'no'));
    error_log('Contact form - WP DB Error: ' . $wpdb->last_error);

    // Check if database insert was successful
    if ($wpdb->insert_id) {
        // Submission saved successfully
        if ($email_sent) {
            return new WP_REST_Response([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully.',
            ], 200);
        } else {
            // Email failed but submission was saved
            return new WP_REST_Response([
                'success' => true,
                'message' => 'Thank you! Your message has been received.',
            ], 200);
        }
    } else {
        return new WP_Error(
            'submission_failed',
            'Failed to save your message. Please try again later.',
            ['status' => 500]
        );
    }
}

/**
 * Add Contact Submissions admin menu page
 */
function nrna_add_contact_submissions_menu()
{
    add_menu_page(
        'Contact Submissions',           // Page title
        'Contact Submissions',           // Menu title
        'manage_options',                // Capability
        'contact-submissions',           // Menu slug
        'nrna_contact_submissions_page', // Callback function
        'dashicons-email',               // Icon
        26                               // Position
    );
}
add_action('admin_menu', 'nrna_add_contact_submissions_menu');

/**
 * Display contact submissions admin page
 */
function nrna_contact_submissions_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_submissions';

    // Get all submissions, ordered by most recent first
    $submissions = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY submitted_at DESC"
    );

?>
    <div class="wrap">
        <h1>Contact Form Submissions</h1>

        <?php if (empty($submissions)) : ?>
            <p>No submissions yet.</p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 12%;">Name</th>
                        <th style="width: 15%;">Email</th>
                        <th style="width: 12%;">Phone</th>
                        <th style="width: 36%;">Message</th>
                        <th style="width: 15%;">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission) : ?>
                        <tr>
                            <td><?php echo esc_html($submission->id); ?></td>
                            <td><?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?></td>
                            <td>
                                <a href="mailto:<?php echo esc_attr($submission->email); ?>">
                                    <?php echo esc_html($submission->email); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html($submission->phone ?: 'N/A'); ?></td>
                            <td style="white-space: pre-wrap;"><?php echo esc_html($submission->message); ?></td>
                            <td><?php echo esc_html(date('M j, Y g:i A', strtotime($submission->submitted_at))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top: 20px;">
                <strong>Total Submissions:</strong> <?php echo count($submissions); ?>
            </p>
        <?php endif; ?>
    </div>

    <style>
        .wrap table td {
            vertical-align: top;
            padding: 10px;
        }

        .wrap table th {
            font-weight: 600;
        }
    </style>
<?php
}

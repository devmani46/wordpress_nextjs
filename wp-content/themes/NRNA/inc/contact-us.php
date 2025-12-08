<?php

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
        status varchar(20) DEFAULT 'new' NOT NULL,
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
            'status' => 'new'
        ],
        ['%s', '%s', '%s', '%s', '%s', '%s']
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
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_submissions';

    // Count new submissions
    // Check if table exists first to avoid errors on fresh install before first submission
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'new'");
    } else {
        $count = 0;
    }

    $menu_title = 'Contact Submissions';
    if ($count > 0) {
        $menu_title .= ' <span class="awaiting-mod count-' . esc_attr($count) . '"><span class="pending-count" aria-hidden="true">' . esc_html($count) . '</span></span>';
    }

    add_menu_page(
        'Contact Submissions',           // Page title
        $menu_title,                     // Menu title (with badge)
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

    // Check for actions
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Handle Delete Action
    if ($action === 'delete' && $id > 0) {
        check_admin_referer('delete_submission_' . $id);

        $wpdb->delete($table_name, ['id' => $id]);

        // Redirect to list to avoid resubmission
        echo '<script>window.location.href="admin.php?page=contact-submissions&message=deleted";</script>';
        exit;
    }

    // Handle View Action
    if ($action === 'view' && $id > 0) {
        // Mark as read
        $wpdb->update(
            $table_name,
            ['status' => 'read'],
            ['id' => $id]
        );

        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        if (!$submission) {
            echo '<div class="wrap"><p>Submission not found.</p></div>';
            return;
        }

?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Submission Details</h1>
            <a href="admin.php?page=contact-submissions" class="page-title-action">Back to List</a>
            <hr class="wp-header-end">

            <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
                <h2 style="margin-top: 0;"><?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?></h2>
                <p>
                    <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($submission->email); ?>"><?php echo esc_html($submission->email); ?></a><br>
                    <strong>Phone:</strong> <?php echo esc_html($submission->phone ?: 'N/A'); ?><br>
                    <strong>Date:</strong> <?php echo esc_html(date('F j, Y g:i A', strtotime($submission->submitted_at))); ?>
                </p>
                <hr>
                <h3>Message</h3>
                <p style="white-space: pre-wrap; font-size: 1.1em; line-height: 1.6;"><?php echo esc_html($submission->message); ?></p>

                <hr>
                <div style="margin-top: 20px;">
                    <a href="<?php echo wp_nonce_url('admin.php?page=contact-submissions&action=delete&id=' . $submission->id, 'delete_submission_' . $submission->id); ?>"
                        class="button button-link-delete"
                        onclick="return confirm('Are you sure you want to delete this submission?')">
                        Delete Submission
                    </a>
                </div>
            </div>
        </div>
    <?php
        return;
    }

    // List View
    $submissions = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY submitted_at DESC"
    );

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Contact Form Submissions</h1>
        <hr class="wp-header-end">

        <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted') : ?>
            <div class="notice notice-success is-dismissible">
                <p>Submission deleted successfully.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($submissions)) : ?>
            <div class="card" style="margin-top: 20px; padding: 20px;">
                <p>No submissions yet.</p>
            </div>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 15%;">Name</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 35%;">Message Preview</th>
                        <th style="width: 15%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission) :
                        $is_new = isset($submission->status) && $submission->status === 'new';
                        $row_activator = 'admin.php?page=contact-submissions&action=view&id=' . $submission->id;
                    ?>
                        <tr class="<?php echo $is_new ? 'updated' : ''; ?>">
                            <td><?php echo esc_html($submission->id); ?></td>
                            <td>
                                <strong><a href="<?php echo $row_activator; ?>" class="row-title"><?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?></a></strong>
                                <div class="row-actions">
                                    <span class="view"><a href="<?php echo $row_activator; ?>">View</a> | </span>
                                    <span class="delete"><a href="<?php echo wp_nonce_url('admin.php?page=contact-submissions&action=delete&id=' . $submission->id, 'delete_submission_' . $submission->id); ?>" class="submitdelete" onclick="return confirm('Are you sure?')">Delete</a></span>
                                </div>
                            </td>
                            <td><a href="mailto:<?php echo esc_attr($submission->email); ?>"><?php echo esc_html($submission->email); ?></a></td>
                            <td>
                                <?php if ($is_new) : ?>
                                    <span class="dashicons dashicons-marker" style="color: #ca4a1f;"></span> <strong>New</strong>
                                <?php else : ?>
                                    <span class="dashicons dashicons-yes" style="color: #999;"></span> Read
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $preview = wp_trim_words($submission->message, 15);
                                echo esc_html($preview);
                                ?>
                            </td>
                            <td><?php echo esc_html(date('M j, Y', strtotime($submission->submitted_at))); ?></td>
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

        .updated {
            background-color: #f0f6fc !important;
        }

        tr.updated th,
        tr.updated td {
            box-shadow: inset 2px 0 0 #3571b1;
        }
    </style>
<?php
}

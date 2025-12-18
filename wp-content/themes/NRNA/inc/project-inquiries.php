<?php

/**
 * Register REST API endpoint for project inquiry submissions
 */
function nrna_register_project_inquiry_endpoint()
{
    register_rest_route('nrna/v1', '/project-inquiry', [
        'methods' => 'POST',
        'callback' => 'nrna_handle_project_inquiry_submission',
        'permission_callback' => '__return_true', // Allow public access
    ]);
}
add_action('rest_api_init', 'nrna_register_project_inquiry_endpoint');

/**
 * Handle project inquiry submission
 */
function nrna_handle_project_inquiry_submission($request)
{
    // Get parameters from request
    $project_slug = sanitize_text_field($request->get_param('project_slug'));
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $email = sanitize_email($request->get_param('email'));
    $phone = sanitize_text_field($request->get_param('phone'));
    $message = sanitize_textarea_field($request->get_param('message'));

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
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
    $subject = 'New Project Inquiry: ' . $project_slug . ' - ' . $first_name . ' ' . $last_name;

    $email_message = "You have received a new project inquiry:\n\n";
    $email_message .= "Project: " . ($project_slug ? $project_slug : 'N/A') . "\n";
    $email_message .= "Name: " . $first_name . " " . $last_name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Phone: " . $phone . "\n";
    $email_message .= "Message: " . ($message ? $message : 'No message') . "\n";

    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // Send email
    $email_sent = wp_mail($to, $subject, $email_message, $headers);

    // Store submission in database
    global $wpdb;
    $table_name = $wpdb->prefix . 'project_inquiries';

    // Create table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        project_slug varchar(255) NOT NULL,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        message text,
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
            'project_slug' => $project_slug,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'status' => 'new'
        ],
        ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
    );

    // Check if database insert was successful
    if ($wpdb->insert_id) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Inquiry successful! We have received your details.',
        ], 200);
    } else {
        return new WP_Error(
            'submission_failed',
            'Failed to save inquiry. DB Error: ' . $wpdb->last_error,
            ['status' => 500]
        );
    }
}

/**
 * Add Project Inquiries admin menu page
 */
function nrna_add_project_inquiries_menu()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'project_inquiries';

    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'new'");
    } else {
        $count = 0;
    }

    $menu_title = 'Project Inquiries';
    if ($count > 0) {
        $menu_title .= ' <span class="awaiting-mod count-' . esc_attr($count) . '"><span class="pending-count" aria-hidden="true">' . esc_html($count) . '</span></span>';
    }

    add_menu_page(
        'Project Inquiries',           // Page title
        $menu_title,                     // Menu title
        'manage_options',                // Capability
        'project-inquiries',           // Menu slug
        'nrna_project_inquiries_page', // Callback function
        'dashicons-format-chat',         // Icon
        28                               // Position
    );
}
add_action('admin_menu', 'nrna_add_project_inquiries_menu');

/**
 * Display project inquiries admin page
 */
function nrna_project_inquiries_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'project_inquiries';

    // Check for actions
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Handle Delete Action
    if ($action === 'delete' && $id > 0) {
        check_admin_referer('delete_inquiry_' . $id);
        $wpdb->delete($table_name, ['id' => $id]);
        echo '<script>window.location.href="admin.php?page=project-inquiries&message=deleted";</script>';
        exit;
    }

    // Handle Mark as Read Action
    if ($action === 'mark_read' && $id > 0) {
        check_admin_referer('mark_read_inquiry_' . $id);
        $wpdb->update($table_name, ['status' => 'read'], ['id' => $id]);
        echo '<script>window.location.href="admin.php?page=project-inquiries&message=marked_read";</script>';
        exit;
    }

    // Handle Mark ALL as Read Action
    if ($action === 'mark_all_read') {
        check_admin_referer('mark_all_read_inquiries');
        $wpdb->update($table_name, ['status' => 'read'], ['status' => 'new']);
        echo '<script>window.location.href="admin.php?page=project-inquiries&message=all_marked_read";</script>';
        exit;
    }

    // View Details
    if ($action === 'view' && $id > 0) {
        // Mark as read when viewing
        $wpdb->update($table_name, ['status' => 'read'], ['id' => $id]);

        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if (!$submission) {
            echo '<div class="wrap"><p>Inquiry not found.</p></div>';
            return;
        }
?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Inquiry Details</h1>
            <a href="admin.php?page=project-inquiries" class="page-title-action">Back to List</a>
            <hr class="wp-header-end">

            <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
                <h2 style="margin-top: 0;"><?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?></h2>
                <p>
                    <strong>Project:</strong> <?php echo esc_html($submission->project_slug ?: 'N/A'); ?><br>
                    <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($submission->email); ?>"><?php echo esc_html($submission->email); ?></a><br>
                    <strong>Phone:</strong> <?php echo esc_html($submission->phone); ?><br>
                    <strong>Message:</strong> <?php echo nl2br(esc_html($submission->message ?: 'No message')); ?><br>
                    <strong>Date:</strong> <?php echo esc_html(date('F j, Y g:i A', strtotime($submission->submitted_at))); ?>
                </p>
                <hr>
                <div style="margin-top: 20px;">
                    <a href="<?php echo wp_nonce_url('admin.php?page=project-inquiries&action=delete&id=' . $submission->id, 'delete_inquiry_' . $submission->id); ?>"
                        class="button button-link-delete"
                        onclick="return confirm('Are you sure you want to delete this inquiry?')">
                        Delete Inquiry
                    </a>
                </div>
            </div>
        </div>
    <?php
        return;
    }

    // List View
    $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submitted_at DESC");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Project Inquiries</h1>
        <a href="<?php echo wp_nonce_url('admin.php?page=project-inquiries&action=mark_all_read', 'mark_all_read_inquiries'); ?>" class="page-title-action">Mark All as Read</a>
        <hr class="wp-header-end">

        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] === 'deleted') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Inquiry deleted successfully.</p>
                </div>
            <?php elseif ($_GET['message'] === 'all_marked_read') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>All new inquiries marked as read.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($submissions)) : ?>
            <div class="card" style="margin-top: 20px; padding: 20px;">
                <p>No inquiries yet.</p>
            </div>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">S.N.</th>
                        <th style="width: 20%;">Project</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Phone</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1;
                    foreach ($submissions as $sub) :
                        $is_new = $sub->status === 'new';
                        $view_url = 'admin.php?page=project-inquiries&action=view&id=' . $sub->id;
                    ?>
                        <tr class="<?php echo $is_new ? 'updated' : ''; ?>">
                            <td><?php echo $sn++; ?></td>
                            <td><strong><?php echo esc_html($sub->project_slug ?: 'N/A'); ?></strong></td>
                            <td>
                                <strong><a class="row-title" href="<?php echo $view_url; ?>"><?php echo esc_html($sub->first_name . ' ' . $sub->last_name); ?></a></strong>
                                <div class="row-actions">
                                    <span class="view"><a href="<?php echo $view_url; ?>">View</a> | </span>
                                    <span class="delete"><a href="<?php echo wp_nonce_url('admin.php?page=project-inquiries&action=delete&id=' . $sub->id, 'delete_inquiry_' . $sub->id); ?>" class="submitdelete" onclick="return confirm('Are you sure?')">Delete</a></span>
                                </div>
                            </td>
                            <td><a href="mailto:<?php echo esc_attr($sub->email); ?>"><?php echo esc_html($sub->email); ?></a></td>
                            <td><?php echo esc_html($sub->phone); ?></td>
                            <td>
                                <?php if ($is_new) : ?>
                                    <span class="dashicons dashicons-marker" style="color: #ca4a1f;"></span> <strong>New</strong>
                                <?php else : ?>
                                    <span class="dashicons dashicons-yes" style="color: #999;"></span> Read
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(date('M j, Y', strtotime($sub->submitted_at))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <style>
        .updated {
            background-color: #f0f6fc !important;
        }

        .updated th,
        .updated td {
            box-shadow: inset 2px 0 0 #3571b1;
        }
    </style>
<?php
}

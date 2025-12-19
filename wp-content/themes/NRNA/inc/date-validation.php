<?php
// Server-side validation for mandatory date fields
function nrna_validate_post_dates($data, $postarr)
{
    // Check for Projects
    if ($data['post_type'] === 'projects' && $data['post_status'] === 'publish') {
        if (empty($_POST['project_date'])) {
            $data['post_status'] = 'draft';
            add_filter('redirect_post_location', function ($location) {
                return add_query_arg('nrna_error', 'missing_project_date', $location);
            });
        }
    }

    // Check for Events
    if ($data['post_type'] === 'events' && $data['post_status'] === 'publish') {
        if (empty($_POST['event_start_date']) || empty($_POST['event_end_date'])) {
            $data['post_status'] = 'draft';
            add_filter('redirect_post_location', function ($location) {
                return add_query_arg('nrna_error', 'missing_event_dates', $location);
            });
        }
    }

    // Check for Regional Meetings
    if ($data['post_type'] === 'regional_meetings' && $data['post_status'] === 'publish') {
        if (empty($_POST['rm_start_date']) || empty($_POST['rm_end_date'])) {
            $data['post_status'] = 'draft';
            add_filter('redirect_post_location', function ($location) {
                return add_query_arg('nrna_error', 'missing_rm_dates', $location);
            });
        }
    }

    return $data;
}
add_filter('wp_insert_post_data', 'nrna_validate_post_dates', 10, 2);

// Admin notices for validation errors
function nrna_validation_admin_notices()
{
    if (isset($_GET['nrna_error'])) {
        $message = '';
        if ($_GET['nrna_error'] === 'missing_project_date') {
            $message = 'Project could not be published. <strong>Date is required.</strong>';
        } elseif ($_GET['nrna_error'] === 'missing_event_dates') {
            $message = 'Event could not be published. <strong>Start Date and End Date are required.</strong>';
        } elseif ($_GET['nrna_error'] === 'missing_rm_dates') {
            $message = 'Regional Meeting could not be published. <strong>Start Date and End Date are required.</strong>';
        }

        if ($message) {
            echo '<div class="notice notice-error is-dismissible"><p>' . $message . '</p></div>';
        }
    }
}
add_action('admin_notices', 'nrna_validation_admin_notices');

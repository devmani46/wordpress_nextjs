<?php

/**
 * NRNA Theme Includes
 */

// Load enqueue meta box
require_once 'enqueue-scripts.php';

// Load menu-api meta box
require_once 'menu-api.php';

// Load date validation
require_once 'date-validation.php';

// Load home meta box
require_once 'home-meta-box.php';

// Load who-we-are meta box
require_once 'who-we-are-meta-box.php';

// Load organizational structure meta box
require_once 'organizational-structure-meta-box.php';

// Load contact meta box
require_once 'contact-meta-box.php';

// Load nrna discount meta box
require_once 'nrna-discount-meta-box.php';

// Load our ncc page meta box
require_once 'our-ncc-page-meta-box.php';

// Load executive committee page meta box
require_once 'executive-committee-page-meta-box.php';

// Load projects includes
require_once 'projects/index.php';

// Load events includes
require_once 'events/index.php';

// Load faqs includes
require_once 'faqs/index.php';

// Load notices includes
require_once 'notices/index.php';

// Load activities includes
require_once 'activities/index.php';

// Load resources includes
require_once 'resources/index.php';

// Load reports & publications includes
require_once 'reports-publications/index.php';

// Load news includes
require_once 'news/index.php';

// Load executive committee includes
require_once 'executive-committee/index.php';

// Load galleries includes
require_once 'galleries/index.php';

// Load videos includes
require_once 'videos/index.php';

// Load committees, taskforces & subcommittees includes
require_once 'committees-taskforces-subcommittees/index.php';

// Load our ncc includes
require_once 'our-ncc/index.php';

// Load regional meetings includes
require_once 'regional-meetings/index.php';

// Load privacy policy meta box
require_once 'privacy-policy-meta-box.php';

// Load terms and conditions meta box
require_once 'terms-and-conditions-meta-box.php';

// Load forms
// Load Contact Us
require_once $inc . 'contact-us.php';

// Load Subscribe
require_once $inc . 'subscribe.php';

// Load Event Registrations
require_once $inc . 'event-registrations.php';

// Load Regional Meeting Registrations
require_once $inc . 'regional-meeting-registrations.php';

// Load Search API
require_once $inc . 'search-api.php';

// Load Project Inquiries
require_once $inc . 'project-inquiries.php';


// Auto-update slug when title updates
function nrna_auto_update_slug($data, $postarr)
{
    if (!in_array($data['post_status'], ['draft', 'pending', 'auto-draft'])) {
        $data['post_name'] = sanitize_title($data['post_title']);
    }
    return $data;
}
// add_filter('wp_insert_post_data', 'nrna_auto_update_slug', 99, 2);

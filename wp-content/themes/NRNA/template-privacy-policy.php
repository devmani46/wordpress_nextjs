<?php

/**
 * Template Name: Privacy Policy
 */

get_header();

// Get policy items from page meta
$policy_items = get_post_meta(get_the_ID(), 'policy_items', true);
if (!is_array($policy_items)) $policy_items = [];

?>

<main class="privacy-policy-page">
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php if (!empty($policy_items)): ?>
            <div class="privacy-policy-list">
                <?php foreach ($policy_items as $policy): ?>
                    <div class="privacy-policy-item">
                        <h2><?php echo esc_html($policy['title'] ?? ''); ?></h2>
                        <div class="privacy-policy-description">
                            <?php echo wp_kses_post($policy['description'] ?? ''); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><?php _e('No privacy policies found.', 'nrna'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
<?php
/**
 * Template Name: Privacy Policy
 */

get_header();

$privacy_policies = get_posts([
    'post_type' => 'privacy-policy',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

?>

<main class="privacy-policy-page">
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php if (!empty($privacy_policies)): ?>
            <div class="privacy-policy-list">
                <?php foreach ($privacy_policies as $policy): ?>
                    <div class="privacy-policy-item">
                        <h2><?php echo esc_html($policy->post_title); ?></h2>
                        <div class="privacy-policy-description">
                            <?php echo wp_kses_post(get_post_meta($policy->ID, 'description', true)); ?>
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

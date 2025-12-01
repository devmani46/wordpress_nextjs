<?php

/**
 * Template Name: Terms and Conditions
 */

get_header();

// Get terms items from page meta
$terms_items = get_post_meta(get_the_ID(), 'terms_items', true);
if (!is_array($terms_items)) $terms_items = [];

?>

<main class="terms-and-conditions-page">
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php if (!empty($terms_items)): ?>
            <div class="terms-and-conditions-list">
                <?php foreach ($terms_items as $term): ?>
                    <div class="terms-and-conditions-item">
                        <h2><?php echo esc_html($term['title'] ?? ''); ?></h2>
                        <div class="terms-and-conditions-description">
                            <?php echo wp_kses_post($term['description'] ?? ''); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><?php _e('No terms and conditions found.', 'nrna'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
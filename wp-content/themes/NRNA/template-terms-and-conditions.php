<?php
/**
 * Template Name: Terms and Conditions
 */

get_header();

$terms_and_conditions = get_posts([
    'post_type' => 'terms-and-conditions',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

?>

<main class="terms-and-conditions-page">
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php if (!empty($terms_and_conditions)): ?>
            <div class="terms-and-conditions-list">
                <?php foreach ($terms_and_conditions as $term): ?>
                    <div class="terms-and-conditions-item">
                        <h2><?php echo esc_html($term->post_title); ?></h2>
                        <div class="terms-and-conditions-description">
                            <?php echo wp_kses_post(get_post_meta($term->ID, 'description', true)); ?>
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

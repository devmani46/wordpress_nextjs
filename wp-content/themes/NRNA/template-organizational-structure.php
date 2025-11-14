<?php
/**
 * Template Name: Organizational Structure Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="organizational-structure">
    <?php
    $title = get_post_meta(get_the_ID(), 'organizational_structure_title', true);
    $image = get_post_meta(get_the_ID(), 'organizational_structure_image', true);
    $stat_title = get_post_meta(get_the_ID(), 'organizational_structure_stat_title', true);
    $stat_description = get_post_meta(get_the_ID(), 'organizational_structure_stat_description', true);
    ?>
    <div><?php echo wp_kses_post($title); ?></div>
    <?php if ($image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($image, 'full')); ?>" alt="">
    <?php endif; ?>
    <?php if ($stat_title) : ?>
        <h3><?php echo esc_html($stat_title); ?></h3>
    <?php endif; ?>
    <?php if ($stat_description) : ?>
        <p><?php echo esc_html($stat_description); ?></p>
    <?php endif; ?>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>

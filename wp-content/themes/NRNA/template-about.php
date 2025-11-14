<?php
/**
 * Template Name: About Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'who_we_are_hero_title', true);
    $hero_description = get_post_meta(get_the_ID(), 'who_we_are_hero_description', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p><?php echo wp_kses_post($hero_description); ?></p>
</section>

<section class="slider">
    <?php
    $slider_items = get_post_meta(get_the_ID(), 'who_we_are_slider_items', true);
    if (is_array($slider_items)) :
        foreach ($slider_items as $item) :
            ?>
            <div class="slider-item">
                <h2><?php echo esc_html($item['title'] ?? ''); ?></h2>
                <p><?php echo wp_kses_post($item['description'] ?? ''); ?></p>
                <?php if ($item['image']) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($item['image'], 'full')); ?>" alt="">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<section class="vision">
    <?php
    $vision_title = get_post_meta(get_the_ID(), 'who_we_are_vision_title', true);
    $vision_description = get_post_meta(get_the_ID(), 'who_we_are_vision_description', true);
    $vision_image = get_post_meta(get_the_ID(), 'who_we_are_vision_image', true);
    ?>
    <h2><?php echo esc_html($vision_title); ?></h2>
    <p><?php echo wp_kses_post($vision_description); ?></p>
    <?php if ($vision_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($vision_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<section class="goals">
    <?php
    $goals_title = get_post_meta(get_the_ID(), 'who_we_are_goals_title', true);
    $goals_description = get_post_meta(get_the_ID(), 'who_we_are_goals_description', true);
    $goals_image = get_post_meta(get_the_ID(), 'who_we_are_goals_image', true);
    ?>
    <h2><?php echo esc_html($goals_title); ?></h2>
    <p><?php echo wp_kses_post($goals_description); ?></p>
    <?php if ($goals_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($goals_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<section class="certificate">
    <?php
    $certificate_title = get_post_meta(get_the_ID(), 'who_we_are_certificate_title', true);
    $certificate_description = get_post_meta(get_the_ID(), 'who_we_are_certificate_description', true);
    $certificate_image = get_post_meta(get_the_ID(), 'who_we_are_certificate_image', true);
    ?>
    <h2><?php echo esc_html($certificate_title); ?></h2>
    <p><?php echo wp_kses_post($certificate_description); ?></p>
    <?php if ($certificate_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($certificate_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<section class="message">
    <?php
    $message_title = get_post_meta(get_the_ID(), 'who_we_are_message_title', true);
    $message_description = get_post_meta(get_the_ID(), 'who_we_are_message_description', true);
    $message_image = get_post_meta(get_the_ID(), 'who_we_are_message_image', true);
    $representative_name = get_post_meta(get_the_ID(), 'who_we_are_message_representative_name', true);
    $representative_role = get_post_meta(get_the_ID(), 'who_we_are_message_representative_role', true);
    ?>
    <h2><?php echo esc_html($message_title); ?></h2>
    <p><?php echo wp_kses_post($message_description); ?></p>
    <?php if ($message_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($message_image, 'full')); ?>" alt="">
    <?php endif; ?>
    <p><strong><?php echo esc_html($representative_name); ?></strong></p>
    <p><?php echo esc_html($representative_role); ?></p>
</section>

<section class="team">
    <?php
    $team_title = get_post_meta(get_the_ID(), 'who_we_are_team_title', true);
    $team_description = get_post_meta(get_the_ID(), 'who_we_are_team_description', true);
    $team_cta_link = get_post_meta(get_the_ID(), 'who_we_are_team_cta_link', true);
    $team_cta_title = get_post_meta(get_the_ID(), 'who_we_are_team_cta_title', true);
    $team_image = get_post_meta(get_the_ID(), 'who_we_are_team_image', true);
    ?>
    <h2><?php echo esc_html($team_title); ?></h2>
    <p><?php echo wp_kses_post($team_description); ?></p>
    <?php if ($team_cta_title && $team_cta_link) : ?>
        <a href="<?php echo esc_url($team_cta_link); ?>"><?php echo esc_html($team_cta_title); ?></a>
    <?php endif; ?>
    <?php if ($team_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($team_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>

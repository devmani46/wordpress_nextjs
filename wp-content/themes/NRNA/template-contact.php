<?php
/**
 * Template Name: Contact Us Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'hero_title', true);
    $hero_email = get_post_meta(get_the_ID(), 'hero_email', true);
    $hero_phone_numbers = get_post_meta(get_the_ID(), 'hero_phone_numbers', true);
    $hero_location = get_post_meta(get_the_ID(), 'hero_location', true);
    $hero_cta_link = get_post_meta(get_the_ID(), 'hero_cta_link', true);
    $hero_cta_title = get_post_meta(get_the_ID(), 'hero_cta_title', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p><?php echo esc_html($hero_email); ?></p>
    <?php if (is_array($hero_phone_numbers)) : ?>
        <div class="phone-numbers">
            <?php foreach ($hero_phone_numbers as $phone) : ?>
                <p><?php echo esc_html($phone); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <p><?php echo esc_html($hero_location); ?></p>
    <?php if ($hero_cta_title && $hero_cta_link) : ?>
        <a href="<?php echo esc_url($hero_cta_link); ?>"><?php echo esc_html($hero_cta_title); ?></a>
    <?php endif; ?>
</section>

<section class="information">
    <?php
    $information_title = get_post_meta(get_the_ID(), 'information_title', true);
    $information_descriptions = get_post_meta(get_the_ID(), 'information_descriptions', true);
    ?>
    <h2><?php echo esc_html($information_title); ?></h2>
    <?php if (is_array($information_descriptions)) : ?>
        <div class="descriptions">
            <?php foreach ($information_descriptions as $desc) : ?>
                <p><?php echo wp_kses_post($desc); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="map">
    <?php
    $map_embed = get_post_meta(get_the_ID(), 'map_embed', true);
    ?>
    <div class="map-container">
        <?php echo $map_embed; ?>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>

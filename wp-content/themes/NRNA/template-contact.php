<?php
/**
 * Template Name: Contact Us Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'hero_title', true);
    $hero_description = get_post_meta(get_the_ID(), 'hero_description', true);
    $hero_email = get_post_meta(get_the_ID(), 'hero_email', true);
    $hero_phone_numbers = get_post_meta(get_the_ID(), 'hero_phone_numbers', true);
    $hero_location = get_post_meta(get_the_ID(), 'hero_location', true);
    $hero_cta_link = get_post_meta(get_the_ID(), 'hero_cta_link', true);
    $hero_cta_title = get_post_meta(get_the_ID(), 'hero_cta_title', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <div><?php echo wp_kses_post($hero_description); ?></div>
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
    $information_descriptions = get_post_meta(get_the_ID(), 'information_descriptions', true);
    ?>
    <?php if (is_array($information_descriptions)) : ?>
        <div class="descriptions">
            <?php foreach ($information_descriptions as $info) : ?>
                <div class="info-item">
                    <h3><?php echo esc_html($info['title'] ?? ''); ?></h3>
                    <div><?php echo wp_kses_post($info['description'] ?? ''); ?></div>
                </div>
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

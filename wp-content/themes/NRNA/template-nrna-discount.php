<?php
/**
 * Template Name: NRNA Discount
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'discount_hero_title', true);
    $hero_description = get_post_meta(get_the_ID(), 'discount_hero_description', true);
    $hero_cta1_link = get_post_meta(get_the_ID(), 'discount_hero_cta1_link', true);
    $hero_cta1_title = get_post_meta(get_the_ID(), 'discount_hero_cta1_title', true);
    $hero_cta2_link = get_post_meta(get_the_ID(), 'discount_hero_cta2_link', true);
    $hero_cta2_title = get_post_meta(get_the_ID(), 'discount_hero_cta2_title', true);
    $hero_images = [];
    for ($i = 1; $i <= 3; $i++) {
        $image_id = get_post_meta(get_the_ID(), 'discount_hero_image' . $i, true);
        if ($image_id) {
            $hero_images[] = wp_get_attachment_image_url($image_id, 'full');
        }
    }
    $hero_banner_title = get_post_meta(get_the_ID(), 'discount_hero_banner_title', true);
    $hero_banner_description = get_post_meta(get_the_ID(), 'discount_hero_banner_description', true);
    $hero_stats = get_post_meta(get_the_ID(), 'discount_hero_stats', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p><?php echo wp_kses_post($hero_description); ?></p>
    <?php if ($hero_cta1_title && $hero_cta1_link) : ?>
        <a href="<?php echo esc_url($hero_cta1_link); ?>"><?php echo esc_html($hero_cta1_title); ?></a>
    <?php endif; ?>
    <?php if ($hero_cta2_title && $hero_cta2_link) : ?>
        <a href="<?php echo esc_url($hero_cta2_link); ?>"><?php echo esc_html($hero_cta2_title); ?></a>
    <?php endif; ?>
    <?php if (!empty($hero_images)) : ?>
        <div class="hero-images">
            <?php foreach ($hero_images as $image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="hero-banner">
        <h2><?php echo esc_html($hero_banner_title); ?></h2>
        <p><?php echo wp_kses_post($hero_banner_description); ?></p>
    </div>
    <?php if (is_array($hero_stats)) : ?>
        <div class="hero-stats">
            <?php foreach ($hero_stats as $stat) : ?>
                <div class="stat">
                    <h3><?php echo esc_html($stat['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($stat['description'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="how-it-works">
    <?php
    $how_title = get_post_meta(get_the_ID(), 'discount_how_title', true);
    $how_description = get_post_meta(get_the_ID(), 'discount_how_description', true);
    $how_banner = get_post_meta(get_the_ID(), 'discount_how_banner', true);
    ?>
    <h2><?php echo esc_html($how_title); ?></h2>
    <p><?php echo wp_kses_post($how_description); ?></p>
    <?php if (is_array($how_banner)) : ?>
        <div class="how-banner">
            <?php foreach ($how_banner as $item) : ?>
                <div class="banner-item">
                    <span class="label"><?php echo esc_html($item['label'] ?? ''); ?></span>
                    <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($item['description'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="browse-partners">
    <?php
    $partners = get_post_meta(get_the_ID(), 'discount_partners', true);
    ?>
    <?php if (is_array($partners)) : ?>
        <div class="partners-list">
            <?php foreach ($partners as $partner) : ?>
                <div class="partner-item <?php echo esc_attr($partner['partner_type'] ?? 'unverified'); ?>">
                    <div class="partner-header">
                        <span class="category"><?php echo esc_html($partner['category'] ?? ''); ?></span>
                        <h3><?php echo esc_html($partner['name'] ?? ''); ?></h3>
                    </div>
                    <p><?php echo wp_kses_post($partner['description'] ?? ''); ?></p>
                    <div class="offer"><?php echo esc_html($partner['offer_text'] ?? ''); ?></div>
                    <?php if ($partner['photo']) : ?>
                        <img src="<?php echo esc_url(wp_get_attachment_image_url($partner['photo'], 'full')); ?>" alt="">
                    <?php endif; ?>
                    <?php if ($partner['cta_title'] && $partner['cta_link']) : ?>
                        <a href="<?php echo esc_url($partner['cta_link']); ?>"><?php echo esc_html($partner['cta_title']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="join-nrna">
    <?php
    $join_title = get_post_meta(get_the_ID(), 'discount_join_title', true);
    $join_description = get_post_meta(get_the_ID(), 'discount_join_description', true);
    $join_points = get_post_meta(get_the_ID(), 'discount_join_points', true);
    $join_cta_link = get_post_meta(get_the_ID(), 'discount_join_cta_link', true);
    $join_cta_title = get_post_meta(get_the_ID(), 'discount_join_cta_title', true);
    $join_stats = get_post_meta(get_the_ID(), 'discount_join_stats', true);
    ?>
    <h2><?php echo esc_html($join_title); ?></h2>
    <p><?php echo wp_kses_post($join_description); ?></p>
    <?php if (is_array($join_points)) : ?>
        <ul class="join-points">
            <?php foreach ($join_points as $point) : ?>
                <li><?php echo esc_html($point['title'] ?? ''); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($join_cta_title && $join_cta_link) : ?>
        <a href="<?php echo esc_url($join_cta_link); ?>"><?php echo esc_html($join_cta_title); ?></a>
    <?php endif; ?>
    <?php if (is_array($join_stats)) : ?>
        <div class="join-stats">
            <?php foreach ($join_stats as $stat) : ?>
                <div class="stat">
                    <h3><?php echo esc_html($stat['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($stat['description'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>

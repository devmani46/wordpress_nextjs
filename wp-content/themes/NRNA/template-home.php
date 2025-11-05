<?php
/**
 * Template Name: Home Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'hero_title', true);
    $hero_description = get_post_meta(get_the_ID(), 'hero_description', true);
    $hero_cta_link = get_post_meta(get_the_ID(), 'hero_cta_link', true);
    $hero_cta_title = get_post_meta(get_the_ID(), 'hero_cta_title', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p><?php echo wp_kses_post($hero_description); ?></p>
    <?php if ($hero_cta_title && $hero_cta_link) : ?>
        <a href="<?php echo esc_url($hero_cta_link); ?>"><?php echo esc_html($hero_cta_title); ?></a>
    <?php endif; ?>
</section>

<?php endwhile; ?>

<section class="slider">
    <?php
    $slider_items = get_post_meta(get_the_ID(), 'slider_items', true);
    if (is_array($slider_items)) :
        foreach ($slider_items as $item) :
            ?>
            <div class="slider-item">
                <h2><?php echo esc_html($item['title'] ?? ''); ?></h2>
                <?php if ($item['image']) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($item['image'], 'full')); ?>" alt="">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<section class="about-us">
    <?php
    $about_title = get_post_meta(get_the_ID(), 'about_title', true);
    $about_image = get_post_meta(get_the_ID(), 'about_image', true);
    $about_stats = get_post_meta(get_the_ID(), 'about_stats', true);
    ?>
    <h2><?php echo esc_html($about_title); ?></h2>
    <?php if ($about_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($about_image, 'full')); ?>" alt="">
    <?php endif; ?>
    <?php if (is_array($about_stats)) : ?>
        <div class="stats">
            <?php foreach ($about_stats as $stat) : ?>
                <div class="stat">
                    <h3><?php echo esc_html($stat['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($stat['description'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="why-choose-us">
    <?php
    $why_title = get_post_meta(get_the_ID(), 'why_title', true);
    $why_description = get_post_meta(get_the_ID(), 'why_description', true);
    $why_features = get_post_meta(get_the_ID(), 'why_features', true);
    ?>
    <h2><?php echo esc_html($why_title); ?></h2>
    <p><?php echo wp_kses_post($why_description); ?></p>
    <?php if (is_array($why_features)) : ?>
        <div class="features">
            <?php foreach ($why_features as $feature) : ?>
                <div class="feature">
                    <h3><?php echo esc_html($feature['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($feature['description'] ?? ''); ?></p>
                    <?php if ($feature['image']) : ?>
                        <img src="<?php echo esc_url(wp_get_attachment_image_url($feature['image'], 'full')); ?>" alt="">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="get-involved">
    <?php
    $involved_title = get_post_meta(get_the_ID(), 'involved_title', true);
    $involved_description = get_post_meta(get_the_ID(), 'involved_description', true);
    $involved_actions = get_post_meta(get_the_ID(), 'involved_actions', true);
    ?>
    <h2><?php echo esc_html($involved_title); ?></h2>
    <p><?php echo wp_kses_post($involved_description); ?></p>
    <?php if (is_array($involved_actions)) : ?>
        <div class="actions">
            <?php foreach ($involved_actions as $action) : ?>
                <div class="action">
                    <h3><?php echo esc_html($action['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($action['description'] ?? ''); ?></p>
                    <?php if ($action['cta_title'] && $action['cta_link']) : ?>
                        <a href="<?php echo esc_url($action['cta_link']); ?>"><?php echo esc_html($action['cta_title']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="stay-updated">
    <?php
    $stay_updated_title = get_post_meta(get_the_ID(), 'stay_updated_title', true);
    $stay_updated_description = get_post_meta(get_the_ID(), 'stay_updated_description', true);
    ?>
    <h2><?php echo esc_html($stay_updated_title); ?></h2>
    <p><?php echo wp_kses_post($stay_updated_description); ?></p>
</section>

<section class="latest-news">
    <?php
    $latest_news_title = get_post_meta(get_the_ID(), 'latest_news_title', true);
    $latest_news_description = get_post_meta(get_the_ID(), 'latest_news_description', true);
    ?>
    <h2><?php echo esc_html($latest_news_title); ?></h2>
    <p><?php echo wp_kses_post($latest_news_description); ?></p>
</section>

<section class="our-initiatives">
    <?php
    $initiatives_title = get_post_meta(get_the_ID(), 'initiatives_title', true);
    $initiatives_description = get_post_meta(get_the_ID(), 'initiatives_description', true);
    $initiatives_items = get_post_meta(get_the_ID(), 'initiatives_items', true);
    ?>
    <h2><?php echo esc_html($initiatives_title); ?></h2>
    <p><?php echo wp_kses_post($initiatives_description); ?></p>
    <?php if (is_array($initiatives_items)) : ?>
        <div class="initiatives">
            <?php foreach ($initiatives_items as $item) : ?>
                <div class="initiative">
                    <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($item['description'] ?? ''); ?></p>
                    <?php if ($item['image']) : ?>
                        <img src="<?php echo esc_url(wp_get_attachment_image_url($item['image'], 'full')); ?>" alt="">
                    <?php endif; ?>
                    <?php if ($item['button_text'] && $item['button_link']) : ?>
                        <a href="<?php echo esc_url($item['button_link']); ?>"><?php echo esc_html($item['button_text']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="join-the-journey">
    <?php
    $journey_title = get_post_meta(get_the_ID(), 'journey_title', true);
    $journey_description = get_post_meta(get_the_ID(), 'journey_description', true);
    $journey_image = get_post_meta(get_the_ID(), 'journey_image', true);
    $journey_button_text = get_post_meta(get_the_ID(), 'journey_button_text', true);
    $journey_button_link = get_post_meta(get_the_ID(), 'journey_button_link', true);
    ?>
    <h2><?php echo esc_html($journey_title); ?></h2>
    <p><?php echo wp_kses_post($journey_description); ?></p>
    <?php if ($journey_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($journey_image, 'full')); ?>" alt="">
    <?php endif; ?>
    <?php if ($journey_button_text && $journey_button_link) : ?>
        <a href="<?php echo esc_url($journey_button_link); ?>"><?php echo esc_html($journey_button_text); ?></a>
    <?php endif; ?>
</section>

<?php get_footer(); ?>

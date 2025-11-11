<?php
/**
 * Template Name: Committees, Taskforces & Subcommittees Page
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero">
    <?php
    $hero_title = get_post_meta(get_the_ID(), 'committees_hero_title', true);
    $hero_description = get_post_meta(get_the_ID(), 'committees_hero_description', true);
    $hero_images = get_post_meta(get_the_ID(), 'committees_hero_images', true);
    ?>
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p><?php echo wp_kses_post($hero_description); ?></p>
    <?php if (is_array($hero_images)) : ?>
        <div class="hero-images">
            <?php foreach ($hero_images as $image) : ?>
                <img src="<?php echo esc_url(wp_get_attachment_image_url($image['image'], 'full')); ?>" alt="">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="why">
    <?php
    $why_title = get_post_meta(get_the_ID(), 'committees_why_title', true);
    $why_description = get_post_meta(get_the_ID(), 'committees_why_description', true);
    $why_image = get_post_meta(get_the_ID(), 'committees_why_image', true);
    ?>
    <h2><?php echo esc_html($why_title); ?></h2>
    <p><?php echo wp_kses_post($why_description); ?></p>
    <?php if ($why_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($why_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<section class="how">
    <?php
    $how_title = get_post_meta(get_the_ID(), 'committees_how_title', true);
    $how_description = get_post_meta(get_the_ID(), 'committees_how_description', true);
    $how_image = get_post_meta(get_the_ID(), 'committees_how_image', true);
    ?>
    <h2><?php echo esc_html($how_title); ?></h2>
    <p><?php echo wp_kses_post($how_description); ?></p>
    <?php if ($how_image) : ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($how_image, 'full')); ?>" alt="">
    <?php endif; ?>
</section>

<section class="banner1">
    <?php
    $banner1_title = get_post_meta(get_the_ID(), 'committees_banner1_title', true);
    $banner1_description = get_post_meta(get_the_ID(), 'committees_banner1_description', true);
    $banner1_cta_link = get_post_meta(get_the_ID(), 'committees_banner1_cta_link', true);
    $banner1_cta_title = get_post_meta(get_the_ID(), 'committees_banner1_cta_title', true);
    $banner1_stats = get_post_meta(get_the_ID(), 'committees_banner1_stats', true);
    ?>
    <h2><?php echo esc_html($banner1_title); ?></h2>
    <p><?php echo wp_kses_post($banner1_description); ?></p>
    <?php if (is_array($banner1_stats)) : ?>
        <div class="stats">
            <?php foreach ($banner1_stats as $stat) : ?>
                <div class="stat">
                    <h3><?php echo esc_html($stat['title'] ?? ''); ?></h3>
                    <p><?php echo wp_kses_post($stat['description'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($banner1_cta_title && $banner1_cta_link) : ?>
        <a href="<?php echo esc_url($banner1_cta_link); ?>"><?php echo esc_html($banner1_cta_title); ?></a>
    <?php endif; ?>
</section>

<section class="teams">
    <?php
    $teams_title = get_post_meta(get_the_ID(), 'committees_teams_title', true);
    $teams_members = get_post_meta(get_the_ID(), 'committees_teams_members', true);
    ?>
    <h2><?php echo esc_html($teams_title); ?></h2>
    <?php if (is_array($teams_members)) : ?>
        <div class="teams-table">
            <table>
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Name</th>
                        <th>Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teams_members as $member) : ?>
                        <tr>
                            <td><?php echo esc_html($member['project'] ?? ''); ?></td>
                            <td><?php echo esc_html($member['name'] ?? ''); ?></td>
                            <td><a href="mailto:<?php echo esc_attr($member['email'] ?? ''); ?>"><?php echo esc_html($member['email'] ?? ''); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<section class="banner2">
    <?php
    $banner2_title = get_post_meta(get_the_ID(), 'committees_banner2_title', true);
    $banner2_description = get_post_meta(get_the_ID(), 'committees_banner2_description', true);
    $banner2_cta_link = get_post_meta(get_the_ID(), 'committees_banner2_cta_link', true);
    $banner2_cta_title = get_post_meta(get_the_ID(), 'committees_banner2_cta_title', true);
    ?>
    <h2><?php echo esc_html($banner2_title); ?></h2>
    <p><?php echo wp_kses_post($banner2_description); ?></p>
    <?php if ($banner2_cta_title && $banner2_cta_link) : ?>
        <a href="<?php echo esc_url($banner2_cta_link); ?>"><?php echo esc_html($banner2_cta_title); ?></a>
    <?php endif; ?>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>

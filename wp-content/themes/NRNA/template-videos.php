<?php
/* Template Name: Videos */
get_header();

$args_videos = [
    'post_type' => 'videos',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_videos = new WP_Query($args_videos);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Videos</h1>

    <?php if ($query_videos->have_posts()) : ?>
      <div class="videos-list">
        <?php while ($query_videos->have_posts()) : $query_videos->the_post(); ?>
          <div class="video-item border border-gray-300 rounded p-6 mb-6">
            <div class="video-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php
            $youtube_url = get_post_meta(get_the_ID(), 'video_youtube_url', true);
            if (!empty($youtube_url)) :
            ?>
              <div class="video-embed mb-4">
                <div class="aspect-w-16 aspect-h-9">
                  <?php echo wp_oembed_get($youtube_url, ['width' => 560, 'height' => 315]); ?>
                </div>
              </div>
            <?php else : ?>
              <div class="video-embed mb-4">
                <p>No video URL provided.</p>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No videos found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

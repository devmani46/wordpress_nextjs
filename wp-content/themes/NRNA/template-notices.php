<?php
/* Template Name: Notices */
get_header();

$args_notices = [
    'post_type' => 'notices',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_notices = new WP_Query($args_notices);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Notices</h1>

    <?php if ($query_notices->have_posts()) : ?>
      <div class="notices-list">
        <?php while ($query_notices->have_posts()) : $query_notices->the_post(); ?>
          <div class="notice-item border border-gray-300 rounded p-6 mb-6">
            <div class="notice-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php if (has_post_thumbnail()) : ?>
              <div class="notice-image mb-4">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded']); ?>
              </div>
            <?php endif; ?>

            <div class="notice-content mb-4">
              <?php the_content(); ?>
            </div>

            <?php
            $video_url = get_post_meta(get_the_ID(), 'notice_video_url', true);
            if ($video_url) :
              // Extract YouTube video ID
              $video_id = '';
              if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                $video_id = $matches[1];
              }
              if ($video_id) :
            ?>
              <div class="notice-video mb-4">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
            <?php endif; ?>
            <?php endif; ?>

            <?php
            $related_notices = get_post_meta(get_the_ID(), 'notice_related', false);
            if (!empty($related_notices)) :
            ?>
              <div class="notice-related">
                <h3 class="text-lg font-semibold mb-2">Related Notices:</h3>
                <ul class="list-disc list-inside">
                  <?php foreach ($related_notices as $rel_id) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($rel_id)); ?>" class="text-blue-600 hover:underline"><?php echo esc_html(get_the_title($rel_id)); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No notices found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

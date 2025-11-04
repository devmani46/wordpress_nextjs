<?php
/* Template Name: Activities */
get_header();

$args_activities = [
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_activities = new WP_Query($args_activities);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Activities</h1>

    <?php if ($query_activities->have_posts()) : ?>
      <div class="activities-list">
        <?php while ($query_activities->have_posts()) : $query_activities->the_post(); ?>
          <div class="activity-item border border-gray-300 rounded p-6 mb-6">
            <div class="activity-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php if (has_post_thumbnail()) : ?>
              <div class="activity-image mb-4">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded']); ?>
              </div>
            <?php endif; ?>

            <div class="activity-content mb-4">
              <?php the_content(); ?>
            </div>

            <?php
            $activity_photos = get_post_meta(get_the_ID(), 'activity_photos', true);
            if (!empty($activity_photos) && is_array($activity_photos)) :
            ?>
              <div class="activity-photos mb-4">
                <h3 class="text-lg font-semibold mb-2">Activity Photos:</h3>
                <div class="photos-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <?php foreach ($activity_photos as $photo) : ?>
                    <div class="photo-item">
                      <img src="<?php echo esc_url(wp_get_attachment_image_url($photo['image_id'], 'medium')); ?>" alt="<?php echo esc_attr($photo['title']); ?>" class="w-full h-auto rounded">
                      <?php if (!empty($photo['title'])) : ?>
                        <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($photo['title']); ?></p>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php
            $related_activities = get_post_meta(get_the_ID(), 'activity_related_activities', false);
            if (!empty($related_activities)) :
            ?>
              <div class="activity-related-activities">
                <h3 class="text-lg font-semibold mb-2">Related Activities:</h3>
                <ul class="list-disc list-inside">
                  <?php foreach ($related_activities as $rel_id) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($rel_id)); ?>" class="text-blue-600 hover:underline"><?php echo esc_html(get_the_title($rel_id)); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No activities found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

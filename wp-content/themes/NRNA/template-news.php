<?php
/* Template Name: News */
get_header();

$args_news = [
    'post_type' => 'news',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_news = new WP_Query($args_news);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Latest News</h1>

    <?php if ($query_news->have_posts()) : ?>
      <div class="news-list">
        <?php while ($query_news->have_posts()) : $query_news->the_post(); ?>
          <div class="news-item border border-gray-300 rounded p-6 mb-6">
            <div class="news-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php if (has_post_thumbnail()) : ?>
              <div class="news-image mb-4">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded']); ?>
              </div>
            <?php endif; ?>

            <div class="news-content mb-4">
              <?php echo wp_kses_post(get_post_meta(get_the_ID(), 'news_content', true)); ?>
            </div>

            <?php
            $related_news = get_post_meta(get_the_ID(), 'news_related', false);
            if (!empty($related_news)) :
            ?>
              <div class="news-related">
                <h3 class="text-lg font-semibold mb-2">Related News:</h3>
                <ul class="list-disc list-inside">
                  <?php foreach ($related_news as $rel_id) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($rel_id)); ?>" class="text-blue-600 hover:underline"><?php echo esc_html(get_the_title($rel_id)); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No news found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

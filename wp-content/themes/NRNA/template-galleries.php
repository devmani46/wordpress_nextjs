<?php
/* Template Name: Galleries */
get_header();

$args_galleries = [
    'post_type' => 'galleries',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_galleries = new WP_Query($args_galleries);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Galleries</h1>

    <?php if ($query_galleries->have_posts()) : ?>
      <div class="galleries-list">
        <?php while ($query_galleries->have_posts()) : $query_galleries->the_post(); ?>
          <div class="gallery-item border border-gray-300 rounded p-6 mb-6">
            <div class="gallery-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php
            $gallery_images = get_post_meta(get_the_ID(), 'images', true);
            if (!empty($gallery_images) && is_array($gallery_images)) :
            ?>
              <div class="gallery-images mb-4">
                <div class="images-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <?php foreach ($gallery_images as $image_id) : ?>
                    <div class="image-item">
                      <img src="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'large')); ?>" alt="" class="w-full h-auto rounded object-cover">
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php else : ?>
              <div class="gallery-images mb-4">
                <p>No images in this gallery.</p>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No galleries found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

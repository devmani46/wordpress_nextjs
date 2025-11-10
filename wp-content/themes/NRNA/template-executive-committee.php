<?php
/* Template Name: Executive Committee */
get_header();

$args_committee = [
    'post_type' => 'executive_committee',
    'posts_per_page' => -1,
    'meta_key' => 'hierarchy_order',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
];

$query_committee = new WP_Query($args_committee);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Executive Committee</h1>

    <?php if ($query_committee->have_posts()) : ?>
      <div class="executive-committee-list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($query_committee->have_posts()) : $query_committee->the_post(); ?>
          <div class="committee-member border border-gray-300 rounded p-6 text-center">
            <?php if (has_post_thumbnail()) : ?>
              <div class="member-photo mb-4">
                <?php the_post_thumbnail('medium', ['class' => 'w-32 h-32 rounded-full mx-auto object-cover']); ?>
              </div>
            <?php endif; ?>
            <div class="member-info">
              <h3 class="text-xl font-bold mb-2"><?php the_title(); ?></h3>
              <?php
              $role = get_post_meta(get_the_ID(), 'committee_role', true);
              if ($role) :
              ?>
                <p class="text-gray-600"><?php echo esc_html($role); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No committee members found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

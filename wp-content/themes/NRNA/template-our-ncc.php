<?php
/* Template Name: Our NCC */
get_header();

$args_ncc = [
    'post_type' => 'our_ncc',
    'posts_per_page' => -1,
    'meta_key' => 'ncc_name',
    'orderby' => 'meta_value',
    'order' => 'ASC',
];

$query_ncc = new WP_Query($args_ncc);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Our NCC</h1>

    <?php if ($query_ncc->have_posts()) : ?>
      <div class="our-ncc-list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($query_ncc->have_posts()) : $query_ncc->the_post(); ?>
          <div class="ncc-member border border-gray-300 rounded p-6 text-center">
            <?php if (has_post_thumbnail()) : ?>
              <div class="member-photo mb-4">
                <?php the_post_thumbnail('medium', ['class' => 'w-32 h-32 rounded-full mx-auto object-cover']); ?>
              </div>
            <?php endif; ?>
            <div class="member-info">
              <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_post_meta(get_the_ID(), 'ncc_name', true)); ?></h3>
              <?php
              $role = get_post_meta(get_the_ID(), 'ncc_role', true);
              $country = get_post_meta(get_the_ID(), 'ncc_country_name', true);
              $region = get_post_meta(get_the_ID(), 'ncc_region', true);
              $year = get_post_meta(get_the_ID(), 'ncc_year_of_tenure', true);
              $est_date = get_post_meta(get_the_ID(), 'ncc_est_date', true);
              $email = get_post_meta(get_the_ID(), 'ncc_official_email', true);
              $website = get_post_meta(get_the_ID(), 'ncc_website', true);
              ?>
              <?php if ($role) : ?>
                <p class="text-gray-600 mb-1"><strong>Role:</strong> <?php echo esc_html($role); ?></p>
              <?php endif; ?>
              <?php if ($country) : ?>
                <p class="text-gray-600 mb-1"><strong>Country:</strong> <?php echo esc_html($country); ?></p>
              <?php endif; ?>
              <?php if ($region) : ?>
                <p class="text-gray-600 mb-1"><strong>Region:</strong> <?php echo esc_html($region); ?></p>
              <?php endif; ?>
              <?php if ($year) : ?>
                <p class="text-gray-600 mb-1"><strong>Year of Tenure:</strong> <?php echo esc_html($year); ?></p>
              <?php endif; ?>
              <?php if ($est_date) : ?>
                <p class="text-gray-600 mb-1"><strong>Est. Date:</strong> <?php echo esc_html(date('Y-m-d', strtotime($est_date))); ?></p>
              <?php endif; ?>
              <?php if ($email) : ?>
                <p class="text-gray-600 mb-1"><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
              <?php endif; ?>
              <?php if ($website) : ?>
                <p class="text-gray-600 mb-1"><strong>Website:</strong> <a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No NCC members found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

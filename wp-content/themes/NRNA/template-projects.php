<?php
/* Template Name: Projects */
get_header();

$args_projects = [
    'post_type' => 'projects',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query_projects = new WP_Query($args_projects);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Projects</h1>

    <?php if ($query_projects->have_posts()) : ?>
      <div class="projects-list">
        <?php while ($query_projects->have_posts()) : $query_projects->the_post(); ?>
          <div class="project-item border border-gray-300 rounded p-6 mb-6">
            <div class="project-header mb-4">
              <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
              <?php
              $subtitle = get_post_meta(get_the_ID(), 'project_subtitle', true);
              if ($subtitle) :
              ?>
                <h3 class="text-xl text-gray-600 mb-2"><?php echo esc_html($subtitle); ?></h3>
              <?php endif; ?>
              <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
            </div>

            <?php if (has_post_thumbnail()) : ?>
              <div class="project-image mb-4">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded']); ?>
              </div>
            <?php endif; ?>

            <div class="project-content mb-4">
              <?php the_content(); ?>
            </div>

            <?php
            $objective = get_post_meta(get_the_ID(), 'project_objective', true);
            if ($objective) :
            ?>
              <div class="project-objective mb-4">
                <h3 class="text-lg font-semibold mb-2">Objective:</h3>
                <?php echo wpautop(wp_kses_post($objective)); ?>
              </div>
            <?php endif; ?>

            <?php
            $locations = get_post_meta(get_the_ID(), 'project_locations', true);
            if (!empty($locations) && is_array($locations)) :
            ?>
              <div class="project-locations mb-4">
                <h3 class="text-lg font-semibold mb-2">Where the Project is Being Conducted:</h3>
                <div class="locations-list">
                  <?php foreach ($locations as $location) : ?>
                    <div class="location-item border border-gray-200 rounded p-4 mb-3">
                      <h4 class="font-semibold"><?php echo esc_html($location['place'] ?? ''); ?> - <?php echo esc_html($location['date'] ?? ''); ?></h4>
                      <p><?php echo esc_html($location['description'] ?? ''); ?></p>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e('No projects found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

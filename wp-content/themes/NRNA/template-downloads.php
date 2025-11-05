<?php
/* Template Name: Downloads */
get_header();

$category_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';

$args_downloads = [
    'post_type' => 'downloads',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

if ($category_slug !== '') {
    $args_downloads['tax_query'] = [
        [
            'taxonomy' => 'download_category',
            'field'    => 'slug',
            'terms'    => $category_slug,
        ],
    ];
}

$query_downloads = new WP_Query($args_downloads);

// Get all categories for sidebar
$categories = get_terms([
    'taxonomy' => 'download_category',
    'hide_empty' => false,
]);
?>

<section class="section-py relative">
  <div class="container">
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-1/4 pr-8">
        <h3 class="text-xl font-bold mb-4">Categories</h3>
        <ul class="space-y-2">
          <li><a href="<?php echo esc_url(get_permalink()); ?>" class="text-blue-600 hover:underline <?php echo ($category_slug === '') ? 'font-bold' : ''; ?>">All Downloads</a></li>
          <?php foreach ($categories as $category) : ?>
            <li><a href="<?php echo esc_url(add_query_arg('category', $category->slug, get_permalink())); ?>" class="text-blue-600 hover:underline <?php echo ($category_slug === $category->slug) ? 'font-bold' : ''; ?>"><?php echo esc_html($category->name); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </aside>

      <!-- Main Content -->
      <main class="w-3/4">
        <h1 class="heading-2 mb-8">Downloads</h1>

        <?php if ($query_downloads->have_posts()) : ?>
          <div class="downloads-list">
            <?php while ($query_downloads->have_posts()) : $query_downloads->the_post(); ?>
              <div class="download-item border border-gray-300 rounded p-6 mb-6">
                <div class="download-header mb-4">
                  <h2 class="text-2xl font-bold mb-2"><?php the_title(); ?></h2>
                  <p class="text-gray-600"><?php echo esc_html(get_the_date()); ?></p>
                </div>

                <?php
                $download_files = get_post_meta(get_the_ID(), 'download_files', true);
                if (!empty($download_files) && is_array($download_files)) :
                ?>
                  <div class="download-files mb-4">
                    <h3 class="text-lg font-semibold mb-2">Download Files:</h3>
                    <div class="files-list">
                      <?php foreach ($download_files as $file_id) : ?>
                        <?php
                        $file_url = wp_get_attachment_url($file_id);
                        $file_name = basename($file_url);
                        ?>
                        <div class="file-item mb-2">
                          <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="text-blue-600 hover:underline">
                            <?php echo esc_html($file_name); ?>
                          </a>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php else : ?>
                  <div class="download-files mb-4">
                    <h3 class="text-lg font-semibold mb-2">Download Files:</h3>
                    <p>No files available</p>
                  </div>
                <?php endif; ?>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        <?php else : ?>
          <p><?php esc_html_e('No downloads found.', 'nrna'); ?></p>
        <?php endif; ?>
      </main>
    </div>
  </div>
</section>

<?php get_footer(); ?>

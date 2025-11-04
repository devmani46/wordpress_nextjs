<?php
/* Template Name: Faqs */
get_header();

$faqsType = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';

$args_faq = [
    'post_type' => 'faqs',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

if ($faqsType !== '') {
    $args_faq['meta_query'] = [
        [
            'key' => 'faqs_location',
            'value' => $faqsType,
            'compare' => 'LIKE',
        ],
    ];
}

$query_faq = new WP_Query($args_faq);
?>

<section class="section-py relative">
  <div class="container">
    <h1 class="heading-2 mb-8">Frequently Asked Questions</h1>

    <?php if ($query_faq->have_posts()) : $count = 0; ?>
      <?php while ($query_faq->have_posts()) : $query_faq->the_post(); $count++; ?>
        <div class="faq-item border border-gray-300 rounded p-4 mb-3">
          <button class="faq-button flex justify-between w-full text-left" onclick="toggleFAQ(this)">
            <span><strong>Q<?= esc_html($count); ?>.</strong> <?= esc_html(get_the_title()); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 28 28"><path d="M19.8327 11.6667L13.9993 17.5L8.16602 11.6667" stroke="#525775" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="faq-content mt-2 hidden">
            <?= wpautop( wp_kses_post( get_post_meta(get_the_ID(), 'answer', true) ) ); ?>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
      <p><?php esc_html_e('No FAQs found.', 'nrna'); ?></p>
    <?php endif; ?>
  </div>
</section>



<?php get_footer(); ?>

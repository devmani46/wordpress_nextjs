<?php
/**
 * Main index file for NRNA Theme
 */

get_header();
?>

<main id="site-content" role="main" class="container">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p><?php esc_html_e( 'No content found.', 'nrna' ); ?></p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>

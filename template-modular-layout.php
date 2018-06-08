<?php
/**
 * Template Name: Modular Layout
 * Template Post Type: post, page
 *
 * @since 1.0
 *
 * @package stereotypesteve
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

get_header();

?>

<main class="content-area" role="main">
	<!-- section -->
	<section>

		<?php while ( have_posts() ) : ?>

			<?php the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php SSteveCustomModules::the_content_blocks(); ?>

			</article>
			<!-- /article -->

		<?php endwhile; ?>

	</section>
	<!-- /section -->
</main>

<?php

get_footer();


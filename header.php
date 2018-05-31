<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Dyad
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ssteve' ); ?></a>
	<header id="masthead" class="site-header" role="banner">
		<?php if( SSteveUtilities::ssteve_get_option( 'show_top_thread', false ) ) : ?>
			<div class="top-thread">
				<div class="top-thread-spacer">&nbsp;</div>
				<?php if ( has_nav_menu( 'top-social' ) ) : ?>
					<?php
					wp_nav_menu( array(
						'theme_location'  => 'top-social',
						'container'       => 'div',
						'container_id'    => '',
						'container_class' => 'top-social-links social-links',
						'menu_id'         => '',
						'menu_class'      => 'top-social-links-items social-links-items',
						'depth'           => 1,
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
						'fallback_cb'     => '',
					) );
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="site-branding">
			<?php
			if ( function_exists( 'jetpack_the_site_logo' ) ) {
				jetpack_the_site_logo();
			}
			?>
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
			<p class="site-description"><?php bloginfo( 'description' ); ?></p>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'ssteve' ); ?></button>
			<?php wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id' => 'primary-menu',
				'container' => 'div',
				'container_class' => 'primary-menu',
			) ); ?>
		</nav>

	</header><!-- #masthead -->

	<div class="site-inner">

		<?php if ( is_home() || is_front_page() || ( is_single() && 'image' == get_post_format() ) ) : ?>
			<div class="featured-content">
				<?php get_template_part( 'template-parts/loop', 'banner' ); ?>
			</div>
		<?php endif; ?>

		<div id="content" class="site-content">

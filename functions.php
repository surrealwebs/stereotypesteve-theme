<?php
/**
 * Theme functions.
 *
 * @package stereotypesteve
 */

/*
 * Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent.
 * Instead, it is loaded in addition to the parent’s functions.php. (Specifically, it is loaded right before the parent theme's functions.php).
 * Source: http://codex.wordpress.org/Child_Themes#Using_functions.php
 *
 * Be sure not to define functions, that already exist in the parent theme!
 * A common pattern is to prefix function names with the (child) theme name.
 * Also if the parent theme supports pluggable functions you can use function_exists( 'put_the_function_name_here' ) checks.
 */

require_once( realpath( __DIR__ ) . '/inc/class.options-page.php' );

/**
 * Loads parent and child themes' style.css
 */
function ssteve_enqueue_parent_theme() {
	$parent_style = 'ssteve_dyad_theme_parent_style';
	$parent_base_dir = 'dyad';

	wp_enqueue_style( $parent_style,
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( $parent_base_dir ) ? wp_get_theme( $parent_base_dir )->get('Version') : ''
	);

	wp_enqueue_style( $parent_style . '_child_style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);
}

add_action( 'wp_enqueue_scripts', 'ssteve_enqueue_parent_theme' );

function ssteve_get_option( $option_name, $default = '' ) {
	$options = get_option( 'ssteve_options', array() );

	if ( empty( $options ) || ! is_array( $options ) || ! isset( $options[ $option_name ] ) ) {
		return $default;
	}

	return $options[ $option_name ];
}

// menus
register_nav_menus( array( 'top-social' => __( 'Social menu for the top nav bar', 'ssteve' ) ) );

<?php
/**
 * Class used to perform various theme related options
 *
 * @author Adam Richards <arichard@nerdery.com>
 */


class SSteveTheme {

	/**
	 * Initialize the theme.
	 */
	public static function init() {
		// Menu locations.
		register_nav_menus( array( 'top-social' => __( 'Social menu for the top nav bar', 'ssteve' ) ) );

	}

	/**
	 * Loads parent and child themes' style.css
	 */
	public static function enqueue_parent_theme() {
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

}
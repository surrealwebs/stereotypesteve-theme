<?php
/**
 * Theme functions.
 *
 * @package stereotypesteve
 */

require_once( realpath( __DIR__ ) . '/inc/class.options-page.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-theme.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-utilities.php' );

SSteveTheme::init();

add_action( 'wp_enqueue_scripts', 'SSteveTheme::ssteve_enqueue_parent_theme' );



<?php
/**
 * Theme functions.
 *
 * @package stereotypesteve
 */

require_once( realpath( __DIR__ ) . '/inc/class.options-page.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-theme.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-utilities.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-custom-modules.php' );

SSteveTheme::init();

add_action( 'wp_enqueue_scripts', 'SSteveTheme::enqueue_parent_theme' );



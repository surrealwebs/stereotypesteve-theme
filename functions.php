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
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-theme.php' );
require_once( realpath( __DIR__ ) . '/inc/class.ssteve-utilities.php' );

SSteveTheme::init();

add_action( 'wp_enqueue_scripts', 'SSteveTheme::ssteve_enqueue_parent_theme' );



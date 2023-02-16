<?php
/**
 * Pitchfork child theme functions and definitions
 *
 * @package pitchfork-child
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Enqueue child scripts and styles.
add_action( 'wp_enqueue_scripts', 'pitchfork_child_scripts' );
function pitchfork_child_scripts() {
	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$css_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/css/child-theme.min.css' );
	wp_enqueue_style( 'pitchfork-child-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array( 'pitchfork-styles' ), $css_child_version );

	$js_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/js/child-theme.js' );
	wp_enqueue_style( 'pitchfork-child-styles', get_stylesheet_directory_uri() . '/js/child-theme.js', array( 'jquery' ), $js_child_version );
}


// Enqueue to the admin. Block editor styles only.
add_action('enqueue_block_editor_assets', 'pitchfork_child_enqueue_editor_scripts');
function pitchfork_child_enqueue_editor_scripts() {

	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

    $css_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/css/block-editor.min.css' );
	wp_enqueue_style( 'pitchfork-child-blockeditor-styles', plugin_dir_url( __DIR__ ) . 'css/block-editor.min.css', array(), $css_child_version );

}
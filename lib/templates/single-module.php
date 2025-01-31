<?php
/**
 * This file adds the custom modules post type single post template to the Executive Pro Theme.
 *
 * @author StudioPress
 * @package Executive Pro
 * @subpackage Customizations
 */

add_filter( 'genesis_site_layout', 'course_maker_modules_single_template_layout' );
/**
 * Callback on the `genesis_site_layout` filter.
 * Force fullwidth content in the archive layout unless there is a specific module item layout set.
 *
 * @access public
 * @param  string $layout
 * @return string
 */
function course_maker_modules_single_template_layout( $layout ) {

	$custom_field = genesis_get_custom_field( '_genesis_layout' );
	$layout       = $custom_field ? $custom_field : genesis_get_option( 'site_layout' );

	return $layout;

}

add_action( 'wp_enqueue_scripts', 'course_maker_modules_load_default_styles' );

//* Remove the breadcrumb navigation
remove_action( 'genesis_before_loop' , 'genesis_do_breadcrumbs'          );
remove_action( 'genesis_entry_header', 'genesis_post_info'           , 5 );
remove_action( 'genesis_after_entry' , 'genesis_do_author_box_single', 8 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta'               );

genesis();

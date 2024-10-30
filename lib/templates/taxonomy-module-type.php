<?php
/**
 * This file adds the modules type taxonomy archive template to the Executive Pro Theme.
 *
 * @author StudioPress
 * @package Course Maker Modules
 * @subpackage Customizations
 */

add_filter( 'genesis_site_layout', 'course_maker_modules_taxonomy_template_layout' );
/**
 * Callback on the `genesis_site_layout` filter.
 * Force fullwidth content in the archive layout unless there is a specific taxonomy layout set.
 *
 * @access public
 * @param  string $layout
 * @return string
 */
function course_maker_modules_taxonomy_template_layout( $layout ) {

	global $wp_query;

	$term   = $wp_query->get_queried_object();
	$layout = ( $term && isset( $term->term_id ) && $opt = get_term_meta( $term->term_id, 'layout', true ) ) ? $opt : __genesis_return_full_width_content();

	return $layout;

}

//* Remove the breadcrumb navigation
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

add_action( 'wp_enqueue_scripts', 'course_maker_modules_load_default_styles' );
add_action( 'genesis_loop', 'course_maker_modules_setup_loop', 9 );

//* Add modules body class to the head
add_filter( 'body_class', 'course_maker_modules_add_body_class'   );
add_filter('post_class' , 'course_maker_modules_custom_post_class');

genesis();

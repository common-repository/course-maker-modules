modules<?php
/**
 * This file adds the modules type taxonomy archive template to the Executive Pro Theme.
 *
 * @author StudioPress
 * @package Course Maker Modules
 * @subpackage Customizations
 */

add_filter( 'genesis_site_layout', 'course_maker_modules_archive_template_layout' );
/**
 * Callback on the `genesis_site_layout` filter.
 * Force fullwidth content in the archive layout unless there is a specific archive layout set.
 *
 * @access public
 * @param  string $layout
 * @return string
 */
function course_maker_modules_archive_template_layout( $layout ) {

	$archive_opts = get_option( 'genesis-cpt-archive-settings-modules' );
	$layout       = empty( $archive_opts['layout'] ) ? __genesis_return_full_width_content() : $archive_opts['layout'];

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

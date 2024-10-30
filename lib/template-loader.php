<?php
/**
 * Template Loader
 *
 * Conditionally Loads Modules template files from child theme or plugin
 *
 * @package Course Maker Modules
 * @author  brandiD
 * @license GPL-2.0+
 */

define( 'course_maker_modules_TEMPLATE_DIR', COURSEMAKER_MODULES_LIBFOLDER . 'templates/' );

/**
 * Load custom template from child theme if available otherwise load plugin template
 *
 * @since 1.0.0
 *
 * @uses course_maker_modules_TEMPLATE_DIR
 * @access public
 * @param  string $template
 *
 */
function course_maker_modules_get_template_hierarchy( $template ) {

	// Get the template slug
	$template_slug = rtrim( $template, '.php' );
	$template = $template_slug . '.php';

	// Check if a custom template exists in the theme folder, if not, load the plugin template file
	if ( $theme_file = locate_template( array( $template ) ) ) {
		$file = $theme_file;
	}
	else {
		$file = course_maker_modules_TEMPLATE_DIR . $template;
	}

	/**
	 * Filter allows customizing the file via the theme or another plugin.
	 *
	 * @param string $file the path to the template file being used
	 */
	return apply_filters( 'course_maker_modules_repl_template_' . $template, $file );
}



add_filter( 'template_include', 'course_maker_modules_template_chooser' );
/**
 * Callback on the `template_include` filter.
 * Returns template file.
 *
 * @since 1.0.0
 *
 * @access public
 * @param  string $template
 * @return void
 */
function course_maker_modules_template_chooser( $template ) {

	if ( is_front_page() ) {
		return $template;
	}

	// Post ID
	$post_id = get_the_ID();

	if ( ! is_search() && get_post_type( $post_id ) == 'modules' || is_post_type_archive( 'modules' ) || is_tax( 'module-type' ) ) {
		require_once( COURSEMAKER_MODULES_LIBFOLDER . 'functions.php' );
	}
	if ( is_single() && get_post_type( $post_id ) == 'modules' ) {
		return course_maker_modules_get_template_hierarchy( 'single-module' );
	}
	elseif ( is_post_type_archive( 'modules' ) ) {
		return course_maker_modules_get_template_hierarchy( 'archive-modules' );
	}
	elseif ( is_tax( 'module-type' ) ) {
		return course_maker_modules_get_template_hierarchy( 'taxonomy-module-type' );
	}

	return $template;

}

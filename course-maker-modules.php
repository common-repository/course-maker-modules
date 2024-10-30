<?php
/*
Plugin Name: Course Maker Modules
Plugin URI:
Description: Course Maker Modules adds a Custom Post Type called "Modules" for you to use as Course content on any Genesis HTML5 theme. Designed for the Course Maker theme by brandiD. Based on the "Genesis Portfolio Pro" plugin by StudioPress.
Version: 1.0.1
Author: brandiD
Author URI: https://thebrandiD.com
Text Domain: course-maker-modules
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Denied." );
}

add_action( 'plugins_loaded', 'course_maker_modules_load_plugin_textdomain' );

/**
* Callback on the `plugins_loaded` hook.
* Loads the plugin text domain via load_plugin_textdomain()
*
* @uses load_plugin_textdomain()
* @since 1.0.0
*
* @access public
* @return void
*/
function course_maker_modules_load_plugin_textdomain() {
	load_plugin_textdomain( 'course-maker-modules', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

define( 'COURSEMAKER_MODULES_LIBFOLDER', dirname( __FILE__ ) . '/lib/' );
define( 'COURSEMAKER_MODULES_URL', plugins_url( '/', __FILE__ )  );

spl_autoload_register( 'course_maker_modules_autoload' );
/**
 * Callback for the `spl_autoload_register` function.
 * Requires class files for specified classes.
 *
 * @access public
 * @param  string $class
 * @return void
 */
function course_maker_modules_autoload( $class ) {

	$classes = array(
		'Course_Maker_Modules_Archive_Settings',
	);

	if ( in_array( $class, $classes ) ) {
		require sprintf( '%s/classes/class.%s.php', COURSEMAKER_MODULES_LIBFOLDER, $class );
	}

}

add_action( 'genesis_init', 'course_maker_modules_init' );
/**
 * Init action loads required files and other actions.
 * Loaded on genesis_init hook to ensure genesis_ functions are available
 *
 * @since 1.0.0
 *
 * @uses COURSEMAKER_MODULES_LIBFOLDER
 *
 */
function course_maker_modules_init() {

	require_once( COURSEMAKER_MODULES_LIBFOLDER . 'post-types-and-taxonomies.php' );

	if ( is_admin() ) {
		add_action( 'admin_enqueue_scripts', 'course_maker_modules_load_admin_styles' );
	} else if ( is_user_logged_in() ){
		add_action( 'wp_enqueue_scripts', 'course_maker_modules_load_admin_styles' );
	} else {
		require_once( COURSEMAKER_MODULES_LIBFOLDER . 'template-loader.php' );
	}

	//archive settings
	add_action( 'genesis_cpt_archives_settings_metaboxes', array( 'Course_Maker_Modules_Archive_Settings', 'register_metaboxes' ) );

	add_action( 'genesis_settings_sanitizer_init'      , 'course_maker_modules_archive_setting_sanitization'        );
	add_action( 'genesis_cpt_archive_settings_defaults', 'course_maker_modules_archive_setting_defaults'    , 10, 2 );
	add_action( 'after_setup_theme'                    , 'course_maker_modules_after_setup_theme'                   );

}

/**
 * Loads admin-style.css file
 *
 * @since 1.0.0
 *
 * @uses COURSEMAKER_MODULES_URL
 *
 */
function course_maker_modules_load_admin_styles() {

	wp_register_style( 'course_maker_modules_pro_admin_css',
		COURSEMAKER_MODULES_URL . 'lib/admin-style.css',
		false,
		'1.0.1'
	);
	wp_enqueue_style( 'course_maker_modules_pro_admin_css' );

}

/**
 * Adds new module image size if not already set in child theme
 *
 * @since 1.0.0
 *
 */
function course_maker_modules_after_setup_theme() {

	global $_wp_additional_image_sizes;

	if ( ! isset( $_wp_additional_image_sizes['modules'] ) ) {
		add_image_size( 'modules', 300, 200, TRUE );
	}

}

/**
 * Callback on the `genesis_settings_sanitizer_init` hook.
 * Registers the sanitize method for the posts_per_page archive setting option
 *
 * @access public
 * @static
 * @return void
 */
function course_maker_modules_archive_setting_sanitization() {

	genesis_add_option_filter(
		'absint',
		GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX . 'modules',
		array(
			'posts_per_page',
		)
	);

}

/**
 * Callback on the `genesis_cpt_archive_settings_defaults` filter.
 * Adds the archive setting for pagination
 *
 * @access public
 * @param  array  $defaults
 * @param  string $post_type
 * @return array
 */
function course_maker_modules_archive_setting_defaults( $defaults = array(), $post_type ) {

	if ( 'modules' === $post_type ) {
		$defaults                   = (array) $defaults;
		$defaults['posts_per_page'] = get_option( 'posts_per_page' );
	}

	return $defaults;

}

register_activation_hook( __FILE__, 'course_maker_modules_rewrite_flush' );
/**
 * Activation hook action to flush the rewrit rules for the custom post type and taxonomy
 *
 * @since 1.0.0
 *
 */
function course_maker_modules_rewrite_flush() {

	require_once( COURSEMAKER_MODULES_LIBFOLDER . 'post-types-and-taxonomies.php' );

	flush_rewrite_rules();

}


/**
 * Removes all actions for the provided hooks by cycling through the hooks and getting the priority so the action is removed correctly.
 *
 * @access public
 * @param string $action
 * @param array $hooks (default: array())
 * @return void
 */
function course_maker_modules_remove_actions( $action, $hooks = array() ) {

	foreach ( $hooks as $hook ) {
		if ( $priority = has_action( $hook, $action ) ) {
			remove_action( $hook, $action, $priority );
		}
	}

}

/**
 * Removes the specified action from the standard entry hooks.
 *
 * @access public
 * @param  string $action
 * @return void
 */
function course_maker_modules_remove_entry_actions( $action ) {

	$hooks = array(
		'genesis_entry_header',
		'genesis_before_entry_content',
		'genesis_entry_content',
		'genesis_after_entry_content',
		'genesis_entry_footer',
		'genesis_after_entry',
	);

	course_maker_modules_remove_actions( $action, $hooks );

}

add_filter( 'pre_get_posts', 'course_maker_modules_archive_pre_get_posts', 999 );
/**
 * Callback on the pre_get_posts hook.
 * Changes the posts per page setting for modules and module-type archives if set.
 *
 * @access public
 * @param  obj $query
 * @return void
 */
function course_maker_modules_archive_pre_get_posts( $query ) {

	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'modules' ) && ! $query->is_tax( 'module-type' ) ) {
		return;
	}

	$opts = (array) get_option( GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX . 'module' );

	if ( empty( $opts['posts_per_page'] ) ) {
		return;
	}

	$query->set( 'posts_per_page', intval( $opts['posts_per_page'] ) );

}

add_action( 'admin_bar_menu', 'course_maker_modules_add_adminbar_item', 70 );
/**
 * Add 'Modules' to Admin Bar
 *
 * @since 1.0.0
 *
 */
function course_maker_modules_add_adminbar_item( $wp_admin_bar ) {
	$args = array(
		'id' 		=> 'course_maker_modules_cpt',
		'title'     => 'Modules', // Label for this item
		'href'      => __(site_url().'/wp-admin/edit.php?post_type=modules'),
		'meta'  => array(
			// 'target'=> '_blank', // Opens the link with a new tab
			'title' => __('Modules'), // Text will be shown on hovering
		)
	);
	$wp_admin_bar->add_node( $args );
}

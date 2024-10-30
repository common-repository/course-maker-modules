<?php

/**
 * Post Type and Taxonomies
 *
 * Registers and Formats post types and taxonomies
 *
 * @package Genesis Module Pro
 * @author  StudioPress
 * @license GPL-2.0+
 */

//registers "module-type" taxonomy for the modules post type
register_taxonomy( 'module-type', 'modules',
	array(
		'labels' => array(
			'name'                       => _x( 'Module Types', 'taxonomy general name' , 'course-maker-modules' ),
			'singular_name'              => _x( 'Module Type' , 'taxonomy singular name', 'course-maker-modules' ),
			'search_items'               => __( 'Search Module Types'                   , 'course-maker-modules' ),
			'popular_items'              => __( 'Popular Module Types'                  , 'course-maker-modules' ),
			'all_items'                  => __( 'All Types'                             , 'course-maker-modules' ),
			'edit_item'                  => __( 'Edit Module Type'                      , 'course-maker-modules' ),
			'update_item'                => __( 'Update Module Type'                    , 'course-maker-modules' ),
			'add_new_item'               => __( 'Add New Module Type'                   , 'course-maker-modules' ),
			'new_item_name'              => __( 'New Module Type Name'                  , 'course-maker-modules' ),
			'separate_items_with_commas' => __( 'Separate Module Types with commas'     , 'course-maker-modules' ),
			'add_or_remove_items'        => __( 'Add or remove Module Types'            , 'course-maker-modules' ),
			'choose_from_most_used'      => __( 'Choose from the most used Module Types', 'course-maker-modules' ),
			'not_found'                  => __( 'No Module Types found.'                , 'course-maker-modules' ),
			'menu_name'                  => __( 'Module Types'                          , 'course-maker-modules' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
		),
		'exclude_from_search' => true,
		'has_archive'         => true,
		'hierarchical'        => true,
		'rewrite'             => array( 'slug' => _x( 'module-type', 'module-type slug' , 'course-maker-modules' ), 'with_front' => false ),
		'show_ui'             => true,
		'show_tagcloud'       => false,
	)
);

//registers "modules" post type
register_post_type( 'modules',
	array(
		'labels' => array(
			'name'               => _x( 'Modules', 'post type general name' , 'course-maker-modules' ),
			'singular_name'      => _x( 'Module' , 'post type singular name', 'course-maker-modules' ),
			'menu_name'          => _x( 'Modules', 'admin menu'             , 'course-maker-modules' ),
			'name_admin_bar'     => _x( 'Module' , 'add new on admin bar'   , 'course-maker-modules' ),
			'add_new'            => _x( 'Add New'   , 'Module Item'         , 'course-maker-modules' ),
			'add_new_item'       => __( 'Add New Module'                    , 'course-maker-modules' ),
			'new_item'           => __( 'New Module'                        , 'course-maker-modules' ),
			'edit_item'          => __( 'Edit Module'                       , 'course-maker-modules' ),
			'view_item'          => __( 'View Module'                       , 'course-maker-modules' ),
			'all_items'          => __( 'All Modules'                       , 'course-maker-modules' ),
			'search_items'       => __( 'Search Modules'                    , 'course-maker-modules' ),
			'parent_item_colon'  => __( 'Parent Modules:'                   , 'course-maker-modules' ),
			'not_found'          => __( 'No Modules found.'                 , 'course-maker-modules' ),
			'not_found_in_trash' => __( 'No Modules found in Trash.'        , 'course-maker-modules' )
		),
		'has_archive'  => true,
		'hierarchical' => true,
		'menu_icon'    => 'dashicons-welcome-learn-more',
		'public'       => true,
		'rewrite'      => array( 'slug' => _x( 'modules', 'modules slug' , 'course-maker-modules' ), 'with_front' => false ),
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo', 'genesis-cpt-archives-settings' ),
		'taxonomies'   => array( 'module-type' ),

	)
);

add_filter( 'manage_taxonomies_for_modules_columns', 'course_maker_modules_columns' );
/**
 * Add Module Type Taxonomy to columns
 *
 * @since 1.0.0
 *
 */
function course_maker_modules_columns( $taxonomies ) {

	$taxonomies[] = 'module-type';
	return $taxonomies;

}

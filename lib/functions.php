<?php
/**
 * Loads the default.css file via wp_enqueue_style
 * unless the `course_maker_modules_load_default_styles` is set to a false value.
 *
 * @access public
 * @return void
 */
function course_maker_modules_load_default_styles() {

	/**
	 * Allows disabling the default.css file.
	 *
	 * @param boolean (default = true)
	 */
	if ( apply_filters( 'course_maker_modules_load_default_styles', true ) ) {

		wp_register_style( 'course_maker_modules_styles',
			COURSEMAKER_MODULES_URL . 'lib/default.css',
			false,
			'1.0.0'
		);
		wp_enqueue_style( 'course_maker_modules_styles' );

	}

}

/**
 * Remove actions on before entry and setup the module entry actions
 */
function course_maker_modules_setup_loop(){

	$hooks = array(
		'genesis_before_entry',
		'genesis_entry_header',
		'genesis_before_entry_content',
		'genesis_entry_content',
		'genesis_after_entry_content',
		'genesis_entry_footer',
		'genesis_after_entry',
	);

	foreach ( $hooks as $hook ) {
		remove_all_actions( $hook );
	}

	add_action( 'genesis_entry_content'      , 'course_maker_modules_grid'                );
	add_action( 'genesis_after_entry_content', 'genesis_entry_header_markup_open' , 5  );
	add_action( 'genesis_after_entry_content', 'genesis_entry_header_markup_close', 15 );
	add_action( 'genesis_after_entry_content', 'genesis_do_post_title'                 );

}

/**
 * Callback on the `body_classes` filter.
 * Adds the `course-maker-modules` body class on module archive and single pages.
 *
 * @access public
 * @param  array $classes
 * @return array
 */
function course_maker_modules_add_body_class( $classes ) {

	$classes[] = 'course-maker-modules';
	return $classes;

}

/**
 * Callback on the `post_classes` filter.
 * Adds the modules class to the main query on module archive and single views
 *
 * @access public
 * @param  array $classes
 * @return array
 */
function course_maker_modules_custom_post_class( $classes ) {

	if ( is_main_query() ) {
		$classes[] = 'modules';
	}

	return $classes;
}

/**
 * Callback on the `course_maker_modules_grid` action.
 * Verifies there is an image attached to the module item
 * then outputs the HTML for the image with classes for styling.
 *
 * @uses genesis_get_image()
 *
 * @access public
 * @return void
 */
function course_maker_modules_grid() {

	$image = genesis_get_image( array(
			'format'  => 'html',
			'size'    => 'modules',
			'context' => 'archive',
			'attr'    => array ( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'modules-image' ),
		) );

	if ( $image ) {
		printf( '<div class="modules-featured-image"><a href="%s" rel="bookmark">%s</a></div>', get_permalink(), $image );
	}

}

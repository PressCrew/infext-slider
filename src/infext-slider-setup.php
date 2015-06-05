<?php

// load the dynamic thumb plugin
require_once INFEXT_SLIDER_LIB_DIR . '/otf_regen_thumbs.php';

/**
 * Setup custom post type for the slider.
 */
function infext_slider_setup_post_type()
{
	// is slider in custom post type mode?
	if ( infext_slider_is_mode( 'custom' ) ) {
		// yep, register it
		infext_slider_register_post_type();
	}
}
add_action( 'init', 'infext_slider_setup_post_type' );

/**
 * Load metaboxes class callback: https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
function infext_slider_init_metaboxes()
{
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( INFEXT_SLIDER_LIB_DIR . '/metaboxes/init.php' );
	}
}
add_action( 'init', 'infext_slider_init_metaboxes', 9999 );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function infext_slider_register_metaboxes( $meta_boxes = array() ) {

	// determine when to show metaboxes
	switch( infext_slider_get_mode() ) {
		// show only on 'infext_slider' post type
		case 1:
			$slider_type = 'infext_slider';
			break;
		// show them on all posts when a category is used for the slider
		case 2:
			$slider_type = 'post';
			break;
		// don't show metaboxes
		default:
			return;
	}

	$meta_boxes[] = array(
		'id'         => 'infext_slider_general_options',
		'title'      => __( 'Slide Options', 'infext-slider' ),
		'pages'      => array( $slider_type ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Slide Caption', 'infext-slider' ),
				'desc' => __( 'Write down the text you would like to display in the slider. You can leave this empty if you want to show an excerpt of the post you have written above.', 'infext-slider' ),
				'id'   => '_infext_slider_excerpt',
				'type' => 'wysiwyg',
					'options' => array(
					    'media_buttons' => false, // show insert/upload button(s)
					),
			),
			array(
				'name'    => __( 'Hide Caption?', 'infext-slider' ),
				'desc'    => __( 'Do you want to completely hide the caption for this slide? This will only display your slide image', 'infext-slider' ),
				'id'      => '_infext_slider_hide_caption',
				'type'    => 'radio_inline',
				'std' => 'no',
				'options' => array(
					array( 'name' => 'Yes', 'value' => 'yes', ),
					array( 'name' => 'No', 'value' => 'no', ),
				),
			),
			array(
				'name' => __( 'Custom URL', 'infext-slider' ),
				'desc' => __( 'The full URL you would like the slide to point to. Example: http://www.google.com.  Leave this blank to use the regular slider post permalink.', 'infext-slider' ),
				'id'   => '_infext_slider_custom_url',
				'type' => 'text',
			),
		),
	);

	// Add other metaboxes as needed
	$meta_boxes[] = array(
			'id'         => 'infext_slider_video_options',
			'title'      => __( 'Video Options', 'infext-slider' ),
			'pages'      => array( $slider_type ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name'    => __( 'Embed a Video?', 'infext-slider' ),
					'desc'    => __( 'Do you want to display a video inside your slide? Note: The video will replace your caption text and slide image.', 'infext-slider' ),
					'id'      => '_infext_slider_video_enable',
					'type'    => 'radio_inline',
					'std' => 'no',
					'options' => array(
						array( 'name' => 'Yes', 'value' => 'yes', ),
						array( 'name' => 'No', 'value' => 'no', ),
					),
				),
				array(
					'name' => __( 'Video URL', 'infext-slider' ),
					'desc' => __( 'Enter a Youtube or Vimeo URL. example: http://www.youtube.com/watch?v=iMuFYnvSsZg', 'infext-slider' ),
					'id'   => '_infext_slider_video_url',
					'type' => 'oembed',
				),
			)
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'infext_slider_register_metaboxes' );

/**
 * Register slider assets
 */
function infext_slider_register_assets()
{
	// bxslider styles
	ice_register_style(
		'bxslider',
		array(
			'src' => INFEXT_SLIDER_CSS_URL . '/bxslider/jquery.bxslider.css',
			'condition' => 'infext_slider_is_on_page'
		)
	);

	// infext slider styles
	ice_register_style(
		'infext-slider',
		array(
			'src' => INFEXT_SLIDER_CSS_URL . '/slider.css',
			'condition' => 'infext_slider_is_on_page'
		)
	);

	// bxslider script
	ice_register_script(
		'bxslider',
		array(
			'src' => INFEXT_SLIDER_JS_URL . '/jquery.bxslider.min.js',
			'in_footer' => true,
			'condition' => 'infext_slider_is_on_page'
		)
	);

	// infext-slider script
	ice_register_script(
		'infext-slider',
		array(
			'src' => INFEXT_SLIDER_JS_URL . '/slider.js',
			'in_footer' => true,
			'condition' => 'infext_slider_is_on_page'
		)
	);
}
add_action( 'after_setup_theme', 'infext_slider_register_assets', 11 );

/**
 * Action callback for localizing bxslider script in the footer.
 *
 * @package Infinity
 * @subpackage base
 */
function infext_slider_localize_script()
{
	// is slider enabled?
	if ( true === infext_slider_is_enabled() ) {

		// options to convert to JS object
		$options = array(
			'adaptiveHeight' => true,
			'auto' => true,
			'autoHover' => true,
			'mode' => 'fade',
			'video' => true,
			'useCSS' => false,
			'controls' => false,
			'pause' =>  5000,
			'speed' => 600
		);

		// get time option
		$time = infext_slider_get_time();

		// did we get a time?
		if ( false === empty( $time ) ) {
			// yep, override it
			$options[ 'pause' ] = $time;
		}

		// get transition option
		$trans = infext_slider_get_transition();

		// did we get a transition?
		if ( false === empty( $trans ) ) {
			// yep, override it
			$options[ 'speed' ] = $trans;
		}

		// pass through filter
		$options_final = apply_filters( 'infext_slider_localize_script_options', $options );

		// new script object
		$script = new ICE_Script();

		// create logic helper and add our variable
		$script
			->logic( 'vars' )
			->add_variable( 'infext_slider_options', (object) $options_final );

		// spit it out
		$script->render( true );
	}
}
add_action( 'wp_print_footer_scripts', 'infext_slider_localize_script', 9 );

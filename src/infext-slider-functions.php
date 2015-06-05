<?php

/**
 * Register custom post type for the slider.
 */
function infext_slider_register_post_type()
{
	$labels = array(
		'name'               => _x( 'Custom Slides', 'post type general name', 'infext-slider' ),
		'singular_name'      => _x( 'Custom Slide', 'post type singular name', 'infext-slider' ),
		'all_items'          => __( 'All Slides', 'infext-slider' ),
		'add_new'            => __( 'Add Slide', 'infext-slider' ),
		'add_new_item'       => __( 'Add Slide', 'infext-slider' ),
		'edit_item'          => __( 'Edit Slide', 'infext-slider' ),
		'new_item'           => __( 'New Slide', 'infext-slider' ),
		'search_items'       => __( 'Search slides', 'infext-slider' ),
		'not_found'          => __( 'No slides found', 'infext-slider' ),
		'not_found_in_trash' => __( 'No slides found in trash', 'infext-slider' ),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'slide' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => INFEXT_SLIDER_IMAGES_URL . '/icon.png',
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'infext_slider', $args );
}

/**
 * Get a slide's image url.
 *
 * @param integer $post_id The post id.
 * @param string $size The size of the thumbnail to get.
 * @return string The thumbnail url.
 */
function infext_slider_get_image_url( $post_id, $size='thumbnail' )
{
	// get post thumbnail id
	$thumb_id = get_post_thumbnail_id($post_id);

	// get one?
	if ( true === is_numeric( $thumb_id ) ) {
		// yes, get image src array
		$thumb_img = wp_get_attachment_image_src( (int) $thumb_id, $size );
		// get something?
		if ( true === isset( $thumb_img[0] ) ) {
			// yes, return it
			return $thumb_img[0];
		}
	}

	// not good
	return '';
}

/**
 * Return the value of the slider mode.
 *
 * @staticvar boolean $mode Cached value of mode setting.
 * @param boolean $force Set to true to bypass cached value of mode and get live option setting.
 * @return integer
 */
function infext_slider_get_mode( $force = false )
{
	// mode is null by default
	static $mode = null;

	// is mode null?
	if ( null === $mode || true === $force ) {
		// yep, get the setting
		$mode = (integer) infinity_option_get( 'slider.mode' );
	}

	// return mode
	return $mode;
}

/**
 * Return slider width setting.
 *
 * @return string
 */
function infext_slider_get_width()
{
	return infinity_option_get( 'slider.width' );
}

/**
 * Return slider height setting.
 *
 * @return string
 */
function infext_slider_get_height()
{
	return infinity_option_get( 'slider.height' );
}

/**
 * Return slider category id setting.
 *
 * @return integer
 */
function infext_slider_get_category_id()
{
	return (int) infinity_option_get( 'slider.category' );
}

/**
 * Return slider time setting.
 *
 * @return integer
 */
function infext_slider_get_time()
{
	return (int) infinity_option_get( 'slider.time' );
}

/**
 * Return slider transition setting.
 *
 * @return integer
 */
function infext_slider_get_transition()
{
	return (int) infinity_option_get( 'slider.transition' );
}

/**
 * Return slider amount setting.
 *
 * @return integer
 */
function infext_slider_get_amount()
{
	return (int) infinity_option_get( 'slider.amount' );
}

/**
 * Returns true if slider theme support is enabled and slider mode is set to a display option.
 *
 * @return boolean
 */
function infext_slider_is_enabled()
{
	// is slider support on?
	if ( true === current_theme_supports( 'infinity:slider' ) ) {
		// check slider mode value
		return ( infext_slider_get_mode() >= 1 );
	}

	// slider not enabled
	return false;
}

/**
 * Returns true if slider is enabled and the current page is using the slider template.
 *
 * @return boolean
 */
function infext_slider_is_on_page()
{
	return (
		true === infext_slider_is_enabled() &&
		true === is_page_template( 'homepage-template.php' )
	);
}

function infext_slider_is_mode( $mode )
{
	switch( $mode ) {
		// custom mode check
		case 1:
		case 'custom':
			return ( infext_slider_get_mode() === 1 );
		// category mode check
		case 2:
		case 'category':
			return ( infext_slider_get_mode() === 2 );
	}

	// no mode match
	return false;
}
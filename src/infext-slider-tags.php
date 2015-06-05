<?php

function infext_slider_load_template( $template, $require_once = true )
{
	return infext_slider_locate_template( $template . '.php', true, $require_once );
}

function infext_slider_locate_template( $template_names, $load = false, $require_once = true )
{
	// all paths to search
	$search_paths = apply_filters(
		'infext_locate_template_paths',
		array(
			STYLESHEETPATH . '/infext-slider',
			TEMPLATEPATH . '/infext-slider',
			INFEXT_SLIDER_TPL_DIR
		)
	);
	
	// loop all search paths
	foreach( (array) $search_paths as $search_path ) {

		// loop all tpl names
		foreach ( (array) $template_names as $template_name ) {

			// is template empty?
			if ( empty( $template_name ) ) {
				// yes, go to next loop
				continue;
			}

			// format full path to template
			$template_path = $search_path . '/' . $template_name;

			// does file exist?
			if ( true === file_exists( $template_path ) ) {

				// is load param toggled on?
				if ( true === $load ) {
					// yep, load it
					load_template( $template_path, $require_once );
				}

				// return full path
				return $template_path;
			}
		}
	}

	// return an empty string by default
	return '';
}

function infext_slider_display( $template = 'default' )
{
	return infext_slider_load_template( 'loops/' . $template );
}

/**
 * Set up slider query and return true if loop should continue.
 *
 * @global WP_Query $infext_slider_query
 * @return boolean
 */
function infext_slider_have_slides()
{
	global $infext_slider_query;

	// do we need to set up slider query?
	if ( true === empty( $infext_slider_query ) ) {

		// yes, setup default slider query args
		$query_args = array(
			'order' => 'ASC',
			'posts_per_page' => '-1'
		);

		// get slider amount setting
		$posts_per_page = infext_slider_get_amount();

		// get a custom amount?
		if ( false === empty( $posts_per_page ) ) {
			// yes, override default
			$query_args['posts_per_page'] = $posts_per_page;
		}

		// custom mode?
		if ( infext_slider_is_mode( 'custom' ) ) {
			// yes, use our custom post type
			$query_args['post_type'] = 'infext_slider';
		}

		// category mode?
		if ( infext_slider_is_mode( 'category' ) ) {
			// yes, use configured category
			$query_args['cat'] = infext_slider_get_category_id();
		}

		// new slider query
		$infext_slider_query = new WP_Query( $query_args );
	}

	// did we get anything?
	return $infext_slider_query->have_posts();
}

/**
 * Set up next slide for the loop.
 *
 * @global WP_Query $infext_slider_query
 */
function infext_slider_the_slide()
{
	global $infext_slider_query;

	// tell query object to setup the slide
	$infext_slider_query->the_post();
}

/**
 * Returns true if current slide's caption should be shown.
 *
 * @return boolean
 */
function infext_slider_the_slide_show_caption()
{
	// try to get hide caption setting for post
	$hide_caption = get_post_meta( get_the_ID(), '_infext_slider_hide_caption', true );

	// return true unless hide caption is explicitly "yes"
	return ( 'yes' !== $hide_caption );
}

/**
 * Returns true if current slide has video enabled.
 *
 * @return boolean
 */
function infext_slider_the_slide_show_video()
{
	// try to get video enable setting for post
	$video_enabled = get_post_meta( get_the_ID(), '_infext_slider_video_enable', true );

	// return true if enable video is explicitly "yes"
	return ( 'yes' === $video_enabled );
}

/**
 * Returns true if current slide has a post thumbnail.
 *
 * @return booleanS
 */
function infext_slider_the_slide_has_thumbnail()
{
	return has_post_thumbnail();
}

/**
 * Print the post thumbnail for the current slide.
 */
function infext_slider_the_slide_thumbnail()
{
	the_post_thumbnail( array( infext_slider_get_width(), infext_slider_get_height() ) );
}

/**
 * Return post thumbnail of the current slide.
 *
 * @param integer $slide_id The post id of the slide to retrieve thumbnail for.
 * @return string
 */
function infext_slider_get_the_slide_thumbnail( $slide_id = null )
{
	return get_the_post_thumbnail( $slide_id, array( infext_slider_get_width(), infext_slider_get_height() ) );
}

/**
 * Print permalink of the current slide.
 */
function infext_slider_the_slide_permalink()
{
	echo esc_url( infext_slider_get_the_slide_permalink() );
}

/**
 * Return permalink for the current slide.
 *
 * @return string
 */
function infext_slider_get_the_slide_permalink()
{
	// try to get custom URL from post meta
	$custom_url = get_post_meta( get_the_ID(), '_infext_slider_custom_url', true );

	// did we get a custom url?
	if ( false === empty( $custom_url ) ) {
		// yes, return it
		return $custom_url;
	} else {
		// no, return default permalink
		return get_the_permalink();
	}
}

/**
 * Print the title for the current slide.
 */
function infext_slider_the_slide_title()
{
	the_title();
}

/**
 * Return the title of the current slide.
 *
 * @return string
 */
function infext_slider_get_the_slide_title()
{
	return get_the_title();
}

/**
 * Print the excerpt for the current slide.
 */
function infext_slider_the_slide_excerpt()
{
	echo infext_slider_get_the_slide_excerpt();
}

/**
 * Return the excerpt for the current slide.
 *
 * @return string
 */
function infext_slider_get_the_slide_excerpt()
{
	// try to get custom excerpt from post meta
	$custom_excerpt = get_post_meta( get_the_ID(), '_infext_slider_excerpt', true );

	// did we get a custom excerpt string?
	if ( false === empty( $custom_excerpt ) ) {
		// yes, use it
		return wpautop( $custom_excerpt );
	} else {
		// no, use generate excerpt from
		return apply_filters( 'the_content', infext_slider_create_excerpt( get_the_content() ) );
	}
}

/**
 * Print the video url for the current slide.
 */
function infext_slider_the_slide_video_content()
{
	echo apply_filters( 'the_content', infext_slider_get_the_slide_video_url() );
}

/**
 * Print the video url for the current slide.
 */
function infext_slider_the_slide_video_url()
{
	echo infext_slider_get_the_slide_video_url();
}

/**
 * Return the video url for the current slide.
 *
 * @return string
 */
function infext_slider_get_the_slide_video_url()
{
	// try to get video url from post meta
	$video_url = get_post_meta( get_the_ID(), '_infext_slider_video_url', true );

	// did we get a video url?
	if ( false === empty( $video_url ) ) {
		// yes, use it
		return $video_url;
	} else {
		// no, this is bad
		return false;
	}
}

/**
 * Print slider width setting.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infext_slider_width( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infext_slider_get_width() );
	} else {
		echo infext_slider_get_width();
	}
}

/**
 * Print slider height setting.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infext_slider_height( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infext_slider_get_height() );
	} else {
		echo infext_slider_get_height();
	}
}

/**
 * Print url of no slides image.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infext_slider_no_slides_image_url( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infext_slider_get_no_slides_image_url() );
	} else {
		echo infext_slider_get_no_slides_image_url();
	}
}

/**
 * Return url of no slides image.
 *
 * @staticvar string $url Cached image url.
 * @return string
 */
function infext_slider_get_no_slides_image_url()
{
	// return it
	return INFEXT_SLIDER_IMAGES_URL . '/bg.png';
}

/**
 * Print title text for no slides found.
 */
function infext_slider_no_slides_title()
{
	_e( 'No slides have been added yet!', 'infext-slider' );
}

/**
 * Print helpful text for no slides found.
 */
function infext_slider_no_slides_help()
{
	// start helpful text
	_e( 'Did you know you can easily add slides to your homepage?', 'infext-slider' );

	// add a space
	echo ' ';

	// more helpful text depending on mode
	if ( infext_slider_is_mode( 'custom' ) ) {
		// need to add custom slides
		_e( 'Simply go to the admin dashboard and add a new <strong>Custom Slide</strong>.', 'infext-slider' );
	} elseif ( infext_slider_is_mode( 'category' ) ) {
		// get category object
		$category = get_category( infext_slider_get_category_id() );
		// need to add posts to configured category
		printf(
			__( 'Simply go to the admin dashboard and add a new post to the <strong>%s</strong> category.', 'infext-slider' ),
			$category->name
		);
	}
}

/**
 * Print helpful text for slide missing content.
 */
function infext_slider_no_content_help()
{
	// start helpful text
	_e( 'This slide has no content!', 'infext-slider' );

	// add a space
	echo ' ';

	// more helpful text depending on mode
	if ( infext_slider_is_mode( 'custom' ) ) {
		// need to edit this custom slide
		_e( 'Please go to the admin dashboard and edit this <strong>Custom Slide</strong>.', 'infext-slider' );
	} elseif ( infext_slider_is_mode( 'category' ) ) {
		// need to edit the post
		_e( 'Please go to the admin dashboard and edit this post.', 'infext-slider' );
	}
}

/**
 * Create an excerpt
 *
 * Uses infinity_bp_create_excerpt() when available. Otherwise falls back on a very
 * rough approximation, ignoring the fancy params passed.
 *
 * @return string
 */
function infext_slider_create_excerpt( $text, $length = 425, $options = array() )
{
	// does bp function exist?
	if ( function_exists( 'infinity_bp_create_excerpt' ) ) {
		// yes, use it
		return infinity_bp_create_excerpt( $text, $length, $options );
	} else {
		// no, wing it
		return substr( $text, 0, $length ) . ' [&hellip;]';
	}
}
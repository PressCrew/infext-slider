<?php

/**
 * Add new column to the Custom Slides index.
 *
 * @param array $columns Array of columns passed by filter.
 * @return array
 */
function infext_slider_custom_column_add( $columns )
{
	// add our column to the array
	$columns[ 'infext_slider_image' ] = __( 'Slide Image', 'infext-slider' );

	// return it
	return $columns;
}
add_filter( 'manage_infext_slider_posts_columns', 'infext_slider_custom_column_add' );

/**
 * Show the slide image in the new column.
 *
 * @param string $column_name The name of the column being rendered.
 * @param integer $post_id The post id of the column being rendered.
 */
function infext_slider_custom_column_content( $column_name, $post_id )
{
	// is this our column?
	if ( $column_name === 'infext_slider_image' ) {
		// yes, try to get the thumb url
		$thumb_url = infext_slider_get_image_url( $post_id );
		// did we get one?
		if ( false === empty( $thumb_url ) ) {
			// yes, render it
			?><img src="<?php echo $thumb_url ?>" /><?php
		}
	}
}
add_action( 'manage_infext_slider_posts_custom_column', 'infext_slider_custom_column_content', 10, 2 );

/**
 * Renames the 'Featured Image' metabox for the slider's custom post type.
 *
 * To rename the metabox, we actually have to remove the 'Featured Image'
 * metabox and replace it with a custom one with the same functionality.
 */
function infext_slider_rename_image_metabox()
{
	// remove default meta box
	remove_meta_box( 'postimagediv', 'infext_slider', 'side' );

	// replace with our custom one
	add_meta_box(
		'postimagediv',
		__( 'Slide Image', 'infext-slider' ),
		'post_thumbnail_meta_box',
		'infext_slider',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'infext_slider_rename_image_metabox' );

/**
 * Add inline JS to toggle "Slide Options" metabox on a "Post" admin page.
 *
 * When the slider's featured category is checked in the "Post" admin page,
 * this javascript will either show or hide the "Slide Options" and "Video
 * Options" metaboxes.
 *
 * This is only used if the slider is set to use a post category.
 */
function infext_slider_admin_footer_script()
{
	// is slider mode set to category?
	if ( infext_slider_is_mode( 'category' ) ) {
		// switch on hook suffix
		switch ( $GLOBALS['hook_suffix'] ) {
			// post edit screens
			case 'post-new.php' :
			case 'post.php' :
				// get category id set in slider options
				$cat_id = infext_slider_get_category_id();
				break;
			// every other screen
			default:
				// bail
				return;
		}
	} else {
		// other slider mode, bail
		return;
	}

	// render the embedded footer script ?>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(function($) {
			function infext_slider_toggle_metaboxes() {
				$('#infext_slider_general_options, #infext_slider_video_options').hide();

				$('#categorychecklist input[type="checkbox"]').each(function(i,e){
					var id = $(this).attr('id').match(/-([0-9]*)$/i);
					id = (id && id[1]) ? parseInt(id[1]) : null ;

					if ($.inArray(id, [<?php echo $cat_id; ?>]) > -1 && $(this).is(':checked')) {
						$('#infext_slider_general_options, #infext_slider_video_options').show();
					}
				});
			}

			$('#taxonomy-category').on( 'click', '#categorychecklist input[type="checkbox"]', infext_slider_toggle_metaboxes );

			infext_slider_toggle_metaboxes();
		});
	//]]>
	</script><?php
}
add_action( 'admin_footer', 'infext_slider_admin_footer_script' );

/**
 * Rename all deprecated post types known to exist for older theme versions.
 *
 * @global wpdb $wpdb
 */
function infext_slider_compat_post_types()
{
	global $wpdb;

	// make sure current post type is registered
	infext_slider_register_post_type();

	// build up old => new types map
	$typemap = array(
		'features' => 'infext_slider'
	);

	// loop the type map
	foreach( $typemap as $old_type => $new_type ) {

		// get all posts of old type
		$posts = get_posts( array(
			'post_status' => array( 'any', 'trash', 'auto-draft' ),
			'post_type' => $old_type
		));

		// loop all posts
		foreach( $posts as $post ) {
			// set the new post type
			set_post_type( $post->ID, $new_type );
			// fix guid field
			$wpdb->update(
				$wpdb->posts,
				array( 'guid' => get_permalink( $post->ID ) ),
				array( 'ID' => $post->ID )
			);
		}
	}

	// flush rewrite rules just in case
	flush_rewrite_rules();
}
add_action( 'infinity_dashboard_activated', 'infext_slider_compat_post_types' );

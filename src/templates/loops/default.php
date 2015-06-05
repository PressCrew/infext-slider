<?php
/**
 * Template Name: Custom or Category Slider
 *
 * This template either displays Slides taken from the custom post type,
 * or loops through posts from a certain category. This is based on the
 * theme options set by the user.
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @since 1.0
 */

// render slider markup ?>
<div class="flex-container">
	<div class="flexslider">
		<ul class="slides">
		<?php
			// setup slides loop
			if ( infext_slider_have_slides() ):
				// start slides loop
				while( infext_slider_have_slides() ):
					// setup current slide
					infext_slider_the_slide();
					// is video enabled?
					if ( infext_slider_the_slide_show_video() ):
						// yes, it's a video slide
						infext_slider_load_template( 'parts/video', false );
					// does it have a thumbnail?
					elseif ( infext_slider_the_slide_has_thumbnail() ):
						// yes, it's a post slide
						infext_slider_load_template( 'parts/post', false );
					// everything else
					else:
						// it's an empty slide
						infext_slider_load_template( 'parts/no-content', false );
					endif;
				endwhile;
			else:
				// there are no slides
				infext_slider_load_template( 'parts/no-slides', false );
			endif;
		?>
		</ul>
	</div>
</div>
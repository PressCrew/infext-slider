<?php
/**
 * Infinity Theme: Slider, slide has no content.
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Bowe Frankema, Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 */
 ?>
<li>
	<img
		src="<?php infext_slider_no_slides_image_url(); ?>"
		width="<?php infext_slider_width(); ?>"
		height="<?php infext_slider_height(); ?>"
		style="width:<?php infext_slider_width(); ?>px; height:<?php infext_slider_height(); ?>px;"
	>
	<div class="flex-caption">
		<h3>
			<?php infext_slider_the_slide_title(); ?>
		</h3>
		<p>
			<?php infext_slider_no_content_help(); ?>
		</p>
	</div>
</li>
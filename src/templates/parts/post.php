<?php
/**
 * Infinity Theme: Slider post (from category) template.
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
	<a href="<?php infext_slider_the_slide_permalink(); ?>"><?php infext_slider_the_slide_thumbnail(); ?></a>
	<?php
		// show caption?
		if ( infext_slider_the_slide_show_caption() ):
			// show slide caption ?>
			<div class="flex-caption">
				<h3>
					<a href="<?php infext_slider_the_slide_permalink(); ?>"><?php infext_slider_the_slide_title();?></a>
				</h3>
				<?php
					infext_slider_the_slide_excerpt();
				?>
			</div>
			<?php
		endif;
	?>
</li>
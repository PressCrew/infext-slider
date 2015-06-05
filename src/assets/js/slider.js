
jQuery(document).ready(function($)
{
	// slider initialization
	if ( typeof infext_slider_options !== 'undefined' ) {
		infext_slider_init( '.slides', infext_slider_options );
	}
});

function infext_slider_init( selector, options )
{
	var el = jQuery( selector );

	if ( 0 < el.length && typeof el.bxSlider !== 'undefined' ) {
		el.bxSlider( options );
	}
}
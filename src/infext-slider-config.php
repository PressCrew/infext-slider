<?php

//
// Slider Section
//
ice_register_section(
	array(
		'name' => 'slider',
		'type' => 'cpanel',
		'title' => 'Slider'
	)
);

//
// Slider Group
//
ice_register_group( 'slider' );

	// Slider post_type='infext_slider' support
	ice_register_feature(
		array(
			'name' => 'custom',
			'group' => 'slider',
			'type' => 'default',
			'title' => 'Custom Slider',
			'description' => 'Adds a custom post type for managing slides.'
		)
	);

	// Slider post/page category support
	ice_register_feature(
		array(
			'name' => 'category',
			'group' => 'slider',
			'type' => 'default',
			'title' => 'Category Slider',
			'description' => 'Adds the option to use a category\'s featured images for slide content.'
		)
	);

		$infext_slider_options_defaults = array(
			'group' => 'slider',
			'section' => 'slider'
		);

		ice_register_option(
			array(
				'name' => 'mode',
				'type' => 'select',
				'title' => 'Display Mode',
				'description' =>
					'If you enable the slider you can choose what type of content you want to display. ' .
					'You can choose between displaying images and videos that you have added through the ' .
					'Custom Slides screen or showing images and videos from posts from a category of your choosing. ' .
					'If you choose the latter, there are some options you can set at the bottom of this page.',
				'field_options' => array(
					0 => 'Do not display',
					1 => 'Show Custom Slides',
					2 => 'Show Category'
				),
				'default_value' => 1
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'category',
				'section' => 'slider',
				'title' => 'Post Category',
				'description' => 'From which category do you want to show posts? This ONLY works when your slider is set to Show Category.',
				'type' => 'category'
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'amount',
				'title' => 'Number of Slides',
				'description' => 'Enter the number of slides you want to show.',
				'type' => 'text',
				'default_value' => 5
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'height',
				'title' => 'Height',
				'description' => 'The height of the slider in pixels.',
				'type' => 'ui/slider',
				'min' => 100,
				'max' => 1000,
				'step' => 10,
				'label' => 'Height in pixels:',
				'default_value' => 344
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'width',
				'title' => 'Width',
				'description' => 'The width of the slider in pixels.',
				'type' => 'ui/slider',
				'min' => 200,
				'max' => 2000,
				'step' => 10,
				'label' => 'Width in pixels:',
				'default_value' => 670
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'time',
				'title' => 'Display',
				'description' => 'The number of milliseconds a slide displays before changing (1 second = 1000 milliseconds).',
				'type' => 'ui/slider',
				'min' => 1000,
				'max' => 10000,
				'step' => 10,
				'label' => 'Time in milliseconds:',
				'default_value' => 5000
			),
			$infext_slider_options_defaults
		);

		ice_register_option(
			array(
				'name' => 'transition',
				'title' => 'Transition Speed',
				'description' => 'The number of milliseconds it takes the slide to change (1 second = 1000 milliseconds).',
				'type' => 'ui/slider',
				'min' => 200,
				'max' => 5000,
				'step' => 10,
				'label' => 'Time in milliseconds:',
				'default_value' => 600
			),
			$infext_slider_options_defaults
		);

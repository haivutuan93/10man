<?php

	$madara_image_sizes = array(
		'madara_misc_thumb_1' => array(
			254,
			140,
			true,
			esc_html__( 'Thumb 254x140px', 'madara' ),
			esc_html__( 'This thumb size is used for post thumbnail in Widget Posts & Post Navigation', 'madara' )
		),
		'madara_misc_thumb_2' => array(
			360,
			206,
			true,
			esc_html__( 'Thumb 360x206px', 'madara' ),
			esc_html__( 'This thumb size is used for post thumbnail in Blog Listing', 'madara' )
		),
		'madara_misc_thumb_3' => array(
			125,
			180,
			true,
			esc_html__( 'Thumb 125x180px', 'madara' ),
			esc_html__( 'This thumb size is used for post thumbnail in Popular Slider', 'madara' )
		),
        'madara_misc_thumb_4' => array(
            1200,
            630,
            true,
            esc_html__( 'Thumb 1200x630px', 'madara' ),
            esc_html__( 'This thumb size is used for meta tag og:image', 'madara' )
        ),
	);

	$madara_image_size_mapping = array(
		'254x140' => 'madara_misc_thumb_1',
		'360x206' => 'madara_misc_thumb_2',
		'125x180' => 'madara_misc_thumb_3',
		'1200x630' => 'madara_misc_thumb_4',
	);
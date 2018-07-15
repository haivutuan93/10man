<?php

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	/**
	 * Custom settings array that will eventually be
	 * passes to the OptionTree Settings API Class.
	 */

	if ( class_exists( 'WP_MANGA' ) ) {
		// wp-manga plugin is active, add some options to Theme Options

		$madara_theme_options = array(
			'sections' => array(
				array(
					'id'    => 'manga_general',
					'title' => '<i class="fas fa-bolt"><!-- --></i>' . esc_html__( 'Manga General Layout', 'madara' ),
				),
				array(
					'id'    => 'manga_archives',
					'title' => '<i class="fas fa-bolt"><!-- --></i>' . esc_html__( 'Manga Archives Layout', 'madara' ),
				),
				array(
					'id'    => 'manga_single',
					'title' => '<i class="fas fa-bolt"><!-- --></i>' . esc_html__( 'Manga Single Layout', 'madara' ),
				),
				array(
					'id'    => 'manga_reading',
					'title' => '<i class="fas fa-bolt"><!-- --></i>' . esc_html__( 'Manga Reading Layout', 'madara' ),
				),
			),
			'settings' => array(


				/*
				* Manga Theme Options
				* */
				array(
					'id'      => 'manga_hover_details',
					'label'   => esc_html__( 'Manga Hover Details', 'madara' ),
					'desc'    => esc_html__( 'Show manga details when manga item in Manga Listing hoverd', 'madara' ),
					'std'     => 'off',
					'type'    => 'on-off',
					'section' => 'manga_general'
				),

				array(
					'id'      => 'manga_main_top_sidebar_container',
					'label'   => esc_html__( 'Manga Main Top Sidebar Container', 'madara' ),
					'desc'    => esc_html__( 'Set container for Manga Main Top Sidebar. Custom width is 1760px', 'madara' ),
					'std'     => 'container',
					'type'    => 'radio-image',
					'class'   => '',
					'choices' => array(
						array(
							'value' => 'full_width',
							'label' => esc_html__( 'Full-Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-fullwidth.png' ),
						),
						array(
							'value' => 'container',
							'label' => esc_html__( 'Container', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-container.png' ),
						),
						array(
							'value' => 'custom_width',
							'label' => esc_html__( 'Custom Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-custom-width.png' ),
						)
					),
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_top_sidebar_background',
					'label'   => esc_html__( 'Manga Main Top Sidebar Background', 'madara' ),
					'desc'    => esc_html__( 'Upload background image for Manga Main Top Sidebar', 'madara' ),
					'std'     => '',
					'type'    => 'background',
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_top_sidebar_spacing',
					'label'   => esc_html__( 'Manga Main Top Sidebar - Padding', 'madara' ),
					'desc'    => esc_html__( 'Padding in Manga Main Top Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
					'std'     => '',
					'type'    => 'spacing',
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_top_second_sidebar_container',
					'label'   => esc_html__( 'Manga Main Top Second Sidebar Container', 'madara' ),
					'desc'    => esc_html__( 'Set container for Manga Main Top Second Sidebar. Custom width is 1760px', 'madara' ),
					'std'     => 'container',
					'type'    => 'radio-image',
					'class'   => '',
					'choices' => array(
						array(
							'value' => 'full_width',
							'label' => esc_html__( 'Full-Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-fullwidth.png' ),
						),
						array(
							'value' => 'container',
							'label' => esc_html__( 'Container', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-container.png' ),
						),
						array(
							'value' => 'custom_width',
							'label' => esc_html__( 'Custom Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-custom-width.png' ),
						)
					),
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_top_second_sidebar_background',
					'label'   => esc_html__( 'Manga Main Top Second Sidebar Background', 'madara' ),
					'desc'    => esc_html__( 'Upload background image for Manga Main Top Second Sidebar', 'madara' ),
					'std'     => '',
					'type'    => 'background',
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_top_second_sidebar_spacing',
					'label'   => esc_html__( 'Manga Main Top Second Sidebar - Padding', 'madara' ),
					'desc'    => esc_html__( 'Padding in Manga Main Top Second Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
					'std'     => '',
					'type'    => 'spacing',
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_bottom_sidebar_container',
					'label'   => esc_html__( 'Manga Main Bottom Sidebar Container', 'madara' ),
					'desc'    => esc_html__( 'Set container for Manga Main Bottom Sidebar. Custom width is 1760px', 'madara' ),
					'std'     => 'container',
					'type'    => 'radio-image',
					'class'   => '',
					'choices' => array(
						array(
							'value' => 'full_width',
							'label' => esc_html__( 'Full-Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-fullwidth.png' ),
						),
						array(
							'value' => 'container',
							'label' => esc_html__( 'Container', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-container.png' ),
						),
						array(
							'value' => 'custom_width',
							'label' => esc_html__( 'Custom Width', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-custom-width.png' ),
						)
					),
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_bottom_sidebar_background',
					'label'   => esc_html__( 'Manga Main Bottom Sidebar Background', 'madara' ),
					'desc'    => esc_html__( 'Upload background image for Manga Main Bottom Sidebar', 'madara' ),
					'std'     => '',
					'type'    => 'background',
					'section' => 'manga_general',
				),

				array(
					'id'      => 'manga_main_bottom_sidebar_spacing',
					'label'   => esc_html__( 'Manga Main Bottom Sidebar - Padding', 'madara' ),
					'desc'    => esc_html__( 'Padding in Manga Main Bottom Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
					'std'     => '',
					'type'    => 'spacing',
					'section' => 'manga_general',
				),

				array(
					'id'           => 'manga_archive_breadcrumb',
					'label'        => esc_html__( 'Manga Archive Breadcrumb', 'madara' ),
					'desc'         => esc_html__( 'Enable Breadcrumb on Manga Archive page', 'madara' ),
					'std'          => 'on',
					'type'         => 'on-off',
					'section'      => 'manga_archives',
					'min_max_step' => '',
				),

				array(
					'id'      => 'manga_archive_heading',
					'label'   => esc_html__( 'Manga Archive Heading', 'madara' ),
					'desc'    => esc_html__( 'Manga Archive Heading. Default is All Manga', 'madara' ),
					'std'     => '',
					'type'    => 'text',
					'section' => 'manga_archives',
				),

				array(
					'id'      => 'manga_archive_breadcrumb_bg',
					'label'   => esc_html__( 'Manga Archive Breadcrumb Background', 'madara' ),
					'desc'    => esc_html__( 'Upload background image for Manga Archive Breadcrumb', 'madara' ),
					'std'     => '',
					'type'    => 'background',
					'section' => 'manga_archives',
				),

				array(
					'id'      => 'manga_archive_genres',
					'label'   => esc_html__( 'Genres on Manga Archive Page', 'madara' ),
					'desc'    => esc_html__( 'Enable Genres block on Manga Archive Page Breadcrumb', 'madara' ),
					'std'     => 'on',
					'type'    => 'on-off',
					'section' => 'manga_archives',
				),

				array(
					'id'        => 'manga_archive_genres_collapse',
					'label'     => esc_html__( 'Show or hide Genres list', 'madara' ),
					'desc'      => esc_html__( 'Show or hide Genres list. Default is On to show Genres list', 'madara' ),
					'std'       => 'on',
					'type'      => 'on-off',
					'section'   => 'manga_archives',
					'condition' => 'manga_archive_genres:is(on)',
				),

				array(
					'id'        => 'manga_archive_genres_title',
					'label'     => esc_html__( 'Genres Block Title', 'madara' ),
					'desc'      => esc_html__( 'Genres Block Title. Default is "GENRES"', 'madara' ),
					'type'      => 'text',
					'section'   => 'manga_archives',
					'condition' => 'manga_archive_genres:is(on)',
				),

				array(
					'id'      => 'manga_archive_sidebar',
					'label'   => esc_html__( 'Manga Archives Sidebar', 'madara' ),
					'desc'    => '',
					'std'     => 'right',
					'type'    => 'radio-image',
					'section' => 'manga_archives',
					'choices' => array(
						array(
							'value' => 'left',
							'label' => esc_html__( 'Left', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-left.png' ),
						),
						array(
							'value' => 'right',
							'label' => esc_html__( 'Right', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-right.png' ),
						),
						array(
							'value' => 'full',
							'label' => esc_html__( 'Hidden', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-hidden.png' ),
						)
					),
				),

				array(
					'id'      => 'manga_single_allow_thumb_gif',
					'label'   => esc_html__( 'Allow GIF for Featured Image', 'madara' ),
					'desc'    => esc_html__( 'Turn On/Off display GIF for Featured Image. Default Off.', 'madara' ),
					'std'     => 'off',
					'type'    => 'on-off',
					'section' => 'manga_single',
				),

				array(
					'id'      => 'manga_profile_background',
					'label'   => esc_html__( 'Manga Profiles Background', 'madara' ),
					'desc'    => esc_html__( 'Upload your background image for Single Manga Profiles', 'madara' ),
					'std'     => '',
					'type'    => 'background',
					'section' => 'manga_single',
				),

				array(
					'id'      => 'manga_single_breadcrumb',
					'label'   => esc_html__( 'Manga Single Breadcrumb', 'madara' ),
					'desc'    => esc_html__( 'Enable Breadcrumb on Manga Single page', 'madara' ),
					'std'     => 'on',
					'type'    => 'on-off',
					'section' => 'manga_single',
				),

				array(
					'id'      => 'manga_single_sidebar',
					'label'   => esc_html__( 'Manga Single Sidebar', 'madara' ),
					'desc'    => '',
					'std'     => 'right',
					'type'    => 'radio-image',
					'section' => 'manga_single',
					'choices' => array(
						array(
							'value' => 'left',
							'label' => esc_html__( 'Left', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-left.png' ),
						),
						array(
							'value' => 'right',
							'label' => esc_html__( 'Right', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-right.png' ),
						),
						array(
							'value' => 'full',
							'label' => esc_html__( 'Hidden', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-hidden.png' ),
						)
					),
				),

				array(
					'id'      => 'manga_chapters_order',
					'label'   => esc_html__( 'Manga Single - Chapters Order', 'madara' ),
					'desc'    => esc_html__( 'Chapters order in Manga Single', 'madara' ),
					'std'     => 'name_desc',
					'type'    => 'select',
					'section' => 'manga_single',
					'choices' => array(
						array(
							'value' => 'name_asc',
							'label' => esc_html__( 'Oldest to latest by Name', 'madara' ),
						),
						array(
							'value' => 'name_desc',
							'label' => esc_html__( 'Latest to oldest by Name', 'madara' ),
						),
						array(
							'value' => 'date_asc',
							'label' => esc_html__( 'Oldest to latest by Time', 'madara' ),
						),
						array(
							'value' => 'date_desc',
							'label' => esc_html__( 'Latest to oldest by Time', 'madara' ),
						),
					),
				),

				array(
					'id'      => 'manga_reading_dark_mode',
					'label'   => esc_html__( 'Dark Mode', 'madara' ),
					'desc'    => esc_html__( 'Turn On/Off Dark Mode for Manga Reading Page. Default Off.', 'madara' ),
					'std'     => 'off',
					'type'    => 'on-off',
					'section' => 'manga_reading',
				),
				array(
					'id'      => 'manga_reading_discussion',
					'label'   => esc_html__( 'Enable Reading Discussion', 'madara' ),
					'desc'    => esc_html__( 'Turn On/Off Reading Discussion for Manga Reading Page. Default Off.', 'madara' ),
					'std'     => 'on',
					'type'    => 'on-off',
					'section' => 'manga_reading',
				),
				array(
					'id'        => 'manga_reading_page_sidebar',
					'label'     => esc_html__( 'Manga Reading Page Sidebar', 'madara' ),
					'desc'      => '',
					'std'       => 'right',
					'type'      => 'radio-image',
					'section'   => 'manga_reading',
					'choices'   => array(
						array(
							'value' => 'left',
							'label' => esc_html__( 'Left', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-left.png' ),
						),
						array(
							'value' => 'right',
							'label' => esc_html__( 'Right', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-right.png' ),
						),
						array(
							'value' => 'full',
							'label' => esc_html__( 'Hidden', 'madara' ),
							'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-hidden.png' ),
						)
					),
					'condition' => 'manga_reading_discussion:is(on)',
				),

				array(
					'id'      => 'manga_reading_style',
					'label'   => esc_html__( 'Reading Style', 'madara' ),
					'desc'    => '',
					'std'     => 'paged',
					'type'    => 'select',
					'section' => 'manga_reading',
					'choices' => array(
						array(
							'value' => 'paged',
							'label' => esc_html__( 'Paged', 'madara' ),
						),
						array(
							'value' => 'list',
							'label' => esc_html__( 'List', 'madara' ),
						),
					),
				),

				array(
					'id'        => 'manga_page_reading_ajax',
					'label'     => esc_html__( 'Page Reading Ajax', 'madara' ),
					'desc'      => '',
					'std'       => 'on',
					'type'      => 'on-off',
					'section'   => 'manga_reading',
					'condition' => 'manga_reading_style:not(list)',
					'desc'      => esc_html__( 'Use Ajax instead of redirecting URL when go to next page on chapter', 'madara' )
				),

				array(
					'id'      => 'manga_reading_sticky_navigation',
					'label'   => esc_html__( 'Sticky Reading Navigation', 'madara' ),
					'desc'    => '',
					'std'     => 'on',
					'type'    => 'on-off',
					'section' => 'manga_reading',
				),

				array(
					'id'      => 'manga_reading_images_per_page',
					'label'   => esc_html__( 'Images Per Page', 'madara' ),
					'desc'    => '',
					'std'     => 'paged',
					'type'    => 'select',
					'section' => 'manga_reading',
					'choices' => array(
						array(
							'value' => '1',
							'label' => esc_html__( '1 image', 'madara' ),
						),
						array(
							'value' => '3',
							'label' => esc_html__( '3 images', 'madara' ),
						),
						array(
							'value' => '6',
							'label' => esc_html__( '6 images', 'madara' ),
						),
						array(
							'value' => '10',
							'label' => esc_html__( '10 images', 'madara' ),
						),
					),
				),
			)
		);
	}

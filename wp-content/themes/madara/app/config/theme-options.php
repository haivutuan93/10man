<?php

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	/**
	 * Custom settings array that will eventually be
	 * passes to the OptionTree Settings API Class.
	 */
	$madara_theme_options = array(
		'contextual_help' => array(
			'content' => array(
				array(
					'id'      => 'option_types_help',
					'title'   => esc_html__( 'Option Types', 'madara' ),
					'content' => '<p>' . esc_html__( 'Help content goes here!', 'madara' ) . '</p>'
				)
			),
			'sidebar' => '<p>' . esc_html__( 'Sidebar content goes here!', 'madara' ) . '</p>'
		),
		'sections'        => array(
			array(
				'id'    => 'general',
				'title' => '<i class="fas fa-cogs"></i>' . esc_html__( 'General', 'madara' ),
			),
			array(
				'id'    => 'theme_layout',
				'title' => '<i class="fas fa-th-large"></i>' . esc_html__( 'General Layout', 'madara' ),
			),
			array(
				'id'    => 'custom_colors',
				'title' => '<i class="fas fa-magic"></i>' . esc_html__( 'Custom Colors', 'madara' ),
			),
			array(
				'id'    => 'custom_fonts',
				'title' => '<i class="fas fa-magic"></i>' . esc_html__( 'Custom Fonts', 'madara' ),
			),
			array(
				'id'    => 'header',
				'title' => '<i class="fas fa-tasks"></i>' . esc_html__( 'Header', 'madara' ),
			),
			array(
				'id'    => 'archives',
				'title' => '<i class="fas fa-th-list"></i>' . esc_html__( 'Blog', 'madara' ),
			),
			array(
				'id'    => 'single_post',
				'title' => '<i class="fas fa-file-text"></i>' . esc_html__( 'Single Post', 'madara' ),
			),
			array(
				'id'    => 'single_page',
				'title' => '<i class="fas fa-file"></i>' . esc_html__( 'Single Page', 'madara' ),
			),
			array(
				'id'    => 'search',
				'title' => '<i class="fas fa-search"></i>' . esc_html__( 'Search', 'madara' ),
			),
			array(
				'id'    => '404',
				'title' => '<i class="fas fa-exclamation-triangle"></i>' . esc_html__( '404', 'madara' ),
			),
			array(
				'id'    => 'social_account',
				'title' => '<i class="fab fa-twitter"></i>' . esc_html__( 'Social Accounts', 'madara' ),
			),
			array(
				'id'    => 'advertising',
				'title' => '<i class="fas fa-lightbulb"></i>' . esc_html__( 'Advertising', 'madara' ),
			),
			array(
				'id'    => 'misc',
				'title' => '<i class="fas fa-sliders"></i>' . esc_html__( 'Misc', 'madara' ),
			)
		),
		'settings'        => array(

			/*
         * General
         * */
			array(
				'id'      => 'logo_image',
				'label'   => esc_html__( 'Logo Image', 'madara' ),
				'desc'    => esc_html__( 'Upload your logo image', 'madara' ),
				'std'     => '',
				'type'    => 'upload',
				'section' => 'general',
			),
			array(
				'id'      => 'retina_logo_image',
				'label'   => esc_html__( 'Retina Logo (optional)', 'madara' ),
				'desc'    => esc_html__( 'Retina logo should be two time bigger than the custom logo. Retina Logo is optional, use this setting if you want to strictly support retina devices.', 'madara' ),
				'std'     => '',
				'type'    => 'upload',
				'section' => 'general',
			),
			array(
				'id'      => 'login_logo_image',
				'label'   => esc_html__( 'Login Logo Image', 'madara' ),
				'desc'    => esc_html__( 'Upload your Admin Login logo image', 'madara' ),
				'std'     => '',
				'type'    => 'upload',
				'section' => 'general',
			),

			/*
	         * Layout
	         *
			 */
			array(
				'id'      => 'body_schema',
				'label'   => esc_html__( 'Body Schema', 'madara' ),
				'desc'    => esc_html__( 'Choose Body Color Schema', 'madara' ),
				'std'     => 'light',
				'type'    => 'select',
				'section' => 'theme_layout',
				'choices' => array(
					array(
						'value' => 'light',
						'label' => esc_html__( 'Light', 'madara' )
					),
					array(
						'value' => 'dark',
						'label' => esc_html__( 'Dark', 'madara' )
					)
				),
			),

			array(
				'id'      => 'main_top_sidebar_container',
				'label'   => esc_html__( 'Main Top Sidebar Container', 'madara' ),
				'desc'    => esc_html__( 'Set container for Main Top Sidebar. Custom width is 1760px', 'madara' ),
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
				'section' => 'theme_layout',
			),

			array(
				'id'      => 'main_top_sidebar_background',
				'label'   => esc_html__( 'Main Top Sidebar Background', 'madara' ),
				'desc'    => esc_html__( 'Upload background image for Main Top Sidebar', 'madara' ),
				'std'     => '',
				'type'    => 'background',
				'section' => 'theme_layout',
			),

			array(
				'id'           => 'main_top_sidebar_spacing',
				'label'        => esc_html__( 'Main Top Sidebar - Padding', 'madara' ),
				'desc'         => esc_html__( 'Padding in Main Bottom Top. Default value is 50 0 20 0 & unit is px', 'madara' ),
				'std'          => '',
				'type'         => 'spacing',
				'section'      => 'theme_layout',
				'min_max_step' => '',
			),

			array(
				'id'      => 'main_top_second_sidebar_container',
				'label'   => esc_html__( 'Main Top Second Sidebar Container', 'madara' ),
				'desc'    => esc_html__( 'Set container for Main Top Second Sidebar. Custom width is 1760px', 'madara' ),
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
				'section' => 'theme_layout',
			),
			array(
				'id'      => 'main_top_second_sidebar_background',
				'label'   => esc_html__( 'Main Top Second Sidebar Background', 'madara' ),
				'desc'    => esc_html__( 'Upload background image for Main Top Second Sidebar', 'madara' ),
				'std'     => '',
				'type'    => 'background',
				'section' => 'theme_layout',
			),

			array(
				'id'           => 'main_top_second_sidebar_spacing',
				'label'        => esc_html__( 'Main Top Second Sidebar - Padding', 'madara' ),
				'desc'         => esc_html__( 'Padding in Main Top Second Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
				'std'          => '',
				'type'         => 'spacing',
				'section'      => 'theme_layout',
				'min_max_step' => '',
			),

			array(
				'id'      => 'main_bottom_sidebar_container',
				'label'   => esc_html__( 'Main Bottom Sidebar Container', 'madara' ),
				'desc'    => esc_html__( 'Set container for Main bottom Sidebar. Custom width is 1760px', 'madara' ),
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
				'section' => 'theme_layout',
			),
			array(
				'id'      => 'main_bottom_sidebar_background',
				'label'   => esc_html__( 'Main bottom Sidebar Background', 'madara' ),
				'desc'    => esc_html__( 'Upload background image for Main Bottom Sidebar', 'madara' ),
				'std'     => '',
				'type'    => 'background',
				'section' => 'theme_layout',
			),

			array(
				'id'           => 'main_bottom_sidebar_spacing',
				'label'        => esc_html__( 'Main Bottom Sidebar - Padding', 'madara' ),
				'desc'         => esc_html__( 'Padding in Main Bottom Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
				'std'          => '',
				'type'         => 'spacing',
				'section'      => 'theme_layout',
				'min_max_step' => '',
			),

			/*
			 * Custom Color
			 * */

			array(
				'id'      => 'site_custom_colors',
				'label'   => esc_html__( 'Custom Colors', 'madara' ),
				'desc'    => esc_html__( 'Show Custom Colors settings', 'madara' ),
				'std'     => 'off',
				'type'    => 'on-off',
				'section' => 'custom_colors'
			),

			array(
				'id'        => 'main_color',
				'label'     => esc_html__( 'Primary Color (Gradient - Start Color)', 'madara' ),
				'desc'      => esc_html__( 'Choose Primary Color of the theme (Gradient - Start Color). Default is: #eb3349', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'main_color_end',
				'label'     => esc_html__( 'Primary Color (Gradient - End Color)', 'madara' ),
				'desc'      => esc_html__( 'Choose Primary Color of the theme (Gradient - End Color)', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'link_color_hover',
				'label'     => esc_html__( 'Link Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose Link Hover Color of the theme. Default is Primary Color', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'star_color',
				'label'     => esc_html__( 'Star Color', 'madara' ),
				'desc'      => esc_html__( 'Choose Star Color rating in Manga Listing. Default is: #ffd900', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'hot_badges_bg_color',
				'label'     => esc_html__( 'HOT Badges background color', 'madara' ),
				'desc'      => esc_html__( 'Choose Background Color for HOT Badges in Manga Listing', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'new_badges_bg_color',
				'label'     => esc_html__( 'NEW Badges backgroundcolor', 'madara' ),
				'desc'      => esc_html__( 'Choose Background Color for NEW Badges in Manga Listing', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'custom_badges_bg_color',
				'label'     => esc_html__( 'CUSTOM Badges backgroundcolor', 'madara' ),
				'desc'      => esc_html__( 'Choose Background Color for Custom Badges in Manga Listing', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'btn_bg',
				'label'     => esc_html__( 'Button Background', 'madara' ),
				'desc'      => esc_html__( 'Choose default Background Color for Buttons', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'btn_color',
				'label'     => esc_html__( 'Button Text Color', 'madara' ),
				'desc'      => esc_html__( 'Choose default Text Color for Buttons', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'btn_hover_bg',
				'label'     => esc_html__( 'Button Background Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose default Background Hover Color for Buttons', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),

			array(
				'id'        => 'btn_hover_color',
				'label'     => esc_html__( 'Button Text Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose default Text Hover Color for Buttons', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'site_custom_colors:is(on)'
			),


			array(
				'id'      => 'header_custom_colors',
				'label'   => esc_html__( 'Customize Header Colors', 'madara' ),
				'desc'    => esc_html__( 'Change various color settings on Header', 'madara' ),
				'std'     => 'off',
				'type'    => 'on-off',
				'section' => 'custom_colors',
			),

			array(
				'id'        => 'nav_item_color',
				'label'     => esc_html__( 'Navigation - Item Color', 'madara' ),
				'desc'      => esc_html__( 'Choose color for menu items on Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_item_hover_color',
				'label'     => esc_html__( 'Navigation - Item Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose hover color for menu items on Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_sub_bg',
				'label'     => esc_html__( 'Navigation - Background Color For Sub Menu', 'madara' ),
				'desc'      => esc_html__( 'Choose background color for sub menu of Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_sub_bg_border_color',
				'label'     => esc_html__( 'Navigation - Sub Menu Item Border Color', 'madara' ),
				'desc'      => esc_html__( 'Choose color for sub menu item border color', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_sub_item_color',
				'label'     => esc_html__( 'Navigation - Sub Menu Item Color', 'madara' ),
				'desc'      => esc_html__( 'Choose color for sub menu item of Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_sub_item_hover_color',
				'label'     => esc_html__( 'Navigation - Sub Menu Item Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose hover color for sub menu item of Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'        => 'nav_sub_item_hover_bg',
				'label'     => esc_html__( 'Navigation - Sub Menu Item Hover Background Color', 'madara' ),
				'desc'      => esc_html__( 'Choose hover background color for sub menu item of Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_custom_colors:is(on)'
			),

			array(
				'id'      => 'header_bottom_custom_colors',
				'label'   => esc_html__( 'Customize Header Bottom Colors', 'madara' ),
				'desc'    => esc_html__( 'Change various color settings on Header Bottom', 'madara' ),
				'std'     => 'off',
				'type'    => 'on-off',
				'section' => 'custom_colors',
			),
			array(
				'id'        => 'header_bottom_bg',
				'label'     => esc_html__( 'Header Bottom Background', 'madara' ),
				'desc'      => esc_html__( 'Choose background color for the Header Bottom', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),
			array(
				'id'        => 'bottom_nav_item_color',
				'label'     => esc_html__( 'Second Navigation - Item Color', 'madara' ),
				'desc'      => esc_html__( 'Choose color for menu items on Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'        => 'bottom_nav_item_hover_color',
				'label'     => esc_html__( 'Second Navigation - Item Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose hover color for menu items on Second Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'        => 'bottom_nav_sub_bg',
				'label'     => esc_html__( 'Second Navigation - Background Color For Sub Menu', 'madara' ),
				'desc'      => esc_html__( 'Choose background color for sub menu of Second Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'        => 'bottom_nav_sub_item_color',
				'label'     => esc_html__( 'Second Navigation - Sub Menu Item Color', 'madara' ),
				'desc'      => esc_html__( 'Choose color for sub menu item of Second Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'        => 'bottom_nav_sub_item_hover_color',
				'label'     => esc_html__( 'Second Navigation - Sub Menu Item Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Choose hover color for sub menu item of Second Navigation', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'        => 'bottom_nav_sub_border_bottom',
				'label'     => esc_html__( 'Second Navigation - Border Bottom Color For Sub Menu', 'madara' ),
				'desc'      => esc_html__( 'Choose border bottom color for sub menu of Second Navigation. Default is Primary Color', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'header_bottom_custom_colors:is(on)'
			),

			array(
				'id'      => 'mobile_menu_custom_color',
				'label'   => esc_html__( 'Mobile Menu Custom Color', 'madara' ),
				'desc'    => esc_html__( 'Change various color settings on Mobile Menu', 'madara' ),
				'std'     => 'off',
				'type'    => 'on-off',
				'section' => 'custom_colors',
			),

			array(
				'id'        => 'canvas_menu_background',
				'label'     => esc_html__( 'Canvas Menu - Background', 'madara' ),
				'desc'      => esc_html__( 'Set Background Color of Canvas Menu', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'mobile_menu_custom_color:is(on)'
			),

			array(
				'id'        => 'canvas_menu_color',
				'label'     => esc_html__( 'Canvas Menu - Menu Item Color', 'madara' ),
				'desc'      => esc_html__( 'Set Color of Item of Canvas Menu', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'mobile_menu_custom_color:is(on)'
			),

			array(
				'id'        => 'canvas_menu_hover',
				'label'     => esc_html__( 'Canvas Menu - Menu Item Hover Color', 'madara' ),
				'desc'      => esc_html__( 'Set Hover Color of Item of Canvas Menu', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'custom_colors',
				'condition' => 'mobile_menu_custom_color:is(on)'
			),

			/*
			* Typography
			* */
			array(
				'id'       => 'custom_gooogle_font',
				'label'    => esc_html__( 'Custom Google Fonts', 'madara' ),
				'desc'     => esc_html__( 'Load custom Google Fonts to be used in Typography shortcode or your own custom code', 'madara' ),
				'std'      => '',
				'type'     => 'google-fonts',
				'section'  => 'custom_fonts',
				'operator' => 'and'
			),

			array(
				'id'       => 'font_using_custom',
				'label'    => esc_html__( 'Custom Font Settings', 'madara' ),
				'desc'     => esc_html__( 'Customize default Font Settings', 'madara' ),
				'std'      => 'off',
				'type'     => 'on-off',
				'section'  => 'custom_fonts',
				'operator' => 'and',
			),
			array(
				'id'        => 'main_font_on_google',
				'label'     => esc_html__( 'Use Google Font for Main Font', 'madara' ),
				'desc'      => esc_html__( 'If you use Google Font for Main Font Family, turn this on', 'madara' ),
				'std'       => 'on',
				'type'      => 'on-off',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'main_font_google_family',
				'label'     => esc_html__( 'Main Font Family', 'madara' ),
				'desc'      => esc_html__( 'Choose Google Fonts for Main Font', 'madara' ),
				'std'       => '',
				'type'      => 'google-fonts',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),main_font_on_google:is(on)'
			),
			array(
				'id'        => 'main_font_family',
				'label'     => esc_html__( 'Main Font Family', 'madara' ),
				'desc'      => esc_html__( 'Enter name of font family here', 'madara' ),
				'std'       => '',
				'type'      => 'text',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),main_font_on_google:is(off)'
			),
			array(
				'id'           => 'main_font_size',
				'label'        => esc_html__( 'Main Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Size. Default is 14px', 'madara' ),
				'std'          => '14',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,20,1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'main_font_weight',
				'label'     => esc_html__( 'Main Font Weight', 'madara' ),
				'desc'      => esc_html__( 'Choose Font Weight.', 'madara' ),
				'std'       => '',
				'type'      => 'select',
				'section'   => 'custom_fonts',
				'choices'   => array(
					array(
						'value' => 'normal',
						'label' => esc_html__( 'Normal', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'bold',
						'label' => esc_html__( 'Bold', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'bolder',
						'label' => esc_html__( 'Bolder', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'initial',
						'label' => esc_html__( 'Initial', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'lighter',
						'label' => esc_html__( 'Lighter', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '100',
						'label' => esc_html__( '100', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '200',
						'label' => esc_html__( '200', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '300',
						'label' => esc_html__( '300', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '400',
						'label' => esc_html__( '400', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '400',
						'label' => esc_html__( '500', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '600',
						'label' => esc_html__( '600', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '700',
						'label' => esc_html__( '700', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '800',
						'label' => esc_html__( '800', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '900',
						'label' => esc_html__( '900', 'madara' ),
						'src'   => ''
					),
				),
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),
			array(
				'id'           => 'main_font_line_height',
				'label'        => esc_html__( 'Main Font Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height. Default is 1.5', 'madara' ),
				'std'          => '1.5',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'heading_font_on_google',
				'label'     => esc_html__( 'Use Google Font for Heading Font', 'madara' ),
				'desc'      => esc_html__( 'If you use Google Font for Heading Font Family, turn this on', 'madara' ),
				'std'       => 'on',
				'type'      => 'on-off',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'heading_font_google_family',
				'label'     => esc_html__( 'Heading Font Family', 'madara' ),
				'desc'      => esc_html__( 'Heading Font is used for all heading tags (ie. H1, H2, H3, H4, H5, H6)', 'madara' ),
				'std'       => '',
				'type'      => 'google-fonts',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),heading_font_on_google:is(on)'
			),
			array(
				'id'        => 'heading_font_family',
				'label'     => esc_html__( 'Heading Font Family', 'madara' ),
				'desc'      => esc_html__( 'Heading Font is used for all heading tags (ie. H1, H2, H3, H4, H5, H6). Enter name of font family here', 'madara' ),
				'std'       => '',
				'type'      => 'text',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),heading_font_on_google:is(off)'
			),
			array(
				'id'           => 'heading_font_size_h1',
				'label'        => esc_html__( 'H1 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H1. Default is 34px', 'madara' ),
				'std'          => '34',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '20,80,1',
				'condition'    => 'font_using_custom:is(on)'
			),
			array(
				'id'           => 'h1_line_height',
				'label'        => esc_html__( 'H1 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h1_font_weight',
				'label'        => esc_html__( 'H1 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '600',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'heading_font_size_h2',
				'label'        => esc_html__( 'H2 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H2. Default is 30px', 'madara' ),
				'std'          => '30',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '20,80,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h2_line_height',
				'label'        => esc_html__( 'H2 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h2_font_weight',
				'label'        => esc_html__( 'H2 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '600',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'heading_font_size_h3',
				'label'        => esc_html__( 'H3 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H3. Default is 24px', 'madara' ),
				'std'          => '24',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,60,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h3_line_height',
				'label'        => esc_html__( 'H3 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h3_font_weight',
				'label'        => esc_html__( 'H3 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '600',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'heading_font_size_h4',
				'label'        => esc_html__( 'H4 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H4. Default is 18px', 'madara' ),
				'std'          => '18',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,40,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h4_line_height',
				'label'        => esc_html__( 'H4 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h4_font_weight',
				'label'        => esc_html__( 'H4 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '600',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'heading_font_size_h5',
				'label'        => esc_html__( 'H5 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H5. Default is 16px', 'madara' ),
				'std'          => '16',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,30,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h5_line_height',
				'label'        => esc_html__( 'H5 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h5_font_weight',
				'label'        => esc_html__( 'H5 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '600',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'heading_font_size_h6',
				'label'        => esc_html__( 'H6 - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for H6. Default is 14px', 'madara' ),
				'std'          => '14',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,20,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h6_line_height',
				'label'        => esc_html__( 'H6 - Line Height', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Line Height.  Default is 1.4em', 'madara' ),
				'std'          => '1.4',
				'type'         => 'numeric-slider',
				'min_max_step' => '1,3,0.1',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'h6_font_weight',
				'label'        => esc_html__( 'H6 - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '500',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'        => 'navigation_font_on_google',
				'label'     => esc_html__( 'Use Google Font for Navigation', 'madara' ),
				'desc'      => esc_html__( 'If you use Google Font for Navigation Items, turn this on', 'madara' ),
				'std'       => 'on',
				'type'      => 'on-off',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'navigation_font_google_family',
				'label'     => esc_html__( 'Navigation - Google Font', 'madara' ),
				'desc'      => esc_html__( 'Choose font to be used for Navigation Items', 'madara' ),
				'std'       => '',
				'type'      => 'google-fonts',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),navigation_font_on_google:is(on)'
			),
			array(
				'id'        => 'navigation_font_family',
				'label'     => esc_html__( 'Navigation - Font Family', 'madara' ),
				'desc'      => esc_html__( 'Enter name of font family to be used for Navigation Items', 'madara' ),
				'std'       => '',
				'type'      => 'text',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),navigation_font_on_google:is(off)'
			),
			array(
				'id'           => 'navigation_font_size',
				'label'        => esc_html__( 'Navigation - Font Size', 'madara' ),
				'desc'         => esc_html__( 'Choose font size for Navigation Items. Default is 14px', 'madara' ),
				'std'          => '14',
				'section'      => 'custom_fonts',
				'type'         => 'numeric-slider',
				'min_max_step' => '10,26,1',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'           => 'navigation_font_weight',
				'label'        => esc_html__( 'Navigation - Font Weight', 'madara' ),
				'desc'         => esc_html__( 'Choose Font Weight', 'madara' ),
				'std'          => '400',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,900,100',
				'section'      => 'custom_fonts',
				'operator'     => 'and',
				'condition'    => 'font_using_custom:is(on)'
			),

			array(
				'id'        => 'meta_font_on_google',
				'label'     => esc_html__( 'Use Google Font for Meta Font', 'madara' ),
				'desc'      => esc_html__( 'If you use Google Font for Meta Font Family, turn this on', 'madara' ),
				'std'       => 'on',
				'type'      => 'on-off',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),
			array(
				'id'        => 'meta_font_google_family',
				'label'     => esc_html__( 'Meta Font Family', 'madara' ),
				'desc'      => esc_html__( 'Meta Font is used for all meta tags', 'madara' ),
				'std'       => '',
				'type'      => 'google-fonts',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),meta_font_on_google:is(on)'
			),
			array(
				'id'        => 'meta_font_family',
				'label'     => esc_html__( 'Meta Font Family', 'madara' ),
				'desc'      => esc_html__( 'Meta Font is used for all meta tags. Enter name of font family here', 'madara' ),
				'std'       => '',
				'type'      => 'text',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on),meta_font_on_google:is(off)'
			),

			array(
				'id'        => 'custom_font_1',
				'label'     => esc_html__( 'Custom Font 1', 'madara' ),
				'desc'      => esc_html__( 'Upload your own font and enter name "custom_font_1" in "Main Font Family or Special Font Family" setting above', 'madara' ),
				'std'       => '',
				'type'      => 'upload',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),

			array(
				'id'        => 'custom_font_2',
				'label'     => esc_html__( 'Custom Font 2', 'madara' ),
				'desc'      => esc_html__( 'Upload your own font and enter name "custom_font_2" in "Main Font Family, Heading Font Family or Meta Font Family" setting above', 'madara' ),
				'std'       => '',
				'type'      => 'upload',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),

			array(
				'id'        => 'custom_font_3',
				'label'     => esc_html__( 'Custom Font 3', 'madara' ),
				'desc'      => esc_html__( 'Upload your own font and enter name "custom_font_3" in "Main Font Family, Heading Font Family or Meta Font Family" setting above', 'madara' ),
				'std'       => '',
				'type'      => 'upload',
				'section'   => 'custom_fonts',
				'operator'  => 'and',
				'condition' => 'font_using_custom:is(on)'
			),

			array(
				'id'      => 'header_style',
				'label'   => esc_html__( 'Header Style', 'madara' ),
				'desc'    => esc_html__( 'Choose Header style. Custom width is 1760px', 'madara' ),
				'std'     => 1,
				'type'    => 'radio-image',
				'section' => 'header',
				'choices' => array(
					array(
						'value' => '1',
						'label' => esc_html__( 'Container', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/header/header-container.png' ),
					),
					array(
						'value' => '2',
						'label' => esc_html__( 'Custom Width', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/header/header-custom-width.png' ),
					),
				),
			),

			array(
				'id'      => 'nav_sticky',
				'label'   => esc_html__( 'Sticky Menu', 'madara' ),
				'desc'    => esc_html__( 'Enable/ Disable the Sticky Menu', 'madara' ),
				'std'     => 1,
				'type'    => 'select',
				'section' => 'header',
				'choices' => array(
					array(
						'value' => 0,
						'label' => esc_html__( 'Disable', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 1,
						'label' => esc_html__( 'Always sticky', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 2,
						'label' => esc_html__( 'When page is scrolled up', 'madara' ),
						'src'   => ''
					)
				),
			),

			array(
				'id'      => 'header_bottom_border',
				'label'   => esc_html__( 'Header Bottom - Border Bottom', 'madara' ),
				'desc'    => esc_html__( 'Enable border bottom of the Header Bottom', 'madara' ),
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'header',
			),

			/*
            * Archives
            * */
			array(
				'id'      => 'archive_sidebar',
				'label'   => esc_html__( 'Blog Sidebar', 'madara' ),
				'desc'    => '',
				'std'     => 'right',
				'type'    => 'radio-image',
				'section' => 'archives',
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
				'id'      => 'archive_heading_text',
				'label'   => esc_html__( 'Blog Heading Text', 'madara' ),
				'desc'    => esc_html__( 'Appear in Blog Listing', 'madara' ),
				'type'    => 'text',
				'section' => 'archives',
			),
			array(
				'id'      => 'archive_heading_icon',
				'label'   => esc_html__( 'Blog Heading Icon', 'madara' ),
				"desc"    => esc_html__( "Icon class, for example 'fa fa-home'", "madara" ) . '</br><a href="http://fontawesome.io/icons/" target="_blank">' . esc_html__( "Font Awesome", "madara" ) . '</a>, <a href="http://ionicons.com/" target="_blank">' . esc_html__( "Ionicons", "madara" ) . '</a>',
				'type'    => 'text',
				'section' => 'archives',
			),

			array(
				'id'      => 'archive_margin_top',
				'label'   => esc_html__( 'Blog Margin Top', 'madara' ),
				"desc"    => esc_html__( "Margin Top in Blog Listing Content. Default's 50 (in pixel)", "madara" ),
				'std'     => '',
				'type'    => 'text',
				'section' => 'archives',
			),

			array(
				'id'      => 'archive_content_columns',
				'label'   => esc_html__( 'Blog Content Columns', 'madara' ),
				'desc'    => esc_html__( 'Columns number of Blog Post', 'madara' ),
				'type'    => 'select',
				'section' => 'archives',
				'choices' => array(
					array(
						'value' => '3',
						'label' => esc_html__( '3 Columns', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '2',
						'label' => esc_html__( '2 Columns', 'madara' ),
						'src'   => ''
					),
				)
			),
			array(
				'id'        => 'archive_navigation',
				'label'     => esc_html__( 'Blog Navigation', 'madara' ),
				'desc'      => esc_html__( 'Choose type of navigation for blog and any listing page. For WP PageNavi, you will need to install WP PageNavi plugin', 'madara' ),
				'std'       => 'default',
				'type'      => 'select',
				'section'   => 'archives',
				'rows'      => '',
				'post_type' => '',
				'taxonomy'  => '',
				'class'     => '',
				'choices'   => array(
					array(
						'value' => 'default',
						'label' => esc_html__( 'Default', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'ajax',
						'label' => esc_html__( 'Ajax', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => 'wp_pagenavi',
						'label' => esc_html__( 'WP PageNavi', 'madara' ),
						'src'   => ''
					)
				)
			),
			array(
				'id'      => 'archive_post_excerpt',
				'label'   => esc_html__( 'Posts Excerpt', 'madara' ),
				'desc'    => esc_html__( 'Show Posts Excerpt in Blog Listing', 'madara' ),
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'archives',
			),

			/*
			 * Single Post
			 * */

			array(
				'id'      => 'single_sidebar',
				'label'   => esc_html__( 'Sidebar', 'madara' ),
				'desc'    => '',
				'std'     => 'right',
				'type'    => 'radio-image',
				'section' => 'single_post',
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
				'id'      => 'single_tags',
				'label'   => esc_html__( 'Tags', 'madara' ),
				'desc'    => esc_html__( 'Show Tags list', 'madara' ),
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'single_post',
			),
			array(
				'id'      => 'single_category',
				'label'   => esc_html__( 'Post Category', 'madara' ),
				'desc'    => esc_html__( 'Show Category list', 'madara' ),
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'single_post',
			),

			/*
         * Single Page
         * */
			array(
				'id'           => 'page_sidebar',
				'label'        => esc_html__( 'Sidebar', 'madara' ),
				'desc'         => '',
				'std'          => 'right',
				'type'         => 'radio-image',
				'section'      => 'single_page',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'choices'      => array(
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
			//Page Breadcumb
			//Page Comments
			array(
				'id'       => 'page_comments',
				'label'    => esc_html__( 'Enable Comments by default', 'madara' ),
				'desc'     => esc_html__( 'Enable Comment Panel under Single Pages', 'madara' ),
				'std'      => 'on',
				'type'     => 'on-off',
				'section'  => 'single_page',
				'operator' => 'and'
			),

			/*
	         * Search
	         * */
			array(
				'id'      => 'search_header_background',
				'label'   => esc_html__( 'Search Header Background', 'madara' ),
				'desc'    => esc_html__( 'Upload background image for Header of Search Page', 'madara' ),
				'std'     => '',
				'type'    => 'background',
				'section' => 'search',
			),

			/*
	         * 404 page
	         * */
			array(
				'id'      => 'page404_head_tag',
				'label'   => esc_html__( 'Head Title Tag', 'madara' ),
				'desc'    => esc_html__( 'Content of Title Tag (to be appeared on browser Tab Name)', 'madara' ),
				'std'     => '',
				'type'    => 'text',
				'section' => '404',
			),
			array(
				'id'      => 'page404_featured_image',
				'label'   => esc_html__( 'Page Featured Image', 'madara' ),
				'desc'    => esc_html__( 'Upload your Featured Image into 404 Page', 'madara' ),
				'std'     => '',
				'type'    => 'upload',
				'section' => '404',
			),
			array(
				'id'      => 'page404_title',
				'label'   => esc_html__( 'Page Title', 'madara' ),
				'desc'    => esc_html__( 'Title of the Page', 'madara' ),
				'std'     => '',
				'type'    => 'text',
				'section' => '404',
			),
			array(
				'id'      => 'page404_content',
				'label'   => esc_html__( 'Page Content', 'madara' ),
				'desc'    => esc_html__( 'Content of the Page', 'madara' ),
				'std'     => '',
				'type'    => 'textarea',
				'section' => '404',
				'rows'    => '8',
			),
			/*
         * Advertising
         * */
			array(
				'id'       => 'adsense_id',
				'label'    => esc_html__( 'Google AdSense Publisher ID', 'madara' ),
				'desc'     => esc_html__( 'Enter your Google AdSense Publisher ID', 'madara' ),
				'std'      => '',
				'type'     => 'text',
				'section'  => 'advertising',
				'operator' => 'and'
			),
			/*
         * Misc
         * */
			array(
				'id'      => 'copyright',
				'label'   => esc_html__( 'Copyright Text', 'madara' ),
				'desc'    => esc_html__( 'Appear in Footer', 'madara' ),
				'type'    => 'text',
				'section' => 'misc'
			),
			array(
				'id'      => 'echo_meta_tags',
				'label'   => esc_html__( 'SEO - Echo Meta Tags', 'madara' ),
				'desc'    => esc_html__( 'By default, Madara generates its own SEO meta tags (for example: Facebook Meta Tags). If you are using another SEO plugin like YOAST or a Facebook plugin, you can turn off this option', 'madara' ),
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'misc',
			),

			array(
				'id'       => 'lazyload',
				'label'    => esc_html__( 'Lazyload', 'madara' ),
				'desc'     => esc_html__( 'Enable to use Image Lazyload.', 'madara' ),
				'std'      => 'off',
				'type'     => 'on-off',
				'section'  => 'misc',
				'operator' => 'and'
			),

			array(
				'id'           => 'scroll_effect',
				'label'        => esc_html__( 'Enable Smooth Scroll Effect', 'madara' ),
				'desc'         => '',
				'std'          => 'off',
				'type'         => 'on-off',
				'section'      => 'misc',
				'min_max_step' => '',
			),

			array(
				'id'           => 'go_to_top',
				'label'        => esc_html__( 'Enable Go To Top button', 'madara' ),
				'desc'         => '',
				'std'          => 'off',
				'type'         => 'on-off',
				'section'      => 'misc',
				'min_max_step' => '',
			),

			array(
				'id'       => 'loading_fontawesome',
				'label'    => esc_html__( 'Turn On/Off loading FontAwesome', 'madara' ),
				'desc'     => esc_html__( 'If you don\'t use FontAwesome (a Font Icons library), you can turn it off to save bandwidth', 'madara' ),
				'std'      => 'on',
				'type'     => 'on-off',
				'section'  => 'misc',
				'operator' => 'and'
			),

			array(
				'id'       => 'loading_ionicons',
				'label'    => esc_html__( 'Turn On/Off loading Ionicons', 'madara' ),
				'desc'     => esc_html__( 'If you don\'t use Ionicons (a Font Icons library), you can turn it off to save bandwidth', 'madara' ),
				'std'      => 'on',
				'type'     => 'on-off',
				'section'  => 'misc',
				'operator' => 'and'
			),

			array(
				'id'       => 'loading_ct_icons',
				'label'    => esc_html__( 'Turn On/Off loading CT-Icons', 'madara' ),
				'desc'     => esc_html__( 'If you don\'t use CT-Icons (a Font Icons library), you can turn it off to save bandwidth', 'madara' ),
				'std'      => 'on',
				'type'     => 'on-off',
				'section'  => 'misc',
				'operator' => 'and'
			),

			array(
				'id'      => 'custom_css',
				'label'   => esc_html__( 'Custom CSS', 'madara' ),
				'desc'    => esc_html__( 'Enter custom CSS. Ex: <i>.class{ font-size: 13px; }</i>', 'madara' ),
				'std'     => '',
				'type'    => 'css',
				'section' => 'misc',
				'rows'    => '5',
			),
			array(
				'id'       => 'facebook_app_id',
				'label'    => esc_html__( 'Facebook App ID', 'madara' ),
				'desc'     => esc_html__( '(Optional) Enter your Facebook App ID. It is useful when you share your post on Facebook', 'madara' ),
				'std'      => '',
				'type'     => 'text',
				'section'  => 'misc',
				'operator' => 'and'
			),
			array(
				'id'      => 'static_icon',
				'label'   => esc_html__( 'Default Heading Icon', 'madara' ),
				'desc'    => esc_html__( 'Default Heading Icon in some heading position. Default is "ion-ios-star"', 'madara' ) . '<br/><a href="http://ionicons.com/" target="_blank">' . esc_html__( 'IonIcons', 'madara' ) . '</a><br/><a href="http://fontawesome.io/icons/" target="_blank">' . esc_html__( 'FontAwesome', 'madara' ) . '</a>',
				'type'    => 'text',
				'section' => 'misc'
			),

			array(
				'id'      => 'pre_loading',
				'label'   => esc_html__( 'Pre-loading Effect', 'madara' ),
				'desc'    => esc_html__( 'Enable Pre-loading Effect', 'madara' ),
				'std'     => '-1',
				'type'    => 'select',
				'section' => 'misc',
				'rows'    => '',
				'choices' => array(
					array(
						'value' => '-1',
						'label' => esc_html__( 'Disable All', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '1',
						'label' => esc_html__( 'Enable All', 'madara' ),
						'src'   => ''
					),
					array(
						'value' => '2',
						'label' => esc_html__( 'Front-page Only', 'madara' ),
						'src'   => ''
					)
				),
			),

			array(
				'id'        => 'pre_loading_logo',
				'label'     => esc_html__( 'Pre-loading Logo', 'madara' ),
				'desc'      => esc_html__( 'Preloading Logo. If not selected, Logo Image at Theme Options > General > Logo Image will be used', 'madara' ),
				'std'       => '',
				'type'      => 'upload',
				'section'   => 'misc',
				'condition' => 'pre_loading:not(-1)'
			),

			array(
				'id'        => 'pre_loading_bg_color',
				'label'     => esc_html__( 'Pre-loading Background Color', 'madara' ),
				'desc'      => esc_html__( 'Default is #eb3349', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'misc',
				'condition' => 'pre_loading:not(-1)'
			),
			array(
				'id'        => 'pre_loading_icon_color',
				'label'     => esc_html__( 'Pre-loading Icon Color', 'madara' ),
				'desc'      => esc_html__( 'Default is #ffffff', 'madara' ),
				'std'       => '',
				'type'      => 'colorpicker',
				'section'   => 'misc',
				'condition' => 'pre_loading:not(-1)'
			),

			array(
				'id'           => 'ajax_loading_effect',
				'label'        => esc_html__( 'Preloading Icon', 'madara' ),
				'desc'         => '',
				'std'          => 'ball-grid-pulse',
				'type'         => 'radio-image',
				'section'      => 'misc',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => 'pre_loading:not(-1)',
				'choices'      => array(
					array(
						'value' => 'ball-pulse',
						'label' => esc_html__( 'Ball Pulse', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-pulse.gif' ),
					),
					array(
						'value' => 'ball-pulse-sync',
						'label' => esc_html__( 'Ball Pulse Sync', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-pulse-sync.gif' ),
					),
					array(
						'value' => 'ball-beat',
						'label' => esc_html__( 'Ball Beat', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-beat.gif' ),
					),
					array(
						'value' => 'ball-rotate',
						'label' => esc_html__( 'Ball Rotate', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-rotate.gif' ),
					),
					array(
						'value' => 'ball-grid-pulse',
						'label' => esc_html__( 'Ball Grid Pulse', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-grid-pulse.gif' ),
					),
					array(
						'value' => 'ball-grid-beat',
						'label' => esc_html__( 'Ball Grid Beat', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-grid-beat.gif' ),
					),
					array(
						'value' => 'ball-clip-rotate',
						'label' => esc_html__( 'Ball Clip Rotate', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-clip-rotate.gif' ),
					),
					array(
						'value' => 'ball-clip-rotate-pulse',
						'label' => esc_html__( 'Ball Clip Rotate Pulse', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-clip-rotate-pulse.gif' ),
					),
					array(
						'value' => 'ball-clip-rotate-multiple',
						'label' => esc_html__( 'Ball Clip Rotate Multiple', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-clip-rotate-multiple.gif' ),
					),
					array(
						'value' => 'ball-pulse-rise',
						'label' => esc_html__( 'Ball Pulse Rise', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-pulse-rise.gif' ),
					),
					array(
						'value' => 'cube-transition',
						'label' => esc_html__( 'Cube Transition', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/cube-transition.gif' ),
					),
					array(
						'value' => 'ball-zig-zag',
						'label' => esc_html__( 'Ball Zig Zag', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-zig-zag.gif' ),
					),
					array(
						'value' => 'ball-zig-zag-deflect',
						'label' => esc_html__( 'Ball Zig Zag Deflect', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-zig-zag-deflect.gif' ),
					),
					array(
						'value' => 'ball-triangle-path',
						'label' => esc_html__( 'Ball Triangle Path', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-triangle-path.gif' ),
					),
					array(
						'value' => 'line-scale',
						'label' => esc_html__( 'Line Scale', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/line-scale.gif' ),
					),
					array(
						'value' => 'line-scale-party',
						'label' => esc_html__( 'Line Scale Party', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/line-scale-party.gif' ),
					),
					array(
						'value' => 'line-scale-pulse-out',
						'label' => esc_html__( 'Line Scale Pulse Out', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/line-scale-pulse-out.gif' ),
					),
					array(
						'value' => 'line-scale-pulse-out-rapid',
						'label' => esc_html__( 'Line Scale Pulse Put Rapid', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/line-scale-pulse-out-rapid.gif' ),
					),
					array(
						'value' => 'ball-scale',
						'label' => esc_html__( 'Ball Scale', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-scale.gif' ),
					),
					array(
						'value' => 'ball-scale-multiple',
						'label' => esc_html__( 'Ball Scale Multiple', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-scale-multiple.gif' ),
					),
					array(
						'value' => 'ball-scale-ripple',
						'label' => esc_html__( 'Ball Scale Ripple', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-scale-ripple.gif' ),
					),
					array(
						'value' => 'ball-scale-ripple-multiple',
						'label' => esc_html__( 'Ball Scale Ripple Multiple', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-scale-ripple-multiple.gif' ),
					),
					array(
						'value' => 'ball-spin-fade-loader',
						'label' => esc_html__( 'Ball Spin Fade Loader', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/ball-spin-fade-loader.gif' ),
					),
					array(
						'value' => 'line-spin-fade-loader',
						'label' => esc_html__( 'Line Spin Fade Loader', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/line-spin-fade-loader.gif' ),
					),
					array(
						'value' => 'triangle-skew-spin',
						'label' => esc_html__( 'Triangle Skew Spin', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/triangle-skew-spin.gif' ),
					),
					array(
						'value' => 'semi-circle-spin',
						'label' => esc_html__( 'Semi Circle Spin', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/semi-circle-spin.gif' ),
					),
					array(
						'value' => 'square-spin',
						'label' => esc_html__( 'Square Spin', 'madara' ),
						'src'   => get_parent_theme_file_uri( '/images/options/ajax-loading/square-spin.gif' ),
					),
				),
			),

			/*
         * End
         * */
		)
	);

	/* Add settings panel for Thumb Sizes */
	$thumb_sizes = App\config\ThemeConfig::getAllThumbSizes();
	if ( is_array( $thumb_sizes ) ) {
		foreach ( $thumb_sizes as $size => $config ) {
			$custom_settings['settings'][] = array(
				'id'      => $size,
				'label'   => $config[3],
				'desc'    => $config[4],
				'std'     => 'on',
				'type'    => 'on-off',
				'section' => 'misc',
			);
		}
	}

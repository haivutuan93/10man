<?php

	/**
	 * Initialize the Page Metaboxes. See /option-tree/assets/theme-mode/demo-meta-boxes.php for reference
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	add_action( 'admin_init', 'madara_page_MetaBoxes' );

	if ( ! function_exists( 'madara_page_MetaBoxes' ) ) {
		function madara_page_MetaBoxes() {
			$page_meta_boxes = array(
				'id'       => 'page_meta_box',
				'title'    => esc_html__( 'Page Settings', 'madara' ),
				'desc'     => '',
				'pages'    => array( 'page' ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'id'      => 'page_sidebar',
						'label'   => esc_html__( 'Page Sidebar', 'madara' ),
						'desc'    => esc_html__( 'Choose Sidebar Layout for Page', 'madara' ),
						'std'     => 'default',
						'type'    => 'radio-image',
						'class'   => '',
						'choices' => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
								'src'   => get_parent_theme_file_uri( '/images/options/default.png' ),
							),
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
						)
					),
				)
			);

			$front_page_meta_boxes = array(
				'id'       => 'frontpage_meta_box',
				'title'    => esc_html__( 'Front Page Settings', 'madara' ),
				'desc'     => '',
				'pages'    => array( 'page' ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(

					/*
					 * Page Content Tab
					 * */
					array(
						'label' => esc_html__( 'Page Content', 'madara' ),
						'id'    => 'page_content_tab',
						'type'  => 'tab'
					),
					//Page Content
					array(
						'id'      => 'page_content',
						'label'   => esc_html__( 'Page Content', 'madara' ),
						'desc'    => 'Choose the content source',
						'std'     => 'page_content',
						'type'    => 'select',
						'choices' => array(
							array(
								'value' => 'page_content',
								'label' => esc_html__( 'Page Content', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'blog',
								'label' => esc_html__( 'Blog', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'manga',
								'label' => esc_html__( 'Manga', 'madara' ),
								'src'   => ''
							),
						)
					),
					//Blog Style
					array(
						'id'        => 'archive_margin_top',
						'label'     => esc_html__( 'Content Margin Top', 'madara' ),
						"desc"      => esc_html__( "Margin Top in Listing Content. Default's 50 (in pixel)", "madara" ),
						'std'       => '',
						'type'      => 'text',
						'condition' => 'page_content:not(page_content)',
					),
					array(
						'id'        => 'archive_heading_text',
						'label'     => esc_html__( 'Archives Heading Text', 'madara' ),
						'desc'      => esc_html__( 'Appear in Blog Listing or Manga Listing', 'madara' ),
						'type'      => 'text',
						'condition' => 'page_content:not(page_content)',
					),
					array(
						'id'        => 'archive_heading_icon',
						'label'     => esc_html__( 'Archives Heading Icon', 'madara' ),
						"desc"      => esc_html__( "Icon class, for example 'fa fa-home'", "madara" ) . '</br><a href="http://fontawesome.io/icons/" target="_blank">' . esc_html__( "Font Awesome", "madara" ) . '</a>, <a href="http://ionicons.com/" target="_blank">' . esc_html__( "Ionicons", "madara" ) . '</a>',
						'type'      => 'text',
						'condition' => 'page_content:not(page_content)',
					),
					array(
						'id'        => 'archive_content_columns',
						'label'     => esc_html__( 'Blog Content Columns', 'madara' ),
						'desc'      => esc_html__( 'Columns number of Blog Post', 'madara' ),
						'type'      => 'select',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
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
						),
						'condition' => 'page_content:is(blog)',
					),
					array(
						'id'        => 'archive_navigation',
						'label'     => esc_html__( 'Blog Navigation', 'madara' ),
						'desc'      => esc_html__( 'Choose type of navigation for blog and any listing page. For WP PageNavi, you will need to install WP PageNavi plugin', 'madara' ),
						'std'       => 'default',
						'type'      => 'select',
						'rows'      => '',
						'post_type' => '',
						'taxonomy'  => '',
						'class'     => '',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default Theme Options', 'madara' ),
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
						),
						'condition' => 'page_content:not(page_content)',
					),
					array(
						'id'        => 'page_post_count',
						'label'     => esc_html__( 'Post Count', 'madara' ),
						'desc'      => esc_html__( 'Number of posts per page. Default is 10', 'madara' ),
						'std'       => '10',
						'type'      => 'text',
						'condition' => 'page_content:not(page_content)',
					),
					array(
						'id'        => 'page_post_cats',
						'label'     => esc_html__( 'Post Categories', 'madara' ),
						'desc'      => esc_html__( 'Enter category Ids or slugs to get posts from, separated by a comma', 'madara' ),
						'std'       => '',
						'type'      => 'text',
						'condition' => 'page_content:is(blog)',
					),
					array(
						'id'        => 'page_post_tags',
						'label'     => esc_html__( 'Post Tags', 'madara' ),
						'desc'      => esc_html__( 'Enter tags to get posts from, separated by a comma', 'madara' ),
						'std'       => '',
						'type'      => 'text',
						'condition' => 'page_content:is(blog)',
					),
					array(
						'id'        => 'page_post_ids',
						'label'     => esc_html__( 'Post Ids', 'madara' ),
						'desc'      => esc_html__( 'Enter post IDs, separated by a comma.If this param is used, other params are ignored', 'madara' ),
						'std'       => '',
						'type'      => 'text',
						'condition' => 'page_content:is(blog)',
					),
					array(
						'id'        => 'page_post_order',
						'label'     => esc_html__( 'Post Order', 'madara' ),
						'desc'      => esc_html__( 'Choose the order condition', 'madara' ),
						'std'       => '',
						'type'      => 'select',
						'choices'   => array(
							array(
								'value' => 'DESC',
								'label' => esc_html__( 'Descending', 'madara' )
							),
							array(
								'value' => 'ASC',
								'label' => esc_html__( 'Ascending', 'madara' )
							)
						),
						'condition' => 'page_content:is(blog)',
					),
					array(
						'id'        => 'page_post_orderby',
						'label'     => esc_html__( 'Order By', 'madara' ),
						'desc'      => '',
						'std'       => 'date',
						'type'      => 'select',
						'condition' => 'page_content:not(page_content)',
						'choices'   => array(
							array(
								'value' => 'latest',
								'label' => esc_html__( 'New Post', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'modified',
								'label' => esc_html__( 'Latest Update', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'name',
								'label' => esc_html__( 'Post Title', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'rand',
								'label' => esc_html__( 'Random', 'madara' ),
								'src'   => ''
							)
						)
					),


					/*
					 * Page Content Tab
					 * */
					array(
						'label' => esc_html__( 'Custom Color', 'madara' ),
						'id'    => 'site_custom_colors_tab',
						'type'  => 'tab'
					),
					array(
						'id'    => 'custom_colors',
						'label' => esc_html__( 'Custom Colors', 'madara' ),
						'desc'  => esc_html__( 'Show Custom Colors settings', 'madara' ),
						'std'   => 'off',
						'type'  => 'on-off',
					),

					array(
						'id'        => 'body_schema',
						'label'     => esc_html__( 'Body Schema', 'madara' ),
						'desc'      => esc_html__( 'Choose Body Color Schema', 'madara' ),
						'std'       => 'default',
						'type'      => 'select',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
							array(
								'value' => 'light',
								'label' => esc_html__( 'Light', 'madara' )
							),
							array(
								'value' => 'dark',
								'label' => esc_html__( 'Dark', 'madara' )
							)
						),
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'main_color',
						'label'     => esc_html__( 'Primary Color (Gradient - Start Color)', 'madara' ),
						'desc'      => esc_html__( 'Choose Primary Color of the theme (Gradient - Start Color). Default is: #eb3349', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'main_color_end',
						'label'     => esc_html__( 'Primary Color (Gradient - End Color)', 'madara' ),
						'desc'      => esc_html__( 'Choose Primary Color of the theme (Gradient - End Color)', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'link_color_hover',
						'label'     => esc_html__( 'Link Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose Link Hover Color of the theme. Default is Primary Color', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'star_color',
						'label'     => esc_html__( 'Star Color', 'madara' ),
						'desc'      => esc_html__( 'Choose Star Color rating in Manga Listing. Default is: #ffd900', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'hot_badges_bg_color',
						'label'     => esc_html__( 'HOT Badges background color', 'madara' ),
						'desc'      => esc_html__( 'Choose Background Color for HOT Badges in Manga Listing', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'new_badges_bg_color',
						'label'     => esc_html__( 'NEW Badges backgroundcolor', 'madara' ),
						'desc'      => esc_html__( 'Choose Background Color for NEW Badges in Manga Listing', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'custom_badges_bg_color',
						'label'     => esc_html__( 'CUSTOM Badges backgroundcolor', 'madara' ),
						'desc'      => esc_html__( 'Choose Background Color for Custom Badges in Manga Listing', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'section'   => 'custom_colors',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'btn_bg',
						'label'     => esc_html__( 'Button Background', 'madara' ),
						'desc'      => esc_html__( 'Choose default Background Color for Buttons', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'btn_color',
						'label'     => esc_html__( 'Button Text Color', 'madara' ),
						'desc'      => esc_html__( 'Choose default Text Color for Buttons', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'btn_hover_bg',
						'label'     => esc_html__( 'Button Background Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose default Background Hover Color for Buttons', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					array(
						'id'        => 'btn_hover_color',
						'label'     => esc_html__( 'Button Text Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose default Text Hover Color for Buttons', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'custom_colors:is(on)'
					),

					/*
					 * Header Settings Tab
					 * */
					array(
						'label' => esc_html__( 'Header Settings', 'madara' ),
						'id'    => 'header_settings_tab',
						'type'  => 'tab'
					),
					array(
						'id'      => 'header_style',
						'label'   => esc_html__( 'Header Style', 'madara' ),
						'desc'    => esc_html__( 'Choose Header style. Custom width is 1760px', 'madara' ),
						'std'     => 'default',
						'type'    => 'radio-image',
						'choices' => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
								'src'   => get_parent_theme_file_uri( '/images/options/default.png' ),
							),
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
						'id'      => 'header_bottom_border',
						'label'   => esc_html__( 'Header Bottom - Border Bottom', 'madara' ),
						'desc'    => esc_html__( 'Enable border bottom of the Header Bottom', 'madara' ),
						'std'     => 'default',
						'type'    => 'select',
						'choices' => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
							array(
								'value' => 'on',
								'label' => esc_html__( 'On', 'madara' ),
							),
							array(
								'value' => 'off',
								'label' => esc_html__( 'Off', 'madara' ),
							),
						),

					),
					array(
						'id'    => 'header_colors',
						'label' => esc_html__( 'Customize Header Colors', 'madara' ),
						'desc'  => esc_html__( 'Change various color settings on Header', 'madara' ),
						'std'   => 'off',
						'type'  => 'on-off',
					),

					array(
						'id'        => 'nav_item_color',
						'label'     => esc_html__( 'Navigation - Item Color', 'madara' ),
						'desc'      => esc_html__( 'Choose color for menu items on Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_item_hover_color',
						'label'     => esc_html__( 'Navigation - Item Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose hover color for menu items on Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_sub_bg',
						'label'     => esc_html__( 'Navigation - Background Color For Sub Menu', 'madara' ),
						'desc'      => esc_html__( 'Choose background color for sub menu of Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_sub_bg_border_color',
						'label'     => esc_html__( 'Navigation - Sub Menu Item Border Color', 'madara' ),
						'desc'      => esc_html__( 'Choose color for sub menu item border color', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_sub_item_color',
						'label'     => esc_html__( 'Navigation - Sub Menu Item Color', 'madara' ),
						'desc'      => esc_html__( 'Choose color for sub menu item of Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_sub_item_hover_color',
						'label'     => esc_html__( 'Navigation - Sub Menu Item Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose hover color for sub menu item of Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'        => 'nav_sub_item_hover_bg',
						'label'     => esc_html__( 'Navigation - Sub Menu Item Hover Background Color', 'madara' ),
						'desc'      => esc_html__( 'Choose hover background color for sub menu item of Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_colors:is(on)'
					),

					array(
						'id'    => 'header_bottom_colors',
						'label' => esc_html__( 'Customize Header Bottom Colors', 'madara' ),
						'desc'  => esc_html__( 'Change various color settings on Header Bottom', 'madara' ),
						'std'   => 'off',
						'type'  => 'on-off',
					),
					array(
						'id'        => 'header_bottom_bg',
						'label'     => esc_html__( 'Header Bottom Background', 'madara' ),
						'desc'      => esc_html__( 'Choose background color for the Header Bottom', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),
					array(
						'id'        => 'bottom_nav_item_color',
						'label'     => esc_html__( 'Second Navigation - Item Color', 'madara' ),
						'desc'      => esc_html__( 'Choose color for menu items on Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'        => 'bottom_nav_item_hover_color',
						'label'     => esc_html__( 'Second Navigation - Item Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose hover color for menu items on Second Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'        => 'bottom_nav_sub_bg',
						'label'     => esc_html__( 'Second Navigation - Background Color For Sub Menu', 'madara' ),
						'desc'      => esc_html__( 'Choose background color for sub menu of Second Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'        => 'bottom_nav_sub_item_color',
						'label'     => esc_html__( 'Second Navigation - Sub Menu Item Color', 'madara' ),
						'desc'      => esc_html__( 'Choose color for sub menu item of Second Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'        => 'bottom_nav_sub_item_hover_color',
						'label'     => esc_html__( 'Second Navigation - Sub Menu Item Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Choose hover color for sub menu item of Second Navigation', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'        => 'bottom_nav_sub_border_bottom',
						'label'     => esc_html__( 'Second Navigation - Border Bottom Color For Sub Menu', 'madara' ),
						'desc'      => esc_html__( 'Choose border bottom color for sub menu of Second Navigation. Default is Primary Color', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'header_bottom_colors:is(on)'
					),

					array(
						'id'    => 'mobile_menu_color',
						'label' => esc_html__( 'Mobile Menu Custom Color', 'madara' ),
						'desc'  => esc_html__( 'Change various color settings on Mobile Menu', 'madara' ),
						'std'   => 'off',
						'type'  => 'on-off',
					),

					array(
						'id'        => 'canvas_menu_background',
						'label'     => esc_html__( 'Canvas Menu - Background', 'madara' ),
						'desc'      => esc_html__( 'Set Background Color of Canvas Menu', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'mobile_menu_color:is(on)'
					),

					array(
						'id'        => 'canvas_menu_color',
						'label'     => esc_html__( 'Canvas Menu - Menu Item Color', 'madara' ),
						'desc'      => esc_html__( 'Set Color of Item of Canvas Menu', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'mobile_menu_color:is(on)'
					),

					array(
						'id'        => 'canvas_menu_hover',
						'label'     => esc_html__( 'Canvas Menu - Menu Item Hover Color', 'madara' ),
						'desc'      => esc_html__( 'Set Hover Color of Item of Canvas Menu', 'madara' ),
						'std'       => '',
						'type'      => 'colorpicker',
						'condition' => 'mobile_menu_color:is(on)'
					),

					/*
					 * Sidebar Settings Tab
					 * */
					array(
						'label' => esc_html__( 'Sidebar Settings', 'madara' ),
						'id'    => 'sidebar_settings_tab',
						'type'  => 'tab'
					),
					array(
						'id'    => 'custom_sidebar_settings',
						'label' => esc_html__( 'Custom Sidebar Settings', 'madara' ),
						'desc'  => esc_html__( 'Change various settings of Sidebars', 'madara' ),
						'std'   => 'off',
						'type'  => 'on-off',
					),
					array(
						'id'        => 'main_top_sidebar_container',
						'label'     => esc_html__( 'Main Top Sidebar Container', 'madara' ),
						'desc'      => esc_html__( 'Set container for Main Top Sidebar. Custom width is 1760px', 'madara' ),
						'std'       => 'default',
						'type'      => 'select',
						'class'     => '',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
							array(
								'value' => 'full_width',
								'label' => esc_html__( 'Full-Width', 'madara' ),
							),
							array(
								'value' => 'container',
								'label' => esc_html__( 'Container', 'madara' ),
							),
							array(
								'value' => 'custom_width',
								'label' => esc_html__( 'Custom Width', 'madara' ),
							)
						),
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_top_sidebar_background',
						'label'     => esc_html__( 'Main Top Sidebar Background', 'madara' ),
						'desc'      => esc_html__( 'Upload background image for Main Top Sidebar', 'madara' ),
						'std'       => '',
						'type'      => 'background',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_top_sidebar_spacing',
						'label'     => esc_html__( 'Main Top Sidebar - Padding', 'madara' ),
						'desc'      => esc_html__( 'Padding in Main Bottom Top. Default value is 50 0 20 0 & unit is px', 'madara' ),
						'std'       => '',
						'type'      => 'spacing',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_top_second_sidebar_container',
						'label'     => esc_html__( 'Main Top Second Sidebar Container', 'madara' ),
						'desc'      => esc_html__( 'Set container for Main Top Second Sidebar. Custom width is 1760px', 'madara' ),
						'std'       => 'default',
						'type'      => 'select',
						'class'     => '',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
							array(
								'value' => 'full_width',
								'label' => esc_html__( 'Full-Width', 'madara' ),
							),
							array(
								'value' => 'container',
								'label' => esc_html__( 'Container', 'madara' ),
							),
							array(
								'value' => 'custom_width',
								'label' => esc_html__( 'Custom Width', 'madara' ),
							)
						),
						'condition' => 'custom_sidebar_settings:is(on)'
					),
					array(
						'id'        => 'main_top_second_sidebar_background',
						'label'     => esc_html__( 'Main Top Second Sidebar Background', 'madara' ),
						'desc'      => esc_html__( 'Upload background image for Main Top Second Sidebar', 'madara' ),
						'std'       => '',
						'type'      => 'background',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_top_second_sidebar_spacing',
						'label'     => esc_html__( 'Main Top Second Sidebar - Padding', 'madara' ),
						'desc'      => esc_html__( 'Padding in Main Top Second Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
						'std'       => '',
						'type'      => 'spacing',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_bottom_sidebar_container',
						'label'     => esc_html__( 'Main Bottom Sidebar Container', 'madara' ),
						'desc'      => esc_html__( 'Set container for Main bottom Sidebar. Custom width is 1760px', 'madara' ),
						'std'       => 'default',
						'type'      => 'select',
						'class'     => '',
						'choices'   => array(
							array(
								'value' => 'default',
								'label' => esc_html__( 'Default', 'madara' ),
							),
							array(
								'value' => 'full_width',
								'label' => esc_html__( 'Full-Width', 'madara' ),
							),
							array(
								'value' => 'container',
								'label' => esc_html__( 'Container', 'madara' ),
							),
							array(
								'value' => 'custom_width',
								'label' => esc_html__( 'Custom Width', 'madara' ),
								'src'   => get_parent_theme_file_uri( '/images/options/sidebar/sidebar-custom-width.png' ),
							)
						),
						'condition' => 'custom_sidebar_settings:is(on)'
					),
					array(
						'id'        => 'main_bottom_sidebar_background',
						'label'     => esc_html__( 'Main bottom Sidebar Background', 'madara' ),
						'desc'      => esc_html__( 'Upload background image for Main Bottom Sidebar', 'madara' ),
						'std'       => '',
						'type'      => 'background',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

					array(
						'id'        => 'main_bottom_sidebar_spacing',
						'label'     => esc_html__( 'Main Bottom Sidebar - Padding', 'madara' ),
						'desc'      => esc_html__( 'Padding in Main Bottom Sidebar. Default value is 50 0 20 0 & unit is px', 'madara' ),
						'std'       => '',
						'type'      => 'spacing',
						'condition' => 'custom_sidebar_settings:is(on)'
					),

				)
			);

			if ( function_exists( 'ot_register_meta_box' ) ) {
				ot_register_meta_box( $page_meta_boxes );
				ot_register_meta_box( $front_page_meta_boxes );
			}
		}
	}

	/**
	 * Return names of meta fields which may contain shortcodes, so they can be parsed in CT Shortcodes plugin to generate custom CSS
	 **/
	add_filter( 'ct_shortcodes_parse_shortcode_custom_css_in_metas', 'madara_meta_fields_contain_shortcodes' );
	function madara_meta_fields_contain_shortcodes( $metas ) {
		$metas = array_merge( $metas, array( 'page_header_content' ) );

		return $metas;
	}

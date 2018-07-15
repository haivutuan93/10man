<?php

	/**
	 * Initialize the Post Metaboxes. See /option-tree/assets/theme-mode/demo-meta-boxes.php for reference
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	add_action( 'admin_init', 'madara_post_MetaBoxes' );

	if ( ! function_exists( 'madara_post_MetaBoxes' ) ) {
		function madara_post_MetaBoxes() {
			$post_meta_boxes = array();

			$post_meta_boxes = array(
				'id'       => 'manga_other_settings',
				'title'    => esc_html__( 'Other Settings', 'madara' ),
				'desc'     => '',
				'pages'    => array( 'wp-manga' ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'id'      => 'manga_title_badges',
						'label'   => esc_html__( 'Title Badges', 'madara' ),
						'desc'    => esc_html__( 'Choose Manga Title Badges', 'madara' ),
						'std'     => '',
						'type'    => 'select',
						'choices' => array(
							array(
								'value' => 'no',
								'label' => esc_html__( 'No', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'hot',
								'label' => esc_html__( 'Hot', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'new',
								'label' => esc_html__( 'New', 'madara' ),
								'src'   => ''
							),
							array(
								'value' => 'custom',
								'label' => esc_html__( 'Custom', 'madara' ),
								'src'   => ''
							)
						)
					),
					array(
						'id'        => 'manga_custom_badges',
						'label'     => esc_html__( 'Custom Badges', 'madara' ),
						'desc'      => esc_html__( 'Enter Custom Badges', 'madara' ),
						'std'       => '',
						'type'      => 'text',
						'condition' => 'manga_title_badges:is(custom)',
					),

					array(
						'id'    => 'manga_profile_background',
						'label' => esc_html__( 'Manga Profiles Background', 'madara' ),
						'desc'  => esc_html__( 'Upload your background image for Single Manga Profiles', 'madara' ),
						'std'   => '',
						'type'  => 'background',
					),

				)
			);


			if ( function_exists( 'ot_register_meta_box' ) ) {
				ot_register_meta_box( $post_meta_boxes );
			}

		}
	}


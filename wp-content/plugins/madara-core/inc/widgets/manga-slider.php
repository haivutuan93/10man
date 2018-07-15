<?php

	/**
	 * Manga Slider
	 */
	class WP_MANGA_SLIDER extends WP_Widget {
		function __construct() {
			$widget_ops = array(
				'classname'   => 'manga-widget widget-manga-slider',
				'description' => esc_html__( 'Display recent manga as slider', WP_MANGA_TEXTDOMAIN )
			);
			parent::__construct( 'manga-slider', esc_html__( 'WP Manga: Manga Hero Slider', WP_MANGA_TEXTDOMAIN ), $widget_ops );
			$this->alt_option_name = 'widget_manga_slider';
		}

		function form( $instance ) {
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$number_of_post = ! empty( $instance['number_of_post'] ) ? $instance['number_of_post'] : '5';
			$number_to_show = ! empty( $instance['number_to_show'] ) ? $instance['number_to_show'] : '2';
			$genre          = ! empty( $instance['genre'] ) ? $instance['genre'] : '';
			$author         = ! empty( $instance['author'] ) ? $instance['author'] : '';
			$artist         = ! empty( $instance['artist'] ) ? $instance['artist'] : '';
			$release        = ! empty( $instance['release'] ) ? $instance['release'] : '';
			$order_by       = ! empty( $instance['order_by'] ) ? $instance['order_by'] : 'latest';
			$order          = ! empty( $instance['order'] ) ? $instance['order'] : 'desc';
			$style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-1';

			$order_by_list = array(
				'latest'    => esc_html__( 'Latest', WP_MANGA_TEXTDOMAIN ),
				'alphabet'  => esc_html__( 'Alphabet', WP_MANGA_TEXTDOMAIN ),
				'ratings'   => esc_html__( 'Ratings', WP_MANGA_TEXTDOMAIN ),
				'trending'  => esc_html__( 'Trending', WP_MANGA_TEXTDOMAIN ),
				'views'     => esc_html__( 'Views', WP_MANGA_TEXTDOMAIN ),
				'new-manga' => esc_html__( 'New Manga', WP_MANGA_TEXTDOMAIN ),
				'random'    => esc_html__( 'Random', WP_MANGA_TEXTDOMAIN )
			);

			$order_list = array(
				'desc' => esc_html__( 'DESC', WP_MANGA_TEXTDOMAIN ),
				'asc'  => esc_html__( 'ASC', WP_MANGA_TEXTDOMAIN )
			);

			?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"> <?php echo esc_html__( 'Title', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'number_of_post' ); ?>"><?php echo esc_html__( 'Number of posts', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="number" id="<?php echo $this->get_field_id( 'number_of_post' ); ?>" name="<?php echo $this->get_field_name( 'number_of_post' ); ?>" value="<?php echo esc_attr( $number_of_post ) ?>">
            </p>

			<?php
			$item_1 = $number_to_show == '1' ? 'selected="selected"' : '';
			$item_2 = $number_to_show == '2' ? 'selected="selected"' : '';
			$item_3 = $number_to_show == '3' ? 'selected="selected"' : '';
			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'number_to_show' ); ?>"><?php echo esc_html__( 'Number of Post to show', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'number_to_show' ); ?>" name="<?php echo $this->get_field_name( 'number_to_show' ); ?>">

                    <option value="2" <?php echo $item_2; ?>><?php echo esc_html__( '2', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="3" <?php echo $item_3; ?>><?php echo esc_html__( '3', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="1" <?php echo $item_1; ?>><?php echo esc_html__( '1', WP_MANGA_TEXTDOMAIN ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'genre' ); ?>"> <?php echo esc_html__( 'Genre', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'genre' ); ?>" name="<?php echo $this->get_field_name( 'genre' ); ?>" value="<?php echo esc_attr( $genre ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'author' ); ?>"> <?php echo esc_html__( 'Author', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" value="<?php echo esc_attr( $author ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'artist' ); ?>"> <?php echo esc_html__( 'Artist', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'artist' ); ?>" name="<?php echo $this->get_field_name( 'artist' ); ?>" value="<?php echo esc_attr( $artist ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'release' ); ?>"> <?php echo esc_html__( 'Release Year', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'release' ); ?>" name="<?php echo $this->get_field_name( 'release' ); ?>" value="<?php echo esc_attr( $release ); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php echo esc_html__( 'Order by', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order_by' ); ?>" name="<?php echo $this->get_field_name( 'order_by' ); ?>">
					<?php
						foreach ( $order_by_list as $value => $title ) {
							$selected = $order_by == $value ? 'selected' : '';
							?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php echo $selected; ?>><?php echo $title ?></option>
							<?php
						}
					?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php echo esc_html__( 'Order By', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
					<?php
						foreach ( $order_list as $value => $title ) {
							$selected = $order == $value ? 'selected' : '';
							?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php echo $selected; ?>><?php echo $title ?></option>
							<?php
						}
					?>
                </select>
            </p>
			<?php
			$style_1 = $style == 'style-1' ? 'selected="selected"' : '';
			$style_2 = $style == 'style-2' ? 'selected="selected"' : '';
			$style_3 = $style == 'style-3' ? 'selected="selected"' : '';
			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php echo esc_html__( 'Style', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">

                    <option value="style-1" <?php echo $style_1; ?>><?php echo esc_html__( 'Style 1', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="style-2" <?php echo $style_2; ?>><?php echo esc_html__( 'Style 2', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="style-3" <?php echo $style_3; ?>><?php echo esc_html__( 'Style 3', WP_MANGA_TEXTDOMAIN ); ?></option>
                </select>
            </p>
			<?php
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = strip_tags( $new_instance['title'] );
			$instance['number_of_post'] = strip_tags( $new_instance['number_of_post'] );
			$instance['number_to_show'] = strip_tags( $new_instance['number_to_show'] );
			$instance['genre']          = strip_tags( $new_instance['genre'] );
			$instance['author']         = strip_tags( $new_instance['author'] );
			$instance['artist']         = strip_tags( $new_instance['artist'] );
			$instance['release']        = strip_tags( $new_instance['release'] );
			$instance['order_by']       = strip_tags( $new_instance['order_by'] );
			$instance['order']          = strip_tags( $new_instance['order'] );
			$instance['style']          = strip_tags( $new_instance['style'] );

			return $instance;
		}

		function widget( $args, $instance ) {

			global $wp_manga_functions, $wp_manga_template, $wp_manga;

			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			ob_start();
			extract( $args );
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$number_of_post = ! empty( $instance['number_of_post'] ) ? $instance['number_of_post'] : '5';
			$number_to_show = ! empty( $instance['number_to_show'] ) ? $instance['number_to_show'] : '2';
			$order_by       = ! empty( $instance['order_by'] ) ? $instance['order_by'] : '';
			$order          = ! empty( $instance['order'] ) ? $instance['order'] : '';
			$style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-1';

			if ( $order_by == 'random' ) {
				$order_by = 'rand';
			}

			$query_args = array(
				'post_type'      => 'wp-manga',
				'post_status'    => 'publish',
				'posts_per_page' => $number_of_post,
				'order'          => $order,
				'orderby'        => $order_by
			);

			$genre = ! empty( $instance['genre'] ) ? $instance['genre'] : '';
			if ( $genre && '' != $genre ) {
				$query_args['tax_query']['relation'] = 'OR';
				$genre_array                         = explode( ',', $genre );
				foreach ( $genre_array as $g ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'wp-manga-genre',
						'terms'    => $g,
						'field'    => 'slug',
					);
				}
			}

			$author = ! empty( $instance['author'] ) ? $instance['author'] : '';
			if ( $author && '' != $author ) {
				$query_args['tax_query']['relation'] = 'OR';
				$author_array                        = explode( ',', $author );
				foreach ( $author_array as $au ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'wp-manga-author',
						'terms'    => $au,
						'field'    => 'slug',
					);
				}
			}

			$artist = ! empty( $instance['artist'] ) ? $instance['artist'] : '';
			if ( $artist && '' != $artist ) {
				$query_args['tax_query']['relation'] = 'OR';
				$artist_array                        = explode( ',', $artist );
				foreach ( $artist_array as $ar ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'wp-manga-artist',
						'terms'    => $ar,
						'field'    => 'slug',
					);
				}
			}

			$release = ! empty( $instance['release'] ) ? $instance['release'] : '';
			if ( $release && '' != $release ) {
				$query_args['tax_query']['relation'] = 'OR';
				$release_array                       = explode( ',', $release );
				foreach ( $release_array as $r ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'wp-manga-release',
						'terms'    => $r,
						'field'    => 'slug',
					);
				}
			}

			$queried_posts = $wp_manga->mangabooth_manga_query( $query_args );

			echo $before_widget;

			switch ( $style ) {
				case 'style-1':
					$data_style = 'style-1';
					$classes    = 'style-1 no-padding';
					break;
				case 'style-2':
					$data_style = 'style-2';
					$classes    = 'style-2';
					break;
				case 'style-3':
					$data_style = 'style-1';
					$classes    = 'style-1 no-full-width style-3';
					break;
			}

			if ( $title != '' ) { ?><?php echo $before_title . $title . $after_title; ?><?php } ?>

            <div class="manga-slider <?php echo esc_attr( $classes ); ?>" data-style="<?php echo esc_attr( $data_style ); ?>" data-count="<?php echo esc_attr( $number_to_show ); ?>">
                <div class="slider__container" role="toolbar">
					<?php while ( $queried_posts->have_posts() ) {
						$queried_posts->the_post();
						$wp_manga_template->load_template( 'widgets/manga-slider/slider', false );
					}
						wp_reset_postdata();
					?>
                </div>
            </div>
			<?php
			wp_reset_postdata();
			echo $after_widget;
		}


	}

	add_action( 'widgets_init', create_function( '', 'register_widget( "WP_MANGA_SLIDER" );' ) );
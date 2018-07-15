<?php

	/**
	 * Recent manga
	 */
	class WP_MANGA_RECENT extends WP_Widget {
		function __construct() {
			$widget_ops = array(
				'classname'   => 'c-popular manga-widget widget-manga-recent',
				'description' => esc_html__( 'Display Manga Posts', WP_MANGA_TEXTDOMAIN )
			);
			parent::__construct( 'manga-recent', esc_html__( 'WP Manga: Manga Posts', WP_MANGA_TEXTDOMAIN ), $widget_ops );
			$this->alt_option_name = 'widget_manga_recent';
		}

		function widget( $args, $instance ) {

			global $wp_manga, $wp_manga_functions, $wp_manga_template;

			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			ob_start();
			extract( $args );
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$number_of_post = ! empty( $instance['number_of_post'] ) ? $instance['number_of_post'] : '3';
			$order_by       = ! empty( $instance['order_by'] ) ? $instance['order_by'] : '';
			$time_range     = ! empty( $instance['time_range'] ) ? $instance['time_range'] : 'all';
			$order          = ! empty( $instance['order'] ) ? $instance['order'] : '';
			$style          = ! empty( $instance['style'] ) ? $instance['style'] : '';
			$button         = ! empty( $instance['button'] ) ? $instance['button'] : '';
			$url            = ! empty( $instance['url'] ) ? $instance['url'] : '';

			$query_args = array(
				'posts_per_page' => $number_of_post,
				'order'          => $order,
				'orderby'        => $order_by,
				'post_type'      => 'wp-manga',
				'post_status'    => 'publish',
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

			if ( $time_range == 'all' ) {
				$time_range = '';
			} elseif ( $time_range == 'day' ) {
				$time_range = '_wp_manga_day_views';
			} elseif ( $time_range == 'week' ) {
				$time_range = '_wp_manga_week_views';
			} elseif ( $time_range == 'month' ) {
				$time_range = '_wp_manga_month_views';
			} else {
				$time_range = '_wp_manga_year_views';
			}

			switch ( $order_by ) {
				case 'latest' :
					$query_args['orderby']  = 'meta_value_num';
					$query_args['meta_key'] = '_latest_update';
					break;
				case 'alphabet' :
					$query_args['orderby'] = 'post_title';
					break;
				case 'ratings' :
					$query_args['orderby']  = 'meta_value_num';
					$query_args['meta_key'] = '_manga_avarage_reviews';
					break;
				case 'trending' :
					$query_args['orderby']  = 'meta_value_num';
					$query_args['meta_key'] = $time_range;
					break;
				case 'views' :
					$query_args['orderby']  = 'meta_value_num';
					$query_args['meta_key'] = '_wp_manga_views';
					break;
				case 'new-manga' :
					$query_args['orderby'] = 'date';
					break;
			}

			$queried_posts = new WP_Query( $query_args );

			global $wp_manga_functions;

			echo $before_widget;

			?>

            <div class="c-widget-content <?php echo $style; ?> <?php echo $button != '' ? 'with-button' : ''; ?>">
				<?php
					if ( $title != '' ) {
						echo $before_title . $title . $after_title;
					}

					while ( $queried_posts->have_posts() ) {

						$queried_posts->the_post();

						?>
                        <div class="popular-item-wrap">

							<?php if ( $style == 'style-1' ) {
								$wp_manga_template->load_template( 'widgets/recent-manga/content-1', false );
							} else {
								$wp_manga_template->load_template( 'widgets/recent-manga/content-2', false );
							} ?>

                        </div>

						<?php
						wp_reset_postdata();

					}
				?>
				<?php if ( $button != '' ) { ?>
                    <span class="c-wg-button-wrap">
				        <a class="widget-view-more" href="<?php echo $url != '' ? esc_attr( $url ) : '#'; ?>"><?php echo esc_html( $button ); ?></a>
                    </span>
				<?php } ?>

            </div>
			<?php

			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = strip_tags( $new_instance['title'] );
			$instance['number_of_post'] = strip_tags( $new_instance['number_of_post'] );
			$instance['genre']          = strip_tags( $new_instance['genre'] );
			$instance['author']         = strip_tags( $new_instance['author'] );
			$instance['artist']         = strip_tags( $new_instance['artist'] );
			$instance['release']        = strip_tags( $new_instance['release'] );
			$instance['order_by']       = strip_tags( $new_instance['order_by'] );
			$instance['time_range']     = strip_tags( $new_instance['time_range'] );
			$instance['order']          = strip_tags( $new_instance['order'] );
			$instance['style']          = strip_tags( $new_instance['style'] );
			$instance['button']         = strip_tags( $new_instance['button'] );
			$instance['url']            = strip_tags( $new_instance['url'] );

			return $instance;
		}

		function form( $instance ) {
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$number_of_post = ! empty( $instance['number_of_post'] ) ? $instance['number_of_post'] : '3';

			$genre   = ! empty( $instance['genre'] ) ? $instance['genre'] : '';
			$author  = ! empty( $instance['author'] ) ? $instance['author'] : '';
			$artist  = ! empty( $instance['artist'] ) ? $instance['artist'] : '';
			$release = ! empty( $instance['release'] ) ? $instance['release'] : '';

			$order_by      = ! empty( $instance['order_by'] ) ? $instance['order_by'] : 'latest';
			$order_by_list = array(
				'latest'    => esc_html__( 'Latest', WP_MANGA_TEXTDOMAIN ),
				'alphabet'  => esc_html__( 'Alphabet', WP_MANGA_TEXTDOMAIN ),
				'ratings'   => esc_html__( 'Ratings', WP_MANGA_TEXTDOMAIN ),
				'trending'  => esc_html__( 'Trending', WP_MANGA_TEXTDOMAIN ),
				'views'     => esc_html__( 'Views', WP_MANGA_TEXTDOMAIN ),
				'new-manga' => esc_html__( 'New Manga', WP_MANGA_TEXTDOMAIN )
			);

			$time_range = ! empty( $instance['time_range'] ) ? $instance['time_range'] : 'all';

			$order      = ! empty( $instance['order'] ) ? $instance['order'] : 'desc';
			$order_list = array(
				'desc' => esc_html__( 'DESC', WP_MANGA_TEXTDOMAIN ),
				'asc'  => esc_html__( 'ASC', WP_MANGA_TEXTDOMAIN )
			);

			$style = ! empty( $instance['style'] ) ? $instance['style'] : 'style-1';

			$button = ! empty( $instance['button'] ) ? $instance['button'] : '';
			$url    = ! empty( $instance['url'] ) ? $instance['url'] : '';

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
                            <option value="<?php echo esc_attr( $value ); ?>" <?php echo $selected; ?>><?php echo esc_html( $title ); ?></option>
							<?php
						}
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'time_range' ); ?>"><?php echo esc_html__( 'Time Range', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'time_range' ); ?>" name="<?php echo $this->get_field_name( 'time_range' ); ?>">
                    <option value="all" <?php echo $time_range == 'all' ? 'selected' : '' ?>><?php echo esc_html__( 'All Time', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="day" <?php echo $time_range == 'day' ? 'selected' : '' ?>><?php echo esc_html__( '1 day', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="week" <?php echo $time_range == 'week' ? 'selected' : '' ?>><?php echo esc_html__( '1 week', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="month" <?php echo $time_range == 'month' ? 'selected' : '' ?>><?php echo esc_html__( '1 month', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="year" <?php echo $time_range == 'year' ? 'selected' : '' ?>><?php echo esc_html__( '1 year', WP_MANGA_TEXTDOMAIN ); ?></option>
                </select>
                <span class="description"><?php esc_html_e( 'Affected when order by is trending', WP_MANGA_TEXTDOMAIN ); ?></span>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php echo esc_html__( 'Order By', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
					<?php
						foreach ( $order_list as $value => $title ) {
							$selected = $order == $value ? 'selected' : '';
							?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php echo $selected; ?>><?php echo esc_html( $title ) ?></option>
							<?php
						}
					?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php echo esc_html__( 'Style', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
                    <option value="style-1" <?php echo $style == 'style-1' ? 'selected' : '' ?>><?php echo esc_html__( 'Style 1', WP_MANGA_TEXTDOMAIN ); ?></option>
                    <option value="style-2" <?php echo $style == 'style-2' ? 'selected' : '' ?>><?php echo esc_html__( 'Style 2', WP_MANGA_TEXTDOMAIN ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'button' ); ?>"> <?php echo esc_html__( 'ReadMore Button', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" value="<?php echo esc_attr( $button ); ?>">
                <span class="description"><?php esc_html_e( 'Readmore button text. Leave blank to disable this button', WP_MANGA_TEXTDOMAIN ); ?></span>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'url' ); ?>"> <?php echo esc_html__( 'ReadMore button URL', WP_MANGA_TEXTDOMAIN ); ?>
                    : </label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo esc_attr( $url ); ?>">
                <span class="description"><?php esc_html_e( 'Readmore button URL', WP_MANGA_TEXTDOMAIN ); ?></span>
            </p>

			<?php
		}

	}

	add_action( 'widgets_init', create_function( '', 'register_widget( "WP_MANGA_RECENT" );' ) );
<?php

	namespace App\Plugins\Widgets;

	use App\Madara;
	use App\Models\Database;

	class Posts extends \WP_Widget {
		function __construct() {
			$options = array(
				'classname'   => 'c-w-posts c-popular manga-widget widget-manga-recent',
				'description' => esc_html__( 'Show Posts', 'madara' )

			);
			parent::__construct( 'c-w-posts', esc_html__( 'Madara - Posts', 'madara' ), $options );
		}

		function form( $instance ) {
			$default_value = array(
				'title'           => esc_html__( 'Madara - Posts', 'madara' ),
				'number_of_posts' => '3',
				'order_by'        => 'date',
				'order'           => 'ASC',
				'category'        => '',
				'tags'            => '',
				'post_ids'        => '',
			);

			$instance = wp_parse_args( ( array ) $instance, $default_value );

			$title           = esc_attr( $instance['title'] );
			$category        = esc_attr( $instance['category'] );
			$tags            = esc_attr( $instance['tags'] );
			$post_ids        = esc_attr( $instance['post_ids'] );
			$number_of_posts = esc_attr( $instance['number_of_posts'] );
			$order           = esc_attr( $instance['order'] );
			$order_by        = esc_attr( $instance['order_by'] );
			// Create form
			$html = '';

			$html .= '<p>';
			$html .= '<label>' . esc_html__( 'Title', 'madara' ) . ': </label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . $title . '"/>';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label>' . esc_html__( 'Number of posts', 'madara' ) . ': </label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name( 'number_of_posts' ) . '" value="' . $number_of_posts . '"/>';
			$html .= '</p>';

			$date          = $order_by == 'date' ? 'selected="selected"' : '';
			$rand          = $order_by == 'rand' ? 'selected="selected"' : '';
			$comment_count = $order_by == 'comment_count' ? 'selected="selected"' : '';
			$html          .= '<p><label>' . esc_html__( 'Choose how to query posts', 'madara' ) . ': </label></p>';
			$html          .= '<p>';
			$html          .= '<select name="' . $this->get_field_name( 'order_by' ) . '">
						<option value="date"' . $date . '>' . esc_html__( 'Latest Posts', 'madara' ) . '</option>
						<option value="rand"' . $rand . '>' . esc_html__( 'Random Posts', 'madara' ) . '</option>
						<option value="comment_count"' . $comment_count . '>' . esc_html__( 'Most Commented', 'madara' ) . '</option>
					</select>';
			$html          .= '</p>';

			$ASC  = $order == 'ASC' ? 'selected="selected"' : '';
			$DESC = $order == 'DESC' ? 'selected="selected"' : '';
			$html .= '<p><label>' . esc_html__( 'Choose order of posts', 'madara' ) . ': </label></p>';
			$html .= '<p>';
			$html .= '<select name="' . $this->get_field_name( 'order' ) . '">
						<option value="ASC"' . $ASC . '>' . esc_html__( 'ASC', 'madara' ) . '</option>
						<option value="DESC"' . $DESC . '>' . esc_html__( 'DESC', 'madara' ) . '</option>
					</select>';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label>' . esc_html__( 'Category - Category ID or Slug', 'madara' ) . ': </label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name( 'category' ) . '" value="' . $category . '"/>';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label>' . esc_html__( 'Tags - Tag List', 'madara' ) . ': </label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name( 'tags' ) . '" value="' . $tags . '"/>';
			$html .= '</p>';

			$html .= '<p>';
			$html .= '<label>' . esc_html__( 'Post IDs - If this param is used, other params are ignored', 'madara' ) . ' </label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name( 'post_ids' ) . '" value="' . $post_ids . '"/>';
			$html .= '</p>';

			echo wp_kses( $html, array(
				'p'      => array(),
				'label'  => array(),
				'input'  => array(
					'class' => array(),
					'type'  => array(),
					'name'  => array(),
					'value' => array(),
				),
				'select' => array(
					'class' => array(),
					'id'    => array(),
					'name'  => array()
				),
				'option' => array(
					'class' => array(),
					'id'    => array(),
					'value' => array(),
				),				
			) );
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']           = strip_tags( $new_instance['title'] );
			$instance['number_of_posts'] = strip_tags( $new_instance['number_of_posts'] );
			$instance['order_by']        = strip_tags( $new_instance['order_by'] );
			$instance['order']           = strip_tags( $new_instance['order'] );
			$instance['category']        = strip_tags( $new_instance['category'] );
			$instance['tags']            = strip_tags( $new_instance['tags'] );
			$instance['post_ids']        = strip_tags( $new_instance['post_ids'] );

			return $instance;
		}

		function widget( $args, $instance ) {
			$direction = '';
			if ( Madara::getOption( 'rtl', 0 ) ) {
				$direction = 'dir="ltr"';
			}

			$lazyload = 'off';
			if ( function_exists( 'ot_get_option' ) ) {
				$lazyload = ot_get_option( 'lazyload', 'off' );
			}

			extract( $args );

			$title           = isset( $instance['title'] ) && $instance['title'] != '' ? $instance['title'] : '';
			$number_of_posts = isset( $instance['number_of_posts'] ) && $instance['number_of_posts'] != '' ? $instance['number_of_posts'] : '3';
			$order_by        = isset( $instance['order_by'] ) && $instance['order_by'] != '' ? $instance['order_by'] : 'date';
			$order           = isset( $instance['order'] ) && $instance['order'] != '' ? $instance['order'] : 'ASC';
			$cats            = isset( $instance['category'] ) && $instance['category'] != '' ? $instance['category'] : '';
			$tags            = isset( $instance['tags'] ) && $instance['tags'] != '' ? $instance['tags'] : '';
			$post_ids        = isset( $instance['post_ids'] ) && $instance['post_ids'] != '' ? $instance['post_ids'] : '';


			$the_query = Database::getPosts( $number_of_posts, $order, 1, $order_by, array(
				'categories' => $cats,
				'tags'       => $tags,
				'ids'        => $post_ids
			) );

			echo wp_kses_post( $before_widget );

			?>

            <div class="c-widget-content style-2">
				<?php
					if ( $title != '' ) {
						echo wp_kses_post( $before_title . $title . $after_title );
					}

					while ( $the_query->have_posts() ) {

						$the_query->the_post();
						$post_title = get_the_title();
						$post_url   = get_the_permalink();

						?>
                        <div class="popular-item-wrap">

							<?php if ( has_post_thumbnail() ) { ?>
                                <div class="popular-img widget-thumbnail c-image-hover">
                                    <a title="<?php echo esc_attr( $post_title ); ?>" href="<?php echo esc_url( $post_url ); ?>">
										<?php
											echo madara_thumbnail( 'manga_wg_post_2' );
										?>
                                    </a>
                                </div>
							<?php } ?>

                            <div class="popular-content">
                                <h5 class="widget-title">
                                    <a title="<?php echo esc_attr( $post_title ); ?>" href="<?php echo esc_url( $post_url ); ?>"><?php echo esc_html( $post_title ); ?></a>
                                </h5>

                                <div class="posts-date"><?php echo get_the_date(); ?></div>

                            </div>

                        </div>

						<?php
						wp_reset_postdata();

					}
				?>

            </div>

			<?php

			echo wp_kses_post( $after_widget );
		}

	}

	add_action( 'widgets_init', create_function( '', 'return register_widget("App\Plugins\Widgets\Posts");' ) );

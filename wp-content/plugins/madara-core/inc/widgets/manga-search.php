<?php
/**
 * Manga Search
 */
class WP_MANGA_SEARCH extends WP_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'manga-widget widget-manga-search', 'description' => esc_html__( 'Manga Search', WP_MANGA_TEXTDOMAIN) );
		parent::__construct('manga-search', esc_html__('WP Manga: Manga Search', WP_MANGA_TEXTDOMAIN), $widget_ops);
		$this->alt_option_name = 'widget_manga_search';
	}

	function widget( $args, $instance ){

		if ( ! isset( $args['widget_id'] ) ) {
		  $args['widget_id'] = $this->id;
		}

		ob_start();

		extract($args);

		$title = !empty( $instance['title'] ) ? $instance['title'] : '';
		$search_advanced = isset( $instance['search_advanced'] ) ? $instance['search_advanced'] : 'Advanced';

		global $wp_manga_functions,$wp_manga_template,$wp_rewrite;
		echo $before_widget;

		if ( $title != '' ) {
            echo $before_title . $title . $after_title;
        }

		?>

		<div class="search-navigation__wrap">

			<?php $wp_manga_template->load_template( 'widgets/manga-search/manga-search', false ); ?>

		<?php if( $search_advanced !== '' ) { ?>
			<div class="link-adv-search">
				<a href="<?php echo esc_url( site_url() . '/?s=&post_type=wp-manga' ); ?>"><?php echo esc_html( $search_advanced ); ?></a>
			</div>
		<?php } ?>

		</div>

		<?php echo $after_widget;
	}

	function form( $instance ){

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$search_advanced = isset( $instance['search_advanced'] ) ? $instance['search_advanced'] : 'Advanced';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"> <?php echo esc_html__( 'Title', WP_MANGA_TEXTDOMAIN ); ?> : </label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_advanced' ); ?>"> <?php echo esc_html__( 'Search Advance Text : ', WP_MANGA_TEXTDOMAIN ); ?> </label>
 			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'search_advanced' ); ?>" name="<?php echo $this->get_field_name( 'search_advanced' ); ?>" value="<?php echo esc_attr( $search_advanced ); ?>">
			<span class="description"><?php esc_html_e( 'Change Advanced Text, leave empty to remove Advanced Text.', WP_MANGA_TEXTDOMAIN ); ?></span>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
		$instance['search_advanced'] = isset( $new_instance['search_advanced'] ) ? $new_instance['search_advanced'] : 'Advanced';

		return $instance;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "WP_MANGA_SEARCH" );' ) );
<?php
/**
 * manga taxonomy
 */
class WP_MANGA_TAXONOMY extends WP_Widget {

  function __construct() {
	$widget_ops = array('classname' => 'manga-widget widget-manga-taxonomy', 'description' => esc_html__( 'Display Manga Taxonomy', WP_MANGA_TEXTDOMAIN) );
	parent::__construct('manga-taxonomy', esc_html__('WP Manga: Manga Taxonomy', WP_MANGA_TEXTDOMAIN), $widget_ops);
	$this->alt_option_name = 'widget_manga_taxonomy';

  }

  function widget($args, $instance) {
	if ( ! isset( $args['widget_id'] ) ) {
	  $args['widget_id'] = $this->id;
	}
	
	ob_start();
	extract($args);

	$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : 'Hot Topic';
	$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
	$style = ( ! empty( $instance['style'] ) ) ? $instance['style'] : 'style-1';
	$taxonomy = ( ! empty( $instance['taxonomy'] ) ) ? $instance['taxonomy'] : 'wp-manga-genre';
	$terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
	echo $before_widget; ?>
	<div class="c-widget-wrap">
	<?php
	if ( '' != $title ) { ?>
		<div class="widget-heading font-nav">
	        <h5><?php echo $title; ?></h5>
	    </div>
	<?php }	?>
		<div class="released-item-wrap <?php echo $style ?>">
			<ul class="list-released">
				<?php foreach ( $terms as $term ) : ?>
					<li><a href="<?php echo esc_url( get_term_link( $term, $taxonomy ) ) ?>"><?php echo esc_attr( $term->name ); ?></a>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php echo $after_widget; ?>
	<?php
  }

  function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
	$instance['style'] = strip_tags($new_instance['style']);
	return $instance;
  }

  function form( $instance ) {
	$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Hot Topic';
	$style = isset( $instance['style'] ) ? esc_attr( $instance['style'] ) : 'style-1';
	$taxonomy = isset( $instance['taxonomy'] ) ? esc_attr( $instance['taxonomy'] ) : 'wp-manga-genre';
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', WP_MANGA_TEXTDOMAIN ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

	<p>
		<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php esc_html_e( 'Taxonomy:', WP_MANGA_TEXTDOMAIN ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
	<?php 
		$taxonomies = $this->get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$tax = get_taxonomy( $taxonomy ); 
			?>
			<option value="<?php echo esc_attr( $tax->name ); ?>" <?php selected( $tax->name, $taxonomy, true ) ?>><?php echo esc_attr( $tax->label ); ?></option>
			<?php
		}
		?>
		</select>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php esc_html_e( 'Style:', WP_MANGA_TEXTDOMAIN ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
			<option value="style-1" <?php selected( 'style-1', $style, true ) ?>><?php esc_html_e( 'Style 1', WP_MANGA_TEXTDOMAIN ); ?></option>
			<option value="style-2" <?php selected( 'style-2', $style, true ) ?>><?php esc_html_e( 'Style 2', WP_MANGA_TEXTDOMAIN ); ?></option>
		</select>
	</p>
	<?php
  }

  function get_taxonomies() {
  	$taxonomies = get_object_taxonomies( 'wp-manga' );
  	return $taxonomies;
  }


}
add_action( 'widgets_init', create_function( '', "register_widget('WP_MANGA_TAXONOMY');" ) );
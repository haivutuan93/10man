<?php
	/*
	 *
	 * Template of Widget Recent Manga (Manga Posts) - Content style 1
	 *
	 * */

	global $wp_manga_functions;

?>

<?php if ( has_post_thumbnail() ) { ?>
    <div class="popular-img widget-thumbnail c-image-hover">
        <a title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php
				echo apply_filters( 'manga_wg_post_1_thumb', the_post_thumbnail( 'manga_wg_post_1' ) );
			?>
        </a>
    </div>
<?php } ?>

<div class="popular-content">
    <h5 class="widget-title">
        <a title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
    </h5>

    <div class="list-chapter">
		<?php $wp_manga_functions->manga_meta( get_the_ID() ); ?>
    </div>

</div>
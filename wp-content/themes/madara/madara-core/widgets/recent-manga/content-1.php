<?php
	/*
	 *
	 * Template of Widget Recent Manga (Manga Posts) - Content style 1
	 *
	 * */

	$wp_manga_functions = madara_get_global_wp_manga_functions();
?>

<?php if ( has_post_thumbnail() ) { ?>
    <div class="popular-img widget-thumbnail c-image-hover">
        <a title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php
				$temp_badges = get_post_meta( get_the_ID(), 'manga_title_badges', true );
                                            if($temp_badges == "custom"){
                                                echo madara_thumbnail( 'manga_wg_post_2');
                                            }else{
                                                echo madara_thumbnail( $thumb_size );
                                            }
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
<?php
	/*
	 *
	 * Template of Widget Recent Manga (Manga Posts) - Content style 2
	 *
	 * */

?>

<?php if ( has_post_thumbnail() ) { ?>
    <div class="popular-img widget-thumbnail c-image-hover">
        <a title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php
				echo madara_thumbnail( 'manga_wg_post_2' );
			?>
        </a>
    </div>
<?php } ?>

<div class="popular-content">

    <h5 class="widget-title">
        <a title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
    </h5>
    <div class="posts-date"><?php echo get_the_date(); ?></div>

</div>
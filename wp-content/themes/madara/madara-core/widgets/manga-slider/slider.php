<?php

	/** Template of Manga: Overwrite Post Slider **/

	use App\Madara;

	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$thumb_size         = array( 642, 320 );
	$allow_thumb_gif    = Madara::getOption( 'manga_single_allow_thumb_gif', 'off' );

	$thumb_url = get_the_post_thumbnail_url( get_the_ID() );

	$thumb_type = 'gif';
	if ( $thumb_url != '' ) {
		$type = substr( $thumb_url, - 3 );
	}

	if ( $allow_thumb_gif == 'on' && $thumb_type == $type ) {
		$thumb_size = 'full';
	}

?>

<div class="slider__item <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

	<?php if ( has_post_thumbnail() ) { ?>
        <div class="slider__thumb">
            <div class="slider__thumb_item">
                <a href="<?php echo get_the_permalink() ?>">
					<?php if ( $allow_thumb_gif == 'off' ) {
						echo madara_thumbnail( $thumb_size );
					} else {
						echo get_the_post_thumbnail( get_the_ID(), $thumb_size );
					} ?>
                    <div class="slider-overlay"></div>
                </a>
            </div>
        </div>
	<?php } ?>

    <div class="slider__content">
        <div class="slider__content_item">
            <div class="post-title font-title">
                <h4>
                    <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                </h4>
            </div>
        </div>
    </div>
</div>
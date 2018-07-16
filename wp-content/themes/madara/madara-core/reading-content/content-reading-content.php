<?php
	/** Manga Reading Content - Text type **/

	use App\Madara;

?>

<?php

	$wp_manga     = madara_get_global_wp_manga();
	$post_id      = get_the_ID();
	$name         = get_query_var('chapter');
	$chapter_type = get_post_meta( get_the_ID(), '_wp_manga_chapter_type', true );
	if( $name == '' ){
		get_template_part( 404 );
		exit();
	}

//	$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( get_the_ID(), $name );
        global $global_chapter_by_slug;
        $this_chapter  = $global_chapter_by_slug;

	if ( ! $this_chapter ) {
		return;
	}

	$chapter_content = new WP_Query( array(
		'post_parent' => $this_chapter['chapter_id'],
		'post_type' => 'chapter_text_content'
	) );

	if( $chapter_content->have_posts() ){

		$post = $chapter_content->the_post();

		setup_postdata( $post );

		?>
			<?php if( $chapter_type == 'text' ){ ?>
				<div class="text-left">
					<?php the_content(); ?>
				</div>
			<?php }elseif( $chapter_type == 'video'){
			
			?>
				<div class="chapter-video-frame row">
					<?php the_content(); ?>
				</div>
			<?php } ?>

		<?php

	}

	wp_reset_postdata();

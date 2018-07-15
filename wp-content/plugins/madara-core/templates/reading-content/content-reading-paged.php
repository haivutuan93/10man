<?php

	/** Manga Reading Content - paged Style **/

	global $wp_manga, $wp_manga_chapter;

	$post_id  = get_the_ID();
	$name     = get_query_var('chapter');
	$paged    = isset( $_GET['manga-paged'] ) ? $_GET['manga-paged'] : 1;
	$style    = isset( $_GET['style'] ) ? $_GET['style'] : 'paged';

	if ( $name !== '' ) {
		$this_chapter = $wp_manga_chapter->get_chapter_by_slug( get_the_ID(), $name );

		if( !$this_chapter ) {
			return;
		}

		$chapter      = $wp_manga->get_single_chapter( $post_id, $this_chapter['chapter_id'] );
		$in_use       = $chapter['storage']['inUse'];

		$alt_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
		if ( $alt_host ) {
			$in_use = $alt_host;
		}
	}

	$host = $chapter['storage'][ $in_use ]['host'];
	$link = $chapter['storage'][ $in_use ]['page'][ $paged ]['src'];
	$src  = $host . $link;
	?>
	<img id="image-<?php echo esc_attr( $paged ); ?>" src="<?php echo esc_url( $src ); ?>" class ="wp-manga-chapter-img">

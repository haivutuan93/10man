<?php
	/** Manga Reading Content - paged Style **/

	use App\Madara;

?>

<?php
	$wp_manga = madara_get_global_wp_manga();
	$post_id  = get_the_ID();
	$name     = get_query_var( 'chapter' );
	$paged    = isset( $_GET['manga-paged'] ) ? intval( $_GET['manga-paged'] ) : 1;
	$style    = isset( $_GET['style'] ) ? $_GET['style'] : 'paged';

	if ( Madara::getOption( 'lazyload', 'off' ) == 'on' ) {
		$lazyload = 'wp-manga-chapter-img img-responsive lazyload effect-fade';
	} else {
		$lazyload = 'wp-manga-chapter-img';
	}

	if ( $name !== '' ) {
		$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( get_the_ID(), $name );

		if ( ! $this_chapter ) {
			return;
		}

		$chapter = $wp_manga->get_single_chapter( $post_id, $this_chapter['chapter_id'] );
		$in_use  = $chapter['storage']['inUse'];

		$alt_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
		if ( $alt_host ) {
			$in_use = $alt_host;
		}
	}

	$img_per_page = intval( madara_get_img_per_page() );

	if ( ! empty( $img_per_page ) && $img_per_page != '1' ) {

		$paged = $img_per_page * ( $paged - 1 ) + 1;

		for ( $i = 1; $i <= $img_per_page; $i ++ ) {

			if ( ! isset( $chapter['storage'][ $in_use ]['page'][ $paged ] ) ) {
				break;
			}

			$host = $chapter['storage'][ $in_use ]['host'];
			$link = $chapter['storage'][ $in_use ]['page'][ $paged ]['src'];
			$src  = $host . $link;
			?>

            <img id="image-<?php echo esc_attr( $paged ); ?>" data-image-paged="<?php echo esc_attr( $paged ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">

			<?php
			$paged ++;
		}

	} else {
		$host = $chapter['storage'][ $in_use ]['host'];

		if ( ! isset( $chapter['storage'][ $in_use ]['page'][ $paged ] ) ) {
			return;
		}

		$link = $chapter['storage'][ $in_use ]['page'][ $paged ]['src'];
		$src  = $host . $link;
		?>

        <img id="image-<?php echo esc_attr( $paged ); ?>" data-image-paged="<?php echo esc_attr( $paged ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">

		<?php
	}

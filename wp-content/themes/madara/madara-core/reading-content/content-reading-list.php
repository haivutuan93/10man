<?php
	/** Manga Reading Content - List Style **/

	use App\Madara;

?>

<?php
	$wp_manga = madara_get_global_wp_manga();
	$post_id  = get_the_ID();
	$name     = get_query_var('chapter');
	$paged    = isset( $_GET['manga-paged'] ) ? $_GET['manga-paged'] : 1;
	$style    = isset( $_GET['style'] ) ? $_GET['style'] : 'paged';

	if ( Madara::getOption( 'lazyload', 'off' ) == 'on' ) {
		$lazyload = 'wp-manga-chapter-img img-responsive lazyload effect-fade';
	} else {
		$lazyload = 'wp-manga-chapter-img';
	}

	if ( $name == '' ) {
		return;
	}

	$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( get_the_ID(), $name );

	if ( ! $this_chapter ) {
		return;
	}

	$chapter  = $wp_manga->get_single_chapter( $post_id, $this_chapter['chapter_id'] );
	$in_use   = $chapter['storage']['inUse'];
	$alt_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
	if ( $alt_host ) {
		$in_use = $alt_host;
	}

	if( !isset( $chapter['storage'][ $in_use ] ) && !is_array( $chapter['storage'][ $in_use ]['page'] ) ){
		return;
	}

	foreach ( $chapter['storage'][ $in_use ]['page'] as $page => $link ) {
		$host = $chapter['storage'][ $in_use ]['host'];
		$src  = $host . $link['src'];
		?>
        <div class="page-break">
            <img id="image-<?php echo esc_attr( $page ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">
        </div>
	<?php }

<?php
	/**
	 * @package madara
	 */

	$madara_postMeta = new App\Views\ParseMeta();
	$madara_showtags = \App\Madara::getOption( 'single_tags', 'on' );
	$thumb_size      = 'full';
?>


<div id="post-<?php the_ID(); ?>" <?php post_class( 'c-blog-post' ); ?>>

    <div class="entry-header">
        <div class="entry-header_wrap">
            <div class="entry-title">
                <h2 class="item-title"><?php the_title(); ?></h2>
            </div>
			<?php $madara_postMeta->renderPostMeta(); ?>
        </div>
    </div>

	<?php if ( has_excerpt() ) { ?>
        <div class="c-blog__excerpt">
			<?php the_excerpt(); ?>
        </div>
	<?php } ?>

	<?php if ( has_post_thumbnail() ) { ?>
        <div class="c-blog__thumbnail">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php echo madara_thumbnail( $thumb_size ); ?>
            </a>
        </div>
	<?php } ?>

    <div class="entry-content">
        <div class="entry-content_wrap">
			<?php the_content(); ?>
        </div>
    </div>

	<?php if ( $madara_showtags == 'on' && has_tag() ): ?>
        <div class="item-tags">
            <h5>Tags: </h5>
			<?php the_tags( '<ul class="list-inline">
                <li>', '</li><li>', '</li></ul>' );
			?>
        </div>
	<?php endif; ?>

	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'madara' ),
			'after'  => '</div>',
		) );
	?>

</div>
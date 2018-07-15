<?php
	/**
	 * @package madara
	 */

	$postMeta = new App\Views\ParseMeta();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="row">
        <div class="col-xs-12 c-column">
			<?php if ( has_post_thumbnail() ): ?>
                <div class="entry-featured-image">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php echo madara_thumbnail( array( 850, 478 ) ); ?>
                    </a>
                </div>
			<?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 c-column">
            <header class="entry-header">
                <div class="entry-title">
					<?php $postMeta->renderPostTitle( 'h3' ); ?>
                </div>
                <!-- .entry-title -->

				<?php if ( 'post' == get_post_type() ) : ?>
                    <div class="entry-date">
						<?php $postMeta->renderPublishDate(); ?>
                    </div>
				<?php endif; ?>

				<?php $postMeta->renderPostMeta(); ?>
            </header>
            <!-- .entry-header -->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 c-column">
            <div class="entry-content">
                <div class="entry-excerpt">
					<?php the_excerpt( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'madara' ) ); ?>
                </div>
                <!-- .entry-excerpt -->

				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'madara' ),
						'after'  => '</div>',
					) );
				?>
            </div>
            <!-- .entry-content -->
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 c-column">
            <footer class="entry-footer">
                <div class="entry-readmore pull-left">
                    <a class="item-readmore btn btn-default btn-custom small" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><i class="ion-ios-plus-outline"></i>
                        <span><?php esc_html_e( 'Read more', 'madara' ); ?></span> </a>
                </div>
            </footer>
            <!-- .entry-footer -->
        </div>
    </div>

</article><!-- #post-## -->

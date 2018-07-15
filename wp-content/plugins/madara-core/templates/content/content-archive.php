<?php

	use App\Cactus;

    global $wp_query, $wp_manga, $wp_manga_setting, $wp_manga_functions, $wp_manga_template;

	//get ready
	$thumb_size  = array( 110, 150 );
	$index       = get_query_var( 'wp_manga_post_index' );
	$last_index  = get_query_var( 'wp_manga_posts_per_page' );

	$alternative = $wp_manga_functions->get_manga_alternative( get_the_ID() );

	$authors     = $wp_manga_functions->get_manga_authors( get_the_ID() );

?>
<?php if( $index % 2 == 1 ){ ?>
	<div class="page-listing-item">
		<div class="row">
<?php }?>
			<div class="col-xs-12 col-md-6">
				<div class="page-item-detail">
					<div class="item-thumb">
						<?php
							if ( has_post_thumbnail() ) {
								?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php the_post_thumbnail( $thumb_size ); ?>
								</a>
								<?php
							}
						?>
					</div>
					<div class="item-summary">
						<div class="post-title font-title">
							<h5>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h5>
						</div>
						<div class="meta-item rating">
							<?php echo $wp_manga_functions->manga_rating( get_the_ID() ); ?>
						</div>
						<div class="list-chapter">
							<?php $wp_manga_functions->manga_meta( get_the_ID() ); ?>
						</div>
					</div>
				</div>
			</div>
<?php if( $index % 2 == 0 || $index == $last_index ){ ?>
		</div>
	</div>
<?php } ?>

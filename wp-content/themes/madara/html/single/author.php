<?php

	use App\Views\MadaraView;

	$manga_show_author = \App\Madara::getOption( 'single_author', 'on' );
	if ( $manga_show_author == 'on' ) {

		$manga_author_desc = get_the_author_meta( 'description' );

		if ( $manga_author_desc != '' ) {
			?>

            <div class="c-post-author">
                <div class="row">
                    <!-- <div class="heading-group">
			<div class="col-xs-12 c-column">
				<div class="item-heading">
					<h4 class="heading"><?php echo esc_html__( 'About The Author', 'madara' ); ?></h4>
				</div>
			</div>
		</div> -->
                    <div class="block-group">
                        <div class="col-xs-12 c-column">
                            <div class="block block-left">
                                <div class="c-avatar">
                                    <div class="item-avatar">
										<?php MadaraView::render( 'AuthorAvatar' ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="block block-right">
                                <div class="c-information">
                                    <div class="c-name">
                                        <h4 class="heading">
                                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                                        </h4>
                                    </div>
                                    <div class="c-socials">
										<?php MadaraView::render( 'AuthorSocialAccounts', get_the_author_meta( 'ID' ), true ); ?>
                                    </div>
                                    <div class="c-summary">
                                        <div class="item-summary">
                                            <p><?php the_author_meta( 'description' ); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hr"></div>
            </div>

            <!--!author-->
			<?php
		}
	}
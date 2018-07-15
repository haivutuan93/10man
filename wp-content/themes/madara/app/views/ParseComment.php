<?php

	/**
	 * Class ParseComment
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	class ParseComment {

		public function __construct() {

		}

		public static function get_comment_form_args() {
			$req       = get_option( 'require_name_email' );
			$aria_req  = ( $req ? " aria-required='true'" : '' );
			$html_req  = ( $req ? " required='required'" : '' );
			$commenter = wp_get_current_commenter();

			$fields = array(

				'author' => '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'madara' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" name="author" type="text" placeholder="' . esc_html__( 'Name', 'madara' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',

				'email' => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'madara' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" name="email" type="text" placeholder="' . esc_html__( 'Email', 'madara' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',

				'url' => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'madara' ) . '</label>' . '<input id="url" name="url" type="text" placeholder="' . esc_html__( 'Website', 'madara' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
			);

			$args = array(
				'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
				'title_reply_after'  => '</h4>',
				'fields'             => $fields,
				'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'madara' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_html__( 'Comment', 'madara' ) . '"></textarea></p>'
			);

			return $args;
		}

		public static function comment_item( $comment, $args, $depth ) {
			if ( 'div' === $args['style'] ) {
				$tag       = 'div';
				$add_below = 'comment';
			} else {
				$tag       = 'li';
				$add_below = 'div-comment';
			}
			?>
            <<?php echo esc_attr( $tag ) . ' '; ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
			<?php if ( 'div' != $args['style'] ) : ?>
                <article id="div-comment-<?php comment_ID() ?>" class="comment-body">
			<?php endif; ?>
            <div class="block block-left">
                <footer class="comment-meta">
                    <div class="comment-avatar">
						<?php if ( $args['avatar_size'] != 0 ) {
							echo get_avatar( $comment, $args['avatar_size'] );
						} ?>
                    </div>
                </footer>
                <!-- .comment-meta -->
            </div>
            <div class="block block-right">
                <div class="comment-author">
                    <h6 class="heading fn"><?php echo get_comment_author_link(); ?></h6>
                </div>
                <!--!comment-author-->
                <div class="comment-content">
					<?php comment_text(); ?>

					<?php if ( $comment->comment_approved == '0' ) : ?>
                        <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'madara' ); ?></em>
                        <br/>
					<?php endif; ?>

					<?php edit_comment_link( esc_html__( '(Edit)', 'madara' ), '  ', '' ); ?>
                </div>
                <!-- .comment-content -->
                <div class="comment-metadata">
                    <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf( esc_html__( '%1$s at %2$s', 'madara' ), get_comment_date(), get_comment_time() ); ?></a>
                </div>
                <!-- .comment-metadata -->
            </div>

            <div class="reply">
				<?php comment_reply_link( array_merge( $args, array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?>
            </div>

			<?php if ( 'div' != $args['style'] ) : ?>
                </article>
			<?php endif; ?><?php
		}
	}
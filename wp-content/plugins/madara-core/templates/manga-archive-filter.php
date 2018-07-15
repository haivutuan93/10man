<?php

	$orderby = isset( $_GET['m_orderby'] ) ? $_GET['m_orderby'] : 'latest';

	global $wp_manga;

?>

<div class="c-nav-tabs">
	<span> <?php esc_html_e('Sắp xếp', WP_MANGA_TEXTDOMAIN); ?> </span>
	<ul class="c-tabs-content">
		<li <?php echo $orderby == 'latest' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'latest' ) : '?m_orderby=latest'; ?>">
				<?php esc_html_e('Mới nhất', WP_MANGA_TEXTDOMAIN);  ?>
			</a>
		</li>
		<li <?php echo $orderby == 'alphabet' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'alphabet' ) : '?m_orderby=alphabet'; ?>">
				<?php esc_html_e('A-Z', WP_MANGA_TEXTDOMAIN); ?>
			</a>
		</li>
		<li <?php echo $orderby == 'rating' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'rating' ) : '?m_orderby=rating'; ?>">
				<?php esc_html_e('Đánh giá', WP_MANGA_TEXTDOMAIN); ?>
			</a>
		</li>
		<li <?php echo $orderby == 'trending' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'trending' ) : '?m_orderby=trending'; ?>">
				<?php esc_html_e('Xu hướng', WP_MANGA_TEXTDOMAIN); ?>
			</a>
		</li>
		<li <?php echo $orderby == 'views' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'views' ) : '?m_orderby=views'; ?>">
				<?php esc_html_e('Xem nhiều', WP_MANGA_TEXTDOMAIN);  ?>
			</a>
		</li>
		<li <?php echo $orderby == 'new-manga' ? 'class="active"' : ''; ?>>
			<a href="<?php echo is_search() ? $wp_manga->wp_manga_search_filter_url( 'new-manga' ) : '?m_orderby=new-manga'; ?>">
				<?php esc_html_e('Mới', WP_MANGA_TEXTDOMAIN);  ?>
			</a>
		</li>
	</ul>
</div>

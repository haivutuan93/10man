<?php
/*
* User Setting page
*/
?>
<?php global $wp_manga_functions,$wp_manga_template,$wp_manga_setting,$wp_manga_user_actions, $wp_manga_setting;
	$user_page = $wp_manga_setting->get_manga_option('user_page');
?>
<div class="wp-manga-container">
	<div id="manga-user-setting-tab">
		<div class="nav-tabs-wrap">
		    <ul class="nav nav-tabs">
			    <?php
			    	$actions = $wp_manga_user_actions->set_user_actions();
			    	$user_page = get_the_permalink( $user_page );
			    	foreach ( $actions as $action ) :
			    		$link = add_query_arg( array( 'user-tab-action' => $action['action'] ), $user_page );
			    		$current_tab_active = isset( $_GET['user-tab-action'] ) ? $_GET['user-tab-action'] : 'bookmark';
			    		?>
			    		<li class="<?php echo $wp_manga_functions->activated( $current_tab_active , $action['action'] ); ?>"><a href="<?php echo esc_url( $link ); ?>"><i class="<?php echo $action['icon'] ?>"></i><?php echo esc_attr( $action['title'] ) ?></a></li>
			    	<?php endforeach;
			    ?>
			</ul>
		</div>
	</div>
	<div id="manga-user-content-tab">
		<div class="tabs-content-wrap">
		    <div class="tab-content">
			  	<?php
					$wp_manga_template->load_template( 'user/page/'.$current_tab_active, '', true );
			  	?>
			</div>
		</div>
	</div>
</div>

<?php

/**
 * Information about the author 0.2.3
 */

if ( !function_exists( 'nkuttler0_2_4_links' ) ) {
	function nkuttler0_2_4_links( $plugin, $url_plugin = false, $facebook_url = 'http://www.facebook.com/sgkarthik/' ) {

		$name               = 'Alagappan Karthikeyan';
		$gravatar           = '67b97a9535720cd438e933e9144dae9b';
		$url_author         = 'http://www.karthik.sg';
		$profile            = 'http://wordpress.org/extend/plugins/profile/karthiksg/';
?>

		<div id="nkbox" >
			<!-- <strong><?php _e( 'Do you like this plugin?', $plugin ) ?></strong> -->
			<div class="gravatar" >
				     <h5>Author</h5>
                                    <a href="<?php echo $url_author ?>"><img src="http://www.gravatar.com/avatar/<?php echo $gravatar ?>?s=50" alt="<?php echo $name ?>" title="<?php echo $name ?>" /></a>
				<br />
				<?php echo $name ?>
			</div>
<?php
	}
}

?>

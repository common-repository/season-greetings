<?php

/**
 * Install hook
 *
 * @todo use one option... somewhen
 */
function seasongreets_activate() {
	if ( !get_option( 'seasongreets_snowflakes' ) ) {
	    update_option( 'seasongreets_snowflakes', '10' );
	    update_option( 'seasongreets_timeout', '80' );
	    update_option( 'seasongreets_maxstepx', '10' );
	    update_option( 'seasongreets_maxstepy', '10' );
	    update_option( 'seasongreets_selected', 'flake2.gif,flake3.gif');
	    update_option( 'seasongreets_maxtime', '20' );
	    update_option( 'seasongreets_uri', '' );
	    update_option( 'seasongreets_precise', '' );
	    update_option( 'seasongreets_flakesize', '40' );
	    update_option( 'seasongreets_invert', 'No' );
	}
}

/**
 * Uninstall hook
 */
function seasongreets_uninstall() {
    delete_option( 'seasongreets_snowflakes' );
    delete_option( 'seasongreets_timeout' );
    delete_option( 'seasongreets_maxstepx' );
    delete_option( 'seasongreets_maxstepy' );
    delete_option( 'seasongreets_selected' );
    delete_option( 'seasongreets_maxtime' );
    delete_option( 'seasongreets_uri' );
    delete_option( 'seasongreets_precise' );
    delete_option( 'seasongreets_flakesize' );
    delete_option( 'seasongreets_invert' );
}

/**
 * Add options page
 */
function seasongreets_add_pages() {
	$page = add_options_page( __( 'Season Greetings', 'seasongreets' ), __( 'Season Greetings', 'seasongreets' ), 10, 'seasongreets', 'seasongreets_options_page' );
	add_action( 'admin_head-' . $page, 'seasongreets_css_admin' );

	// Add icon
	add_filter( 'ozh_adminmenu_icon_seasongreets', 'seasongreets_icon' );
}

/**
 * Return admin menu icon
 *
 * @return string path to icon
 *
 * @since 0.9.1.1
 */
function seasongreets_icon() {
	global $seasongreets;
	return $seasongreets['url'] . 'pic/weather_snow.png';
}

/**
 * Load admin CSS style
 *
 * @since 0.9.0
 *
 */
function seasongreets_css_admin() {
	global $seasongreets; ?>
    <link rel="stylesheet" href="<?php echo $seasongreets['url'] . 'css/admin.css?v=0.2.4' ?>" type="text/css" media="all" /> <?php
echo $seasongreets['url'];
}



/**
 * The options page
 *
 * @todo Makes this, erm, better. Or not. It was my first plugin.
 */
function seasongreets_options_page() {
	if ( current_user_can( 'manage_options' ) ) { ?>
		<div class="wrap" >  <?php
			if ( $_POST['seasongreets'] ) {
				#function_exists( 'check_admin_referer' ) ? check_admin_referer( 'seasongreets' ) : null;
				$nonce = $_POST['_wpnonce'];
				if ( !wp_verify_nonce( $nonce, 'seasongreets') ) die( 'Security check' );

				update_option('seasongreets_snowflakes', $_POST['seasongreets_snowflakes']);
				update_option('seasongreets_uri', $_POST['seasongreets_uri']);
				update_option('seasongreets_precise', $_POST['seasongreets_precise']);
				update_option('seasongreets_timeout', $_POST['seasongreets_timeout']);
				update_option('seasongreets_maxstepx', $_POST['seasongreets_maxstepx']);
				update_option('seasongreets_homelink', $_POST['seasongreets_homelink']);
				update_option('seasongreets_maxstepy', $_POST['seasongreets_maxstepy']);
				update_option('seasongreets_maxtime', $_POST['seasongreets_maxtime']);
				update_option('seasongreets_invert', $_POST['seasongreets_invert']);

				// todo why array? single flake?
				if ( is_array( $_POST['seasongreets_selected'] ) ) {
					update_option( 'seasongreets_selected', $_POST['seasongreets_selected'] );
				}
				else {
					update_option( 'seasongreets_selected', array( 'flake2.gif','flake3.gif' ) );
				}
				update_option( 'seasongreets_flakesize', $_POST['seasongreets_flakesize'] );
			}
		} ?>

		<h2><?php _e( 'Season Greetings', 'seasongreets' ) ?></h2> <?php

		require_once( 'jack.php' );
		nkuttler0_2_4_links(
			'seasongreets',
			'http://projects.karthik.sg/wp/'
		); ?>

		<h3><?php _e( 'Settings', 'seasongreets' ) ?></h3>
		<form action="" method="post">
			<?php if ( function_exists('wp_nonce_field') ) wp_nonce_field( 'seasongreets' )  ?>
			<input type="hidden" name="seasongreets" value="hello!" />
			<table class="form-table" id="clearnone" >
				<tr>
					<th>
						<label>
							<?php _e( 'Show how many snowflakes (or other objects)?', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_snowflakes" > <?php
							$select = get_option('seasongreets_snowflakes');
							for ($i = 20 ; $i >= 0; $i--) {
								if ( $i == $select ) {
									echo "<option selected>$i</option>\n";
								}
								else {
									echo "<option>$i</option>\n";
								}
							} ?>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<label>
							<?php _e( 'Which of these flakes, drops, leaves and balloons do you want? ', 'seasongreets' ) ?>
						</label>
					</th>
					<td> <?php
						seasongreets_listpics( '<b>GIF</b>', '/\.gif$/' );
						seasongreets_listpics( '<b>PNG</b> Don\'t use them if you care about <a href="http://www.stoplivinginthepast.com/" target="_blank" >IE6</a> support.', '/\.png$/' );
						seasongreets_listpics( '<b>Rest</b>', '/!(\.(gif|png)$)/' ); ?>
					</td>
				</tr>

				<tr>
					<th>
						&nbsp;<!-- yes -->
					<td>
						<?php _e( 'By the way if you have nice snowflakes, drops, leaves etc. feel free to submit them to me if you made them yourself.', 'seasongreets' ) ?>
					</td>
				</tr>

				<tr>
					<th>
						<label>
							<?php _e( 'Use the balloon mode? This will make all images float upwards.', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_invert">
							<option value="Yes" <?php
								if (get_option('seasongreets_invert') === 'Yes') {
									echo "selected";
								}?>><?php _e( 'Yes', 'seasongreets' ) ?></option>
							<option <?php
								if (get_option('seasongreets_invert') !== 'Yes') {
									echo "selected";
								}?>><?php _e( 'No', 'seasongreets' ) ?></option>
						</select>
					</td>
				</tr>
			</table>

			<p class="submit" >
				<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'seasongreets' ) ?>" />
			</p>

			<h3><?php _e( 'Custom images', 'seasongreets' ) ?></h3>
			<p>
				<?php _e( 'If you add your own images to the <tt>pics</tt> directory they will appear in the table above. To have them disappear properly when they are leaving the visible part of the browser window you may have to change the <tt>flakesize</tt> value.', 'seasongreets' ) ?>
				<br />
				<?php _e( "Make sure the value is bigger than your highest image's height and broadest image's width.", 'seasongreets' ) ?>
			</p>

			<table class="form-table">
				<tr>
					<th>
						<label>
							<?php _e( 'Flakesize?', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_flakesize" > <?php
						$select = get_option('seasongreets_flakesize');
						for ($i = 20 ; $i <= 500; $i = $i + 10) {
							if ( $i == $select ) {
								echo "<option selected>$i</option>\n";
							}
							else {
								echo "<option>$i</option>\n";
							}
						} ?>
						</select>
					</td>
				</tr>
			</table>

			<p class="submit" >
				<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'seasongreets' ) ?>" />
			</p>

			<h3><?php _e( 'Pro settings', 'seasongreets' ) ?></h3>

			<table class="form-table">
				<tr>
					<th>
						<label>
							<?php _e( 'Stop snow after how many seconds?', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<input type="text" name="seasongreets_maxtime" value="<?php echo get_option('seasongreets_maxtime'); ?>" size="3">
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( 'Overall speed (timeout in milliseconds between moves)? ', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_timeout" > <?php
							$select = get_option('seasongreets_timeout');
							for ($i = 40 ; $i <= 500; $i = $i + 40) {
								if ( $i == $select ) {
									echo "<option selected>$i</option>\n";
								}
								else {
									echo "<option>$i</option>\n";
								}
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( 'Maximum Wind strength ', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_maxstepx" > <?php
							$select = get_option('seasongreets_maxstepx');
							for ($i = 1 ; $i <= 20; $i++) {
								if ( $i == $select ) {
									echo "<option selected>$i</option>\n";
								}
								else {
									echo "<option>$i</option>\n";
								}
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( 'Maximum Falling speed', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_maxstepy" > <?php
							$select = get_option('seasongreets_maxstepy');
							for ($i = 3 ; $i <= 20; $i++) {
								if ( $i == $select ) {
									echo "<option selected>$i</option>\n";
								}
								else {
									echo "<option>$i</option>\n";
								}
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( 'Show snowflakes only on pages whose URI contains', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<input type="text" value="<?php echo get_option('seasongreets_uri'); ?>" name="seasongreets_uri" />
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( "Show snowflakes only if the URI given above and the URI are equal (\$_SERVER['REQUEST_URI'] == URI string)?", 'seasongreets' ) ?>
						</label>
					</th>
					<td>
		   				<input type="checkbox" name="seasongreets_precise" <?php
							if (get_option('seasongreets_precise') === 'on') {
								echo "checked";
							}?>>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e( 'Hide the &quot;Powered by&quot; message in the footer?', 'seasongreets' ) ?>
						</label>
					</th>
					<td>
						<select name="seasongreets_homelink">
							<option value="Yes" <?php
								if (get_option('seasongreets_homelink') === 'Yes') {
									echo "selected";
								}?>><?php _e( 'Yes', 'seasongreets' ) ?></option>
							<option value="No" <?php
								if (get_option('seasongreets_homelink') !== 'Yes') {
									echo "selected";
								}?>><?php _e( 'No', 'seasongreets' ) ?></option>
						</select>
					</td>
				</tr>
			</table>

			<p class="submit" >
				<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'seasongreets' ) ?>" />
			</p>


		</form>
	</div> <?php
}

/**
 * List pictures with proper markup
 *
 * @since 0.9.2
 */
function seasongreets_listpics( $header, $pattern = null ) {
	global $seasongreets;
	$dirArray = seasongreets_dirArray( $pattern );
	if ( !isset( $dirArray ) )
		return;
	$selected_array = get_option('seasongreets_selected');

	// 0.7.3 had some incompatible changes, check
	if (!is_array($selected_array)) {
		$selected_array = array('flake2.gif', 'flake3.gif');
	} ?>

	<?php echo $header ?>

	<div class="seasongreets_select_wrap"> <?php

		for ($i = 0 ; $i < count($dirArray); $i++) { ?>
			<div class="seasongreets_select" > <?php
				if ( is_integer(array_search($dirArray[$i], $selected_array)) ) {
					echo "<input type=\"checkbox\" name=\"seasongreets_selected[]\" value=\"$dirArray[$i]\" checked />";
				}
				else {
					echo "<input type=\"checkbox\" name=\"seasongreets_selected[]\" value=\"$dirArray[$i]\" />";
				}
				echo '<br>';
				echo '<img src="' . $seasongreets['url'] . "pics/" . $dirArray[$i] . "\" />"; ?>
			</div >
			<?php
		} ?>
		<div style="clear:both;">&nbsp;</div>
	</div> <?php
}
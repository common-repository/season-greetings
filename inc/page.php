<?php

function seasongreets() {
	if ( get_option('seasongreets_uri') ) {
		if (
			(
				get_option( 'seasongreets_precise' ) !== 'on' &&
				strpos( $_SERVER['REQUEST_URI'], get_option( 'seasongreets_uri' ) ) > 0
			) ||
			(
				get_option('seasongreets_precise') === 'on' &&
				strcmp( $_SERVER['REQUEST_URI'], get_option( 'seasongreets_uri' ) ) === 0
			)
		) {
			add_action( 'wp_head', 'seasongreets_head' );
			add_action( 'wp_footer', 'seasongreets_footer' );

			add_action( 'wp_footer', 'seasongreets_homelink' );
		}
	} // default: enable
	elseif  ( !get_option( 'seasongreets_uri' ) ) {
		add_action( 'wp_head', 'seasongreets_head' );
		add_action( 'wp_footer', 'seasongreets_footer' );

		add_action( 'wp_footer', 'seasongreets_homelink' );
	}
}
seasongreets();

/**
 * Add the necessary JS variables and include the wonderful snow script
 */
function seasongreets_head() { ?>
<!-- seasongreets -->
<script type="text/javascript">
nks = new Object;
nks.snowflakes = <?php
	echo get_option('seasongreets_snowflakes');
?>;
nks.timeout = <?php
	echo get_option('seasongreets_timeout');
?>;
nks.maxstepx = <?php
	echo get_option('seasongreets_maxstepx');
?>;
nks.maxstepy = <?php
	echo get_option('seasongreets_maxstepy');
?>;
nks.flakesize = <?php
	echo get_option('seasongreets_flakesize');
?>;
nks.maxtime = <?php
	echo get_option('seasongreets_maxtime') * 1000;
?>;
nks.invert = <?php
	if ( get_option('seasongreets_invert') == 'Yes' ) {
		echo '-1';
	}
	else {
		echo '1';
	}
?>;
</script>
<script src="<?php global $seasongreets; echo $seasongreets['url'] . '/snow.js'; ?>" type="text/javascript"></script>
<!-- /seasongreets -->
<?php
}

/**
 * Put the images into the HTML code
 */
function seasongreets_footer() {
	$snowflakes = get_option('seasongreets_snowflakes');
	$selected_array = get_option('seasongreets_selected');
	$dirArray = seasongreets_dirArray();
	$arraymax = count($selected_array) - 1;
	global $seasongreets;

	if (!is_array($selected_array)) {
    	$selected_array = array('flake2.gif', 'flake3.gif');
	}

	
	foreach($selected_array as $selected) {
		if ( !file_exists( $seasongreets['path'] . '/pics/' . $selected ) ) {
    		$selected_array = array( 'flake2.gif', 'flake3.gif' );
		}
	}

	for ( $i = 0; $i < $snowflakes; $i++ ) {
		echo "\n<img id=\"seasongreets$i\" src=\"" . $seasongreets['url'] . '/pics/' . $selected_array[rand(0, $arraymax)] . "\" style=\"position: fixed; top: -100px; border: 0; z-index:1000;\" class=\"seasongreets\" alt=\"snowflake\" />";
	}
}

function seasongreets_homelink() {
	$url = 'http://projects.karthik.sg/wp/';
	if ( !( get_option('seasongreets_homelink' ) === 'Yes' ) ) {
		if ( get_option( 'seasongreets_invert' ) === 'Yes' ) {
			printf( __( "<a href=\"%s\">Wordpress Season Greetings</a> powered by <a href=\"%s\">seasongreets</a>", 'seasongreets' ), $url, $url );
		} else {
			printf( __( "<a href=\"%s\">Season Greetings</a> powered by <a href=\"%s\">seasongreets</a>", 'seasongreets' ), $url, $url );
		} ?>
		<br /> <?php
	}
}

function seasongreets_dirArray( $pattern = null ) {
	global $seasongreets;
	$picpath = $seasongreets['path'] . '/pics/';
	if ( $picdir = opendir( $picpath ) ) {
		while( $entryName = readdir( $picdir ) ) {

			if( $entryName == '.' || $entryName == '..' || $entryName == '.svn' )
				continue;
			elseif ( isset( $pattern ) )
				if( !preg_match( "$pattern", $entryName ) )
					continue;

			$dirArray[] = $entryName;
		}
	}
	closedir( $picdir );

	if( isset( $dirArray ) ) {
		sort( $dirArray );
		return $dirArray;
	}
}

?>

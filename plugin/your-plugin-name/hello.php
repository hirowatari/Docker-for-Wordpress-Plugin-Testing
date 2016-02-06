<?php
/**
 * @package Hello_Everett
 * @version 1.6
 */
/*
Plugin Name: Hello Everett
Description: This is not just a plugin, it is Everett's favourite plugin.
Version: 1.6
*/

function hello_everett_get_lyric() {
	/** These are the lyrics to Hello Everett */
	$lyrics = "Hello, Everett
Well, hello, Everett
It's so nice to have you back where you belong
You're lookin' swell, Everett
I can tell, Everett
You're still glowin', you're still crowin'
You're still goin' strong
We feel the room swayin'
While the band's playin'
One of your old favourite songs from way back when
So, take her wrap, fellas
Find her an empty lap, fellas
Everett'll never go away again
Hello, Everett
Well, hello, Everett
It's so nice to have you back where you belong
You're lookin' swell, Everett
I can tell, Everett
You're still glowin', you're still crowin'
You're still goin' strong
We feel the room swayin'
While the band's playin'
One of your old favourite songs from way back when
Golly, gee, fellas
Find her a vacant knee, fellas
Everett'll never go away
Everett'll never go away
Everett'll never go away again";

	// Here we split it into lines
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function hello_everett() {
	$chosen = hello_everett_get_lyric();
	echo "<p id='everett'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hello_everett' );

// We need some CSS to position the paragraph
function everett_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#everett {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'everett_css' );

?>

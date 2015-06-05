<?php
/*
 * Contains debug/troubleshooting information
 *	
 * LICENSE: The MIT License (MIT)
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    http://choosealicense.com/licenses/mit/
*/

	# Gets the GET value if the delete icon was pressed
	$phpinfo = S2T_Functions::get_GET_string("view-phpinfo");
	$viewinfo_url = S2T_Functions::get_server_path_request() . "&view-phpinfo=1";

	if ($phpinfo != "") {
		echo phpinfo();
		die();	#prevents some of WP table styling to take effect.
	}
	
?>

<div class="wrap">
	<h2>Sheets2Table - Troubleshooting</h2>
	<p>Problems?</p>
	<ol>
		<li>Your current PHP version is <code><?php echo phpversion(); ?></code>. This plugin requires <code><?php echo PHP_REQUIRED_VERSION; ?></code>.</li>
		<li>Check the <?php echo S2T_Functions::get_debug_log_file_url("debug.log"); ?> file. Please note: debug mode must be enabled.</li>
		<li>Display the <a href="<?php echo $viewinfo_url; ?>" target="_blank">PHP info</a> to check file versions, etc.</li>
	</ol>
</div>

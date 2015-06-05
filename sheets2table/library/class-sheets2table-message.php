<?php
/*
 * Displays messages in the admin panel
 *	
 * LICENSE: The MIT License (MIT)
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    http://choosealicense.com/licenses/mit/
*/


/*
  Example of class use: 
 	$s2t_message = new S2T_Message();
 	$s2t_message->print($message, $s2t_message->information);
-OR-
 	global $s2t_message;
 	$s2t_message->print($message, $s2t_message->information);
*/

/**
 * This class writes messages to the admin panel. 
 *
 * @since 0.4.0
 *
 */
Class S2T_Message {

	public $shortcode = "shortcode";
	public $information = "information";
	public $error = "error";
	public $warning = "warning";
	public $success = "success";
	
	/*
	 * Prints a message to the screen in HTML format
	 *
	 * @since 0.4.0
	 *
	 * @param string $message Message to display
	 * @param string $type Type of message to display
	*/
	function print_message($message, $type = "") {
		
		if ($type == "")
			$type = $this->information;
	
	?>
		<p class="message <?php echo $type; ?>"> <?php echo $message; ?></p>
	<?php
	}
}
	# The global instance
	$s2t_message = new S2T_Message();

?>
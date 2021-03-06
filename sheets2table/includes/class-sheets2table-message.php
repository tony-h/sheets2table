<?php
/*
 * Displays messages in the admin panel
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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

	/**
	 * The text for the shortcode message
	 *
	 * @since 0.4.0
	 * @var string $shortcode
	 */
	public $shortcode = "shortcode";
	/**
	 * The text for the information message
	 *
	 * @since 0.4.0
	 * @var string $information
	 */
	public $information = "information";
	/**
	 * The text for the error message
	 *
	 * @since 0.4.0
	 * @var string $error
	 */
	public $error = "error";
	/**
	 * The text for the warning message
	 *
	 * @since 0.4.0
	 * @var string $warning
	 */
	public $warning = "warning";
	/**
	 * The text for the success message
	 *
	 * @since 0.4.0
	 * @var string $success
	 */
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
		
		if ($type == "") {
			$type = $this->information;
		}
	
	?>
		<p class="message <?php echo $type; ?>"> <?php echo $message; ?></p>
	<?php
	}
}
	# The global instance
	$s2t_message = new S2T_Message();

?>
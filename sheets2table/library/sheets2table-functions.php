<?php
/*
 * General functions or utilities for Sheets2Table
 *	
 * LICENSE: The MIT License (MIT)
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    http://choosealicense.com/licenses/mit/
*/


/**
 * S2T_Functions contains commonly used static functions 
 *
 * @since 0.4.0
 *
 */
 Class S2T_Functions {

	/*  
	 * Writes a single setting value to a file. 
	 * Used this instead of writing it to the WP database settings table. 
	 * However, this should be removed and use the settings library
	 * @since 0.4.0
	 *
	 * @param $file_name name of file in which to the write the setting to
	 * @param $value value to write to the file
	 */
	public static function write_to_settings_file($file_name, $value) {

		$value = trim($value);
		$full_path = SHEETS2TABLE_RESOURCES_DIR . "/$file_name";
		file_put_contents($full_path, $value);	
	}

	/*  
	 * Reads a single setting value from the file. 
	 *
	 * @since 0.4.0
	 *
	 * @param $file_name name of file in which read the value from
	 * @return string value from the settings file
	 */
	public static function read_from_settings_file($file_name) {

		$full_path = SHEETS2TABLE_RESOURCES_DIR . "/$file_name";

		if (file_exists($full_path)) {
			return file_get_contents($full_path);
		}
			
		return "";
	}

	/*
	 * Get the debug log file located in the content dir
	 *
	 * @since 0.4.0
	 *
	 * @param string $text_for_anchor_tag Optional. optional text for the anchor tag
	 * @return string URL or the <a> tag of the debug.log file
	 */
	public static function get_debug_log_file_url($text_for_anchor_tag = "") {

		$url = content_url() . "/debug.log";

		# If no text is specified, don't build the <a> tag.
		if ($text_for_anchor_tag == "") {
			return $url;
		}

$anchor_tag = <<<EOD
<a target="_blank" href="$url">$text_for_anchor_tag</a>
EOD;

		return $anchor_tag;
	}

	/*
	 * Cleans the text of any white spaces and converts to underscores.
	 *
	 * @since 0.4.0
	 *
	 * @param array/string $data An array or string containing the text to change
	 * @return array/string array or string with the whitespaces
	 */
	public static function whitespace_to_underscore($data) {

		# If a string and not an array of strings
		if (!is_array($data)) {
		
			$value = $data;
		
			# Remove any trailing or leading white spaces
			# Change the rest to ' '
			
			$value = trim($value);
			$value = str_replace(' ', '_', $value);

			return $value;
		} 

		$array = $data;
		
		for ($i = 0; $i < count($array); $i++) {

			# Remove all white spaces
			$array[$i] = trim($array[$i]);
			$array[$i] = str_replace(' ', '_', $array[$i]);
		}

		return $array;
	}

	/*
	 * Converts any underscores in the text to whitespace
	 *
	 * @since 0.4.0
	 *
	 * @param array/string $data An array or string containing the text to change
	 * @return array/string array or string with the whitespaces
	 */
	public static function underscore_to_whitespace($data) {
		
		# If a string and not an array of strings
		if (!is_array($data)) {
		
			$value = $data;
		
			# Remove any trailing or leading white spaces
			# Change the rest to ' '
			
			$value = trim($value);
			$value = str_replace('_', ' ', $value);

			return $value;
		} 
		
		$array = $data;
		
		for ($i = 0; $i < count($array); $i++) {

			# Remove any trailing or leading white spaces
			# Change the rest to ' '
			
			$array[$i] = trim($array[$i]);
			$array[$i] = str_replace('_', ' ', $array[$i]);
		}

		return $array;
	}

	/**
	 * Get the variable equivalent to $_SERVER['REQUEST_URI']. This provides a single
	 * location to change URI, if needed.
	 *
	 * @since 0.4.0
	 *
	 * @return string containing the URI which was given in order to access 
	 * this page; for instance, '/index.html'
	 */
	public static function get_server_path_request() {
		
		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		return $uri;
	}

	/*
	 * Gets the string value from POST
	 *
	 * @since 0.4.0
	 *
	 * @param string $name name of the variable in POST: $_POST['my_var']
	 * @returns string of the POST data or an empty string
	 */
	public static function get_POST_string($name) {

		$post_string = "";
		
		# If the POST data contains data, use it. Otherwise, return an empty array
		if (isset($_POST[$name]) && $_POST[$name] != "")
			$post_string = $_POST[$name];

		return $post_string;
	}

	/*
	 * Gets the string value from GET
	 *
	 * @since 0.4.0
	 *
	 * @param string $name name of the variable in GET: $_GET['my_var']
	 * @returns string of the GET data or an empty string
	 */
	public static function get_GET_string($name) {

		$get_string = "";
		
		# If the POST data contains data, use it. Otherwise, return an empty array
		if (isset($_GET[$name]) && $_GET[$name] != "")
			$get_string = $_GET[$name];

		return $get_string;
	}

	/**
	 * Gets the array from POST
	 *
	 * @since 0.4.0
	 *
	 * @param string $name name of the variable in POST: $_POST['my_var']
	 * @returns array of the POST data or an empty array
	 */
	public static function get_POST_array($name) {

		$post_array = array();
		
		# If the POST data contains data, use it. Otherwise, return an empty array
		if (isset($_POST[$name]) && count($_POST[$name]) > 0)
			$post_array = $_POST[$name];

		return $post_array;
	}

	/**
	 * Trims the whitespace before and after the value
	 *
	 * @since 0.4.0
	 *
	 * @param array $array to trim the text from
	 * @returns array with the trimmed whitespace
	 */
	 public static function trim_array_values($array) {
	 
		for ($i = 0; $i < count($array); $i++) {
			$array[$i] = trim($array[$i]);
		}
		
		return $array;
	 }
}

?>
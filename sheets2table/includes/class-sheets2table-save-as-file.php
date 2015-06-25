<?php
/*
 * Provides functionality for the save-as file
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * S2T_Save_As_File handles information and procedures surrounding the 
 * save-as name of the file. I.E. my-file.csv
 * This class creates a separate file called my-file.txt that contains the
 * ID of the Google sheet for reverse lookup
 *
 * @since 0.4.1
 *
 */
Class S2T_Save_As_File {

	/**
	 * The file name of the save-as file without the extension.
	 *
	 * @since 0.4.1
	 * @access private
	 * @var string $_file_name
	 */
	private $_file_name;
	/**
	 * The file extension for a txt file
	 *
	 * @since 0.4.1
	 * @access private
	 * @var string $_txt_extension
	 */
	private $_txt_extension = ".txt";
	
	/**
	 * Instantiates the class with the save-as file name
	 *
	 * @since 0.4.1
	 *
	 * @param string $file_path file name of save-as file
	 */
	public function __construct($file_name) {
	
		# Nothing to do. Exit and let other errors fly
		if ($file_name == "") {
			return;
		}
		
		# Set the variables and save the sheet ID
		$this->_file_name = $this->_remove_file_extension($file_name);
	}

	/**
	 * Removes the file extension from a file name
	 * 
	 * @param string $file_name name of the file to remove the extension from
	 * 
	 * @return string file name without the extension
	 */
	private function _remove_file_extension($file_name) {

		$path_parts = pathinfo($file_name);
		return $path_parts['filename'];
	}

	
	/**
	 * Associates the ID of the Google Sheet to this file name by writing the
	 * ID to a settings file
	 *
	 * @since 0.4.1
	 */
	public function save_google_sheet_id($google_sheet_id) {
		
		$file_name = $this->_file_name . $this->_txt_extension;
		S2T_Functions::write_to_settings_file($file_name, $google_sheet_id);
	}
	
	/**
	 * Gets the ID of the Google sheet associated with this file name
	 *
	 * @since 0.4.1
	 */
	public function get_google_sheet_id() {

		$file_name = $this->_file_name . $this->_txt_extension;
		return S2T_Functions::read_from_settings_file($file_name);
	}
	
	/**
	 * Deletes the file from the server
	 *
	 * @since 0.4.1
	 *
	 * @returns bool value of true if successfully deleted, otherwise returns false
	 */
	public function delete() {
		
		$file_path = $GLOBALS['Sheets2Table']->get_resources_dir() . "/" . 
						$this->_file_name . $this->_txt_extension; 
		
		if (file_exists($file_path)) {
			return unlink($file_path);
		} 
		
		return false;
	}
}

?>
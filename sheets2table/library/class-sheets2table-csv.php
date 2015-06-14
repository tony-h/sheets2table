<?php
/*
 * Provides functionality for the CSV file
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
 * S2T_CSV handles the CSV file and all functions surrounding it
 *
 * @since 0.4.0
 *
 */
Class S2T_CSV {

	/**
	 * The file name of the CSV file.
	 *
	 * @since 0.4.0
	 * @access private
	 * @var string $_file_name
	 */
	private $_file_name;
	/**
	 * The fully qualified path of the file
	 *
	 * @since 0.4.0
	 * @access private
	 * @var string $_file_path
	 */
	private $_file_path;
	/**
	 * The part of the file name that indicates it is a backup
	 *
	 * @since 0.4.0
	 * @access private
	 * @var string $_backup_slug
	 */
	private $_backup_slug = "-backup.csv";
	
	/**
	 * Instantiates the class with a CSV file
	 *
	 * @since 0.4.0
	 *
	 * @param string $file_path or file name name of CSV file
	 */
	public function __construct($file_path) {
	
		# Nothing to do. Exit and let other errors fly
		if ($file_path == "") {
			return;
		}
		
		# If the file doesn't exist, assume it is only the file name. 
		# Prepend the dir to the file name
		if (!file_exists($file_path)) {
			$this->_file_name = $file_path;
			$this->_file_path = $GLOBALS['Sheets2Table']->get_resources_dir() . "/$file_path";
		} else {
			$this->_file_name = basename($file_path);
			$this->_file_path = $file_path; 
		}
	}	
	
	/**
	 * Returns true or false if the file is valid and contains data
	 *
	 * @since 0.4.0
	 *
	 * @return boolean true if the file exists and contains data; otherwise return false
	 */
	public function is_valid_file() {
		
		if (!file_exists($this->_file_path) || filesize($this->_file_path) <= 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Deletes the CSV file from the server
	 *
	 * @since 0.4.0
	 *
	 * @returns bool value of true if successfully deleted, otherwise returns false
	 */
	public function delete() {
		
		if (file_exists($this->_file_path)) {
			return unlink($this->_file_path);
		}
	}
	
	/**
	 * Creates a copy of the CSV file as a backup
	 *
	 * @since 0.4.0
	 *
	 * @returns bool value of true if successfully backed up, otherwise returns false
	 */
	public function backup() {
	
		$result = false;
	
		if ($this->is_valid_file()) {
	
			# Append the new backup slug to the file and path
			$backup_file_name = $this->_file_name . $this->_backup_slug;
			$backup_file_path = $this->_file_path . $this->_backup_slug;
			
			$result = rename($this->_file_path, $backup_file_path);
			
			# If successful on the rename, set the updated file path
			if ($result) {
				$this->_file_path = $backup_file_path;
				$this->_file_name = $backup_file_name;
			}
		}
		
		return $result;
	}
	
	/**
	 * Restores a backed up up CSV file
	 *
	 * @since 0.4.0
	 *
	 * @returns bool value of true if successfully restored, otherwise returns false
	 */
	public function restore() {
		$result = false;
	
		if ($this->is_valid_file()) {
	
			# Remove the backup slug to the file and path
			$restore_file_name = str_replace($this->_backup_slug, '', $this->_file_name);
			$restore_file_path = str_replace($this->_backup_slug, '', $this->_file_path);
			
			$result = rename($this->_file_path, $restore_file_path);
			
			# If successful on the rename, set the updated file path
			if ($result) {
				$this->_file_path = $restore_file_path;
				$this->_file_name = $restore_file_name;
			}
		}
		
		return $result;	
	}
	
	/**
	 * Gets the name of the file. i.e: my-file.csv
	 *
	 * @since 0.4.0
	 *
	 * @return string the name of file
	 */
	public function get_file_name() {
		return $this->_file_name;
	}
	
	/**
	 * Gets the fully qualified path to the file. 
	 *
	 * @since 0.4.0
	 *
	 * @return string path to the file
	 */
	public function get_file_path() {
		return $this->_file_path;
	}
	
	/**
	 * Gets the fully qualified URL to the file. 
	 *
	 * @since 0.4.0
	 *
	 * @return string URL of the file
	 */
	public function get_file_url() {
		return $GLOBALS['Sheets2Table']->get_resources_url() . "/" . $this->_file_name; 
	}	
	
	/**
	 * Gets the size of the file in a human readable format
	 *
	 * @since 0.4.0
	 *
	 * @return string the size of the file in a human readable format
	 */
	public function get_file_size() {
	
		$file_size = filesize($this->get_file_path());
		 
		# Display in human readable format
		# 1048576 = 1024 * 1024 
		 
		if ($file_size >= 1048576 ) {
			$file_size = (int)($file_size / 1048576) . " MB"; 
		} elseif ($file_size >= 1024) {
			$file_size = (int)($file_size / 1024) . " KB"; 
		} else {
			$file_size = $file_size . " bytes";
		}

		return $file_size;	
	}
	
	/**
	 * Gets the array form of the CSV file
	 *
	 * @since 0.4.0
	 *
	 * @return array_map a 2D array of the CSV data if valid file, otherwise an empty array.
	 */
	public function convert_to_array() {

		$array = array();
	
		if ($this->is_valid_file()) {
			$array = array_map('str_getcsv', file($this->_file_path));
		}
		
		return $array;
	}	
	
	/**
	 * Gets the CSV column headers (row[0])
	 *
	 * @since 0.4.0
	 *
	 * @return array containing the CSV headers if valid file, otherwise an empty array.
	 */
	public function get_headers() {
	
		$array = $this->convert_to_array();
		
		if (count($array) > 0) {
			return $array[0];
		} else {
			return array();
		}
	}
}

?>
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
	 * @param string $file_name file name of CSV file
	 */
	public function __construct($file_name) {
	
		# Nothing to do. Exit and let other errors fly
		if ($file_name == "") {
			return;
		}
		
		# Construct the file path to the CSV file in the resources dir
		$this->_file_name = basename($file_name);
		$this->_file_path = $GLOBALS['Sheets2Table']->get_resources_dir() . "/" . $this->_file_name; 
	}
	
	// -------------------- Begin static methods ----------------------------------
	
	/**
	 * Gets the URL to the Google sheet
	 *
	 * @since 0.4.1
	 *
     * @param string $google_sheets_id ID of the Google sheet to get the data from
	 */
	public static function get_googlesheet_csv_url($google_sheets_id) {
		
		# Construct the URL for the CSV file
		$csv_url = "https://docs.google.com/spreadsheets/d/" . 	
					$google_sheets_id .
					"/export?format=csv";

		return $csv_url;
	}
	
	// -------------------- Begin instance methods ----------------------------------
	
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
	 * Restores a backed up of the CSV file
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
	
	/**
	 * Gets the CSV data from the Google sheet using the sheet's ID
	 *
	 * @since 0.4.1
	 *
      * @param string $google_sheets_id ID of the Google sheet to get the data from
	 */
	public function populate_csv_file($google_sheets_id) {

		# Create a backup as to not overwrite the file in a failed download
		$s2t_csv_backup = new S2T_CSV($this->_file_name); 
		$backup_result = $s2t_csv_backup->backup();
		
		# Get the file contents
		$downloaded_csv_contents = @file_get_contents(S2T_CSV::get_googlesheet_csv_url($google_sheets_id));
		
		# Do not create an empty file if nothing was retrieved. An error will be generated later.
		if (!empty($downloaded_csv_contents)) {
			file_put_contents($this->_file_path, $downloaded_csv_contents);
		}
		
		$result = $this->is_valid_file();
		
		# Based on success, either delete the backup or restore the file
		if (!$result) {

			# Attempt to restore the file if a backup exists
			if ($backup_result) {
				$s2t_csv_backup->restore();
			}
			
		} else {
		
			# Delete the backup file, if one was created
			if ($backup_result) {
				$s2t_csv_backup->delete();
			}
		}
		
		# Return the result of retrieving the file and successfully saving it
		return $result;
	}	
}

?>
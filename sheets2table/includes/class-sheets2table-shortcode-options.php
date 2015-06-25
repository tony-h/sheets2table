<?php
/*
 * The S2T_Shortcode_Options class is storage container for the shortcodes. 
 * It also contains the master shortcode list.
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
 * This class contains the options for use in the shortcodes. 
 *
 * @since 0.4.0
 *
 */
 class S2T_Shortcode_Options {
	
	/**
	 * Array of all of the options
	 *
	 * @since 0.4.0
	 * @access private
	 * @var array $_options_list
	 */
	private $_options_list;
	
	# The option keys. The user sees these values in the config options,
	# so they need to be self-explanatory

	/**
	 * Option key to use FooTable
	 *
	 * @since 0.4.0
	 * @access private
	 * @var string $_use_foo_table_key
	 */
	private $_use_foo_table_key = "use-foo-table";
	/**
	 * Option key to always get the latest data from the Google spreadsheet
	 *
	 * @since 0.4.1
	 * @access private
	 * @var string $_get_latest_data_key
	 */
	private $_get_latest_data_key = "get-latest-data";
	
	/**
	 * Use FooTable boolean value
	 *
	 * @since 0.4.0
	 * @access private
	 * @var boolean $_use_foo_table
	 */
	private $_use_foo_table;
	/**
	 * Get latest boolean value
	 *
	 * @since 0.4.1
	 * @access private
	 * @var boolean $_get_latest_data
	 */
	private $_get_latest_data;
	
	/**
	 * Instantiates the class with a list of options
	 *
	 * @since 0.4.0
	 *
	 * @param array $options_array Optional. an optional list of options set the values of.
	 * 				Default value is false for all options
	 */
	public function __construct($options_array = array()) {
		$this->build_options_list();
		$this->set_option_values($options_array);
	}
	
	/**
	 * Creates a list of available options
	 *
	 * @since 0.4.0
	 * @access private
	 */
	private function build_options_list() {
		
		# There has to be better way of keeping a single reference to the key and have text...
		# The attempt was to have key=>value pair with human readable Key in 
		$this->_options_list = array();
		$this->_options_list[$this->_use_foo_table_key] = $this->_use_foo_table_key; 
		$this->_options_list[$this->_get_latest_data_key] = $this->_get_latest_data_key; 
	}
	
	/**
	 * Sets a list of options based on the provided options array
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 * @param array $options_array the list of options set the values of
	 */
	private function set_option_values($options_array) {
		
		if ($options_array == null || count($options_array) < 1) {
			return;
		}
		
		foreach ($options_array as $option) {
			
			if ($option == $this->_options_list[$this->_use_foo_table_key]) {
				$this->_use_foo_table = true;
			} else if ($option == $this->_options_list[$this->_get_latest_data_key]) {
				$this->_get_latest_data = true;
			}
		}
	}
	
	/**
	 * Gets the full list of available options for use
	 *
	 * @since 0.4.0
	 *
	 * @return array an array of all options
	 */
	public function get_options_array() {	
		return $this->_options_list;	
	}
	
	//---------------------------------------------------------------------------------
	// This section gets or sets the state of any set options
	// I opted for a one function fits all method that gets and sets
	
	/**
	 * Gets or sets the state of the use-foo-table option
	 *
	 * @since 0.4.0
	 *
	 * @param boolean $value of the state to set
	 * @return boolean the state of the value
	 */
	public function use_foo_table($value = "") {

		if ($value != "")
			$this->_use_foo_table = $value;
	
		return $this->_use_foo_table;	
	}
	
	/**
	 * Gets or sets the state of the get-latest-data option
	 *
	 * @since 0.4.1
	 *
	 * @param boolean $value of the state to set
	 * @return boolean the state of the value
	 */
	public function get_latest_data($value = "") {

		if ($value != "")
			$this->_get_latest_data = $value;
	
		return $this->_get_latest_data;	
	}
};

?>
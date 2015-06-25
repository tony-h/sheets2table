<?php
/*
 * The primary Sheets2Table plugin class
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

# Preliminary stuff

// Plugin base dir/url constants
define("SHEETS2TABLE_PLUGIN_BASE_DIR", plugin_dir_path( __FILE__ ));
define("SHEETS2TABLE_PLUGIN_BASE_URL", plugins_url('', __FILE__));

// Initialize the class
$GLOBALS['Sheets2Table'] = new Sheets2Table( __FILE__ );

// If logged in as admin, enable the admin panel
if ( 'is_admin' ) {
	require_once $GLOBALS['Sheets2Table']->get_admin_dir() . '/class-sheets2table-admin.php';
}

// Include the working files necessary for the plugin to function
require_once $GLOBALS['Sheets2Table']->get_includes_dir() . '/sheets2table-functions.php';
require_once $GLOBALS['Sheets2Table']->get_includes_dir() . '/class-sheets2table-resources.php';
require_once $GLOBALS['Sheets2Table']->get_includes_dir() . '/class-sheets2table-csv.php';
require_once $GLOBALS['Sheets2Table']->get_includes_dir() . '/class-sheets2table-save-as-file.php';
require_once $GLOBALS['Sheets2Table']->get_core_dir() . '/class-sheets2table-tables.php';
require_once $GLOBALS['Sheets2Table']->get_core_dir() . '/sheets2table-shortcodes.php';


/**
 * Sheets2Table class contains variables and information about the plugin
 *
 * @since 0.4.0
 *
 */
Class Sheets2Table {

	private $_render_table_shortcode = "sheets2table-render-table";
	private $_required_php_version = "5.3.0";	
	private $_admin_dir = "admin";
	private $_includes_dir = "includes";
	private $_core_dir = "core";
	private $_resources_dir = "resources";
			 

	 /**
	  * Gets the render table shortcode string
	  * 
	  * @since 0.4.0
	  *
	  * @return $string render table shortcode text
	  */
	function get_render_table_shortcode() {
		return $this->_render_table_shortcode;
	}
	
	 /**
	  * Gets the required PHP version of the plugin
	  * 
	  * @since 0.4.0
	  *
	  * @return $string require php version as a string
	  */
	function get_required_php_version() {
		return $this->_required_php_version;
	}
	
	// --------- This section contains getters for the URL and DIR --------- //
	
	 /**
	  * Gets the admin directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string path to the admin directory
	  */
	function get_admin_dir() {
		return SHEETS2TABLE_PLUGIN_BASE_DIR . '/' . $this->_admin_dir;
	}
	
	 /**
	  * Gets the URL to the admin directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string URL to the admin directory
	  */
	function get_admin_url() {
		return SHEETS2TABLE_PLUGIN_BASE_URL . '/' . $this->_admin_dir;
	}

	/**
	  * Gets the library directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string path to the library directory
	  */
	function get_includes_dir() {
		return SHEETS2TABLE_PLUGIN_BASE_DIR . '/' . $this->_includes_dir;
	}
	
	 /**
	  * Gets the URL to the includes/library directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string URL to the includes/library directory
	  */
	function get_library_url() {
		return SHEETS2TABLE_PLUGIN_BASE_URL . '/' . $this->_includes_dir;
	}
	
	 /**
	  * Gets the core files directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string path to the core directory
	  */
	function get_core_dir() {
		return SHEETS2TABLE_PLUGIN_BASE_DIR . '/' . $this->_core_dir;
	}
	
	 /**
	  * Gets the URL to the core directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string URL to the core directory
	  */
	function get_core_url() {
		return SHEETS2TABLE_PLUGIN_BASE_URL . '/' . $this->_core_dir;
	}
	
	 /**
	  * Gets the resources directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string path to the resources directory
	  */
	function get_resources_dir() {
		return SHEETS2TABLE_PLUGIN_BASE_DIR . '/' . $this->_resources_dir;
	}
	
	 /**
	  * Gets the URL to the resources directory
	  * 
	  * @since 0.4.0
	  *
	  * @return $string URL to the resources directory
	  */
	function get_resources_url() {
		return SHEETS2TABLE_PLUGIN_BASE_URL . '/' . $this->_resources_dir;
	}
}

?>
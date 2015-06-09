<?php
/*
 * Plugin Name: Sheets2Table
 * Description: Sheets2Table builds an HTML table from a Google Sheet
 * Version: 	0.4.0
 * Author: 		Tony Hetrick
 * Author URI:	http://www.tonyhetrick.com
 * License: 	GNU General Public License (GPL) version 3
 * License URI: https://www.gnu.org/licenses/gpl.html
 */

/*
	GNU General Public License (GPL) version 3

	Copyright (c) [2015] [tonyhetrick.com]

	Sheets2Table is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>

*/

// Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Plugin base dir/url constants
define("SHEETS2TABLE_PLUGIN_BASE_DIR", plugin_dir_path( __FILE__ ));
define("SHEETS2TABLE_PLUGIN_BASE_URL", plugins_url('', __FILE__));

// Load the plugin in class
require_once( 'class-sheets2table.php' );

// Initialize the class
$GLOBALS['Sheets2Table'] = new Sheets2Table(__FILE__);

// Include the working files necessary for the plugin to function
require_once $GLOBALS['Sheets2Table']->get_admin_dir() . '/class-sheets2table-admin.php';
require_once $GLOBALS['Sheets2Table']->get_library_dir() . '/sheets2table-functions.php';
require_once $GLOBALS['Sheets2Table']->get_library_dir() . '/class-sheets2table-resources.php';
require_once $GLOBALS['Sheets2Table']->get_library_dir() . '/class-sheets2table-csv.php';
require_once $GLOBALS['Sheets2Table']->get_core_dir() . '/class-sheets2table-tables.php';
require_once $GLOBALS['Sheets2Table']->get_core_dir() . '/sheets2table-shortcodes.php';

// Add settings link on plugin page
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", array($GLOBALS['Sheets2Table'],'sheets2table_settings_link' ));

?>
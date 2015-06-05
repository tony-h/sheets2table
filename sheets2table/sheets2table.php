<?php
/*
 * Plugin Name: Sheets2Table
 * Description: Sheets2Table builds an HTML table from a Google Sheet
 * Version: 	0.4.0
 * Author: 		Tony Hetrick
 * Author URI:	http://dev.tonyhetrick.com
 * License: 	The MIT License (MIT)
 * License URI: http://opensource.org/licenses/MIT
 */

/*
	The MIT License (MIT)

	Copyright (c) [2015] [tonyhetrick.com]

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
*/

# -- Constants / Statics / Globals -- #

// Shortcodes
define("SHEETS2TABLE_RENDER_TABLE_SHORTCODE", "sheets2table-render-table");

// Plugin base dir/url constants
define("SHEETS2TABLE_PLUGIN_BASE_DIR", plugin_dir_path( __FILE__ ));
define("SHEETS2TABLE_PLUGIN_BASE_URL", plugins_url('', __FILE__));

// One stop shop for changing dirs
define("SHEETS2TABLE_ADMIN_DIR", SHEETS2TABLE_PLUGIN_BASE_DIR . '/admin');
define("SHEETS2TABLE_ADMIN_URL", SHEETS2TABLE_PLUGIN_BASE_URL . '/admin');
define("SHEETS2TABLE_LIBRARY_DIR", SHEETS2TABLE_PLUGIN_BASE_DIR . '/library');
define("SHEETS2TABLE_LIBRARY_URL", SHEETS2TABLE_PLUGIN_BASE_URL . '/library');
define("SHEETS2TABLE_CORE_DIR", SHEETS2TABLE_PLUGIN_BASE_DIR . '/core');
define("SHEETS2TABLE_CORE_URL", SHEETS2TABLE_PLUGIN_BASE_URL . '/core');
define("SHEETS2TABLE_RESOURCES_DIR", SHEETS2TABLE_PLUGIN_BASE_DIR . '/resources');
define("SHEETS2TABLE_RESOURCES_URL", SHEETS2TABLE_PLUGIN_BASE_URL . '/resources');

// Include the working files. All operations needs these files
require_once SHEETS2TABLE_LIBRARY_DIR . '/sheets2table-functions.php';
require_once SHEETS2TABLE_LIBRARY_DIR . '/class-sheets2table-resources.php';
require_once SHEETS2TABLE_LIBRARY_DIR . '/class-sheets2table-csv.php';
require_once SHEETS2TABLE_CORE_DIR . '/class-sheets2table-tables.php';
require_once SHEETS2TABLE_CORE_DIR . '/sheets2table-shortcodes.php';

// Misc
define("PHP_REQUIRED_VERSION", "5.3.0");

# -- Admin hooks / handles -- #
// Load the admin class
require_once SHEETS2TABLE_ADMIN_DIR . '/class-sheets2table-admin.php';

# -- Plug page custom menu items --#
// Add settings link on plugin page
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'sheets2table_settings_link' );
 
 /**
  * Handles the requests to add a custom links to the plugin page 
  * 
  * @since 0.4.0
  *
  * @param $array $links plugin links
  * @param string $admin_page  Optional. optional link to the admin page
  * 
  * @return $array plugin link
  */
function sheets2table_settings_link($links, $admin_page ="") { 
  $settings_link = '<a href="options-general.php?page=sheets2table-admin.php">Settings</a>'; 
  array_push($links, $settings_link); 
  return $links; 
}

?>
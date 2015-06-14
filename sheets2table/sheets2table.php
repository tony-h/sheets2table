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

// Version check
// From: https://make.wordpress.org/plugins/2015/06/05/policy-on-php-versions/
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', 
		create_function(
			'', 
			"echo '<div class=\"error\"><p>" . 
			__('Sheets2Table requires PHP 5.3 to function properly. Please upgrade PHP or deactivate Sheet2sTable.', 'sheets2table') .
			"</p></div>';" ) 
		);
	return;
} else {
	include 'class-sheets2table.php';
}

?>
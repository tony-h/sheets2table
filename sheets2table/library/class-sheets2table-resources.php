<?php
/*
 * Contains the name of any resources such as images or icons.
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
 * This class contains a list of all resources. 
 *
 * @since 0.4.0
 *
 */
class S2T_Resources {

	public static $delete_icon = "delete_icon.svg";
	
    /**
     * Gets the URL to the delete file icon. The delete icon is from:
	 * http://publicdomainvectors.org/en/free-clipart/Red-round-error-warning-vector-icon/8103.html
	 *
	 * @since 0.4.0
	 *
     * @return string url to the delete icon
     */
	public static function get_delete_icon_url() {
	
		return $GLOBALS['Sheets2Table']->get_resources_url() . "/" . S2T_Resources::$delete_icon;
	}
}

?>
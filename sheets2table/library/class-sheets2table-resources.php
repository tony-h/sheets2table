<?php
/*
 * Contains the name of any resources such as images or icons.
 *	
 * LICENSE: The MIT License (MIT)
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    http://choosealicense.com/licenses/mit/
*/

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
	
		return SHEETS2TABLE_RESOURCES_URL . "/" . S2T_Resources::$delete_icon;
	}
}

?>
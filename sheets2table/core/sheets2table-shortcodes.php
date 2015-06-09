<?php
/*
 * Handles any shortcodes for the Sheets2Table plugin
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

#short codes
add_shortcode($GLOBALS['Sheets2Table']->get_render_table_shortcode(), 'handle_st2_render_table_shortcode');

/**
 * Handles the 'sheets2table-render-table' shortcode
 *
 * @since 0.4.0
 *
 * @param string $atts attributes of the shortcode
 * @param string $content any prexisting content to append to
 */
function handle_st2_render_table_shortcode($atts, $content=''){

	# Begin output buffering (captures any echo/print statements)
	# Allows for text to be added before and after the shortcode has executed
	ob_start();

	extract(shortcode_atts(array(
		'csv_file'	=> '',	// name of the CSV file to get the data from
		'columns'	=> '', 	// a column name or Comma-separated list of columns name
		'titles'	=> '', 	// a column title or Comma-separated list of column titles
		'options'	=> '', 	// an option or comma-separated list of options
	), $atts));

	# Extract the shortcode data from the shortcode text strings
	# Remove any additional whitespace from the shortcode values
	# This allows for entry as: val1,val2,val3 or val1, val2, val3
	$columns_array = S2T_Functions::trim_array_values(explode(",", $columns));
	$titles_array = S2T_Functions::trim_array_values(explode(",", $titles));
	$options_array = S2T_Functions::trim_array_values(explode(",", $options));

	# process the code and build the table
	$s2t_table = new S2T_Table();
	$s2t_table->build_table($csv_file, $columns_array, $titles_array, $options_array);

	# End output buffering and return the captured text 
    $output = ob_get_contents();
    ob_end_clean();
    return $output;	
}

?>
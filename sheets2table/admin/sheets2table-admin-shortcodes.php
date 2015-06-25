<?php
/*
 * Displays the shortcodes available for use and allows the user to customize a shortcode 
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

?>

<div class="wrap">
	<h2>Sheets2Table - Shortcode Configuration</h2>
	<p>Sheets2Table plugin makes use of custom shortcodes that render a table based on the data in the CSV file.<p>
	<p>Copy/paste a shortcode to your post or page to display the contents of the Google Sheet as a table.</p>
	<p>These codes provide a template or starting point to help customize the fields to display. Please note: The <i>columns</i> and <i>titles</i> should contain the same number of elements.</p>
	<h3>Shortcode Options</h3>
	<ul>
		<li><b>use-foo-table</b>: Using this option enables the table to be used with the Footable plugin. Without the Footable plugin, this option has no effect. </li>
		<li><b>get-latest-data</b>: Using this option always displays the latest tabular data from the Google Sheet. Otherwise, the last snapshot of the Google sheet is used.</li>
	</ul>
	<hr />
	<h3>Shortcodes</h3>
<?php 

	# Look for a shortcode in the GET request.
	$shortcode = S2T_Functions::get_GET_string("shortcode");

	# If processing a GET/POST request, display customize form. Otherwise display all of the codes
	if ($shortcode != "") {
		customize_shortcode($shortcode);
	} else {
		echo display_short_codes(); 
	}
?>
</div>

<?php

// --------- End of linear HTML process. Functions are below --------- //

/**
 * Displays a list of shortcodes available for use
 * @since 0.4.0
 */
function display_short_codes() {

	global $s2t_message;
	
	# Get the list of files to build shortcodes from
	$csv_files = glob($GLOBALS['Sheets2Table']->get_resources_dir() . "/*.csv");
	$count = count($csv_files);
	
	# If no files are found, write a message and exit function
	if (count($csv_files) == 0 || !$csv_files) { 
		$message = "There are no CSV files.";
		$s2t_message->print_message($message, $s2t_message->warning);		
		return;
	}
	
	# Loop through each csv file and build the default shortcodes
	foreach($csv_files as $csv) { 
	
		$s2t_csv = new S2T_CSV($csv);
		$file_name = $s2t_csv->get_file_name();
	
?>
		<a href="<?php echo $_SERVER['REQUEST_URI'] . "&shortcode=$file_name"; ?>">Customize</a>
		<i><?php echo $file_name; ?></i> code</h4>: 
<?php

		# Build a default shortcode (with everything in it)
		$columns_headers = $s2t_csv->get_headers();
		display_shortcode($file_name, $columns_headers);
		
		echo "<hr />";
	}	
}

/**
 * Displays a shortcode for a specified file
 *
 * @since 0.4.0
 *
 * @param string $file_name name of the file to build shortcode for
 * @param string $columns a list of columns to display in the shortcode 
 * @param string $options a list of options to display in the shortcode
 */
 function display_shortcode($file_name, $columns, $options = null) {
		
	global $s2t_message;
		
	if ($options == null) {
		$options = array();
	}
		
	$column_list = "";
	foreach($columns as $columnName) { 
		$column_list .= $columnName . ", ";
	}
	
	# Remove the trailing comma and space
	$column_list = trim($column_list);
	$column_list = rtrim($column_list, ',');
	
	# Convert the array to a comma delimited string
	$shortcode_options = implode(",", $options);

	# Produce a warning for empty codes
	if (count($columns) == 0) {
		$message = "Please note: This shortcode contains no data";
		$s2t_message->print_message($message, $s2t_message->warning);
	}

	# Get the shortcode value from the global
	$s2t_render_table_shortcode = $GLOBALS['Sheets2Table']->get_render_table_shortcode();
$shortcode = <<<EOD
[$s2t_render_table_shortcode csv_file="$file_name" columns="%s" titles="%s" options="%s"]
EOD;
		
	# Assemble the components for the shortcodes
	$display_all_columns = sprintf($shortcode, $column_list, $column_list, $shortcode_options);
	$s2t_message->print_message($display_all_columns, $s2t_message->shortcode);
}

/**
 * Displays a form for building a custom shortcode
 *
 * @since 0.4.0
 *
 * @param string $table_name name of the table to build shortcode for
 */
 function customize_shortcode($csv_file_name) {

 	global $s2t_message;

	$s2t_csv = new S2T_CSV($csv_file_name);

	# Get the list of columns
	$column_list = $s2t_csv->get_headers();
 
	# Get the options object for the available codes
	$shortcodeOptions = new S2T_Shortcode_Options();
	$option_list = $shortcodeOptions->get_options_array(); 
 
	build_selection_form($csv_file_name, $column_list, $option_list);
	
	if(S2T_Functions::get_POST_string('build_shortcode') == 'Y') {

		$columns = S2T_Functions::get_POST_array("column");
		$options = S2T_Functions::get_POST_array("option");
	
		display_shortcode($csv_file_name, $columns, $options);
	}
}

/**
 * Builds a form for the selection of shortcode data
 *
 * @since 0.4.0
 *
 * @param string $csv_file_name name of the file to build shortcode for
 * @param string $column_list list columns to display for selection
 * @param string $option_list list options to display for selection
 */
 function build_selection_form($csv_file_name, $column_list, $option_list) {
 ?>
		<h3>Customizing shortcode for <i><?php echo $csv_file_name; ?></i></h3>
		<form class="shortcodes" name="rebuild_tags" method="post" action="<?php echo S2T_Functions::get_server_path_request(); ?>">
			<input type="hidden" name="build_shortcode" value="Y">
			
			<div class="checkboxes">
				<b>Columns to display</b><br />
<?php insert_column_checkboxes($column_list); ?>
			</div>
			<div class="options">
				<b>Other Options</b><br />
<?php insert_option_checkboxes($option_list); ?>
			</div>
			<input type="submit" value="Generate shortcode for '<?php echo $csv_file_name; ?>'">
		</form>
<?php
}

/**
 * Prints the checkboxes for each column
 *
 * @since 0.4.0
 *
 * @param string $column_list list columns to build a checkbox for
 */
 function insert_column_checkboxes($column_list) {

	$column_post_array = S2T_Functions::get_POST_array("column");
 
	# loop through each column and build a checkbox
	foreach ($column_list as $column) { 
	
		# If column has been check from the POST, check it so the user doesn't
		# have to check the boxes again for a different set
		# Default: unchecked
		$checked = "";
		if (in_array($column, $column_post_array)) {
			$checked = "checked";
		}

		build_check_box("column[]", $column, $checked, $column);		
	} 
}

/**
 * Prints the checkboxes for each column
 *
 * @since 0.4.0
 *
 * @param string $option_list list options to build a checkbox for
 */
function insert_option_checkboxes($option_list) {

	$options_post_array = S2T_Functions::get_POST_array("option");

	#loop through option and build a checkbox
	foreach ($option_list as $option) {

		# If column has been check from the POST, check it so the user doesn't
		# have to check the boxes again for a different set
		# Default: unchecked
		$checked = "";
		if (in_array($option, $options_post_array)) {
			$checked = "checked";
		}

		build_check_box("option[]", $option, $checked, $option);
	}
}

/**
 * Prints the an input control: <label><input type="" name="" value="" checked/></label>
 *
 * @since 0.4.0
 *
 * @param string $name name of the checkbox
 * @param string $value value of the checkbox
 * @param string $checked the text "checked" to set the state
 * @param string $display_text text to display next to the control
 */
 function build_check_box($name, $value, $checked, $display_text) {
?>
		<label>
			<input type="checkbox" name="<?php echo $name; ?>" 
					value="<?php echo $value ?>" 
					<?php echo $checked ?>/>
					<?php echo $display_text ?>
		</label><br />
<?php
}
?>
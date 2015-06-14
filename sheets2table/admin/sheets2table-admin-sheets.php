<?php
/*
 * Gets a CSV file from Google Sheets 
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

# TODO: This init section needs to be redone using the WP settings code

#Initializing variables
$google_sheets_id_settings_file = "google_sheets_id.txt";
$google_sheets_id = S2T_Functions::read_from_settings_file($google_sheets_id_settings_file);

$save_as_settings_file = "save_as.txt";
$save_as = S2T_Functions::read_from_settings_file($save_as_settings_file);

# Get the values from the form POST operation, which are the most up to date values
if (S2T_Functions::get_POST_string('google_sheets_id') != "") {
	$google_sheets_id = trim(S2T_Functions::get_POST_string('google_sheets_id'));
}

# If any value is in the save_as field (even a blank), use it
if (isset($_POST['save_as'])) {
	$save_as = trim(S2T_Functions::get_POST_string('save_as'));
}

?>

<div class="wrap">
	<h2>Sheets2Table - Get Data</h2>
	<p>This sections allows a Wordpress admin to get a remote CSV file from Google Sheet.</p>
	<hr />
	
	<form name="get_data" method="post" action="<?php echo S2T_Functions::get_server_path_request(); ?>">
		<input type="hidden" name="get_sheet_submit" value="Y">
		<p class="label">Step 1: Enter the Google Spreadsheet ID</p>
		<label>https://docs.google.com/spreadsheets/d/<input type="text" name="google_sheets_id" value="<?php echo $google_sheets_id; ?>" />/export?format=csv</label>

		<p class="label">Step 2: Enter a name to save the file to</p>
		<label><input type="text" name="save_as" value="<?php echo $save_as; ?>" />.csv</label><br />By default this is the ID of the Google Sheet
		<p class="submit label">
			Step 3: Retrieve the file from the Google Sheet<br />
			<input type="submit" name="Submit" value="Save and View Data" /> 
		</p>
	</form>
</div>
<hr />

<?php

# Scan for POST values
$form_submit = S2T_Functions::get_POST_string('get_sheet_submit');

# If the form has been submitted, process the request
if($form_submit == 'Y') {
	
	# Save the value in a settings file
	S2T_Functions::write_to_settings_file($google_sheets_id_settings_file, $google_sheets_id);
	S2T_Functions::write_to_settings_file($save_as_settings_file, $save_as);

	#Get the save-as file name if the user entered one. Otherwise, use the sheet ID
	$save_as = trim(S2T_Functions::get_POST_string('save_as'));
	if ($save_as == "") {
		$save_as = $google_sheets_id;
	}

	# Remove any whitespaces and build the file name
	$save_as = S2T_Functions::whitespace_to_underscore($save_as) . ".csv";

	// Download the data and get the local file path
	get_csv_file($google_sheets_id, $save_as);
}

/**
 * 
 * Displays the instructions for the final steps
 * 
 */
function display_final_steps() {

	# Add a direct URL to the shortcodes page

	$search_string = "sheets2table-admin-sheets";
	$replace_string = "sheets2table-admin-shortcodes";
	$current_url = S2T_Functions::get_server_path_request();
	$str_pos = strpos($current_url, $search_string);

	if (strpos($current_url, $search_string) > 0) {
		$shortcode_url = str_replace($search_string, $replace_string, $current_url);
	} else {
		$shortcode_url = $current_url . "&tab=" . $replace_string;
	}
	
?>
	<hr />
	<p class="label"><b>Step 4: <a href="<?php echo $shortcode_url; ?>" >Configure a shortcode</a> and add it to any webpage or blog post.</b></p>
<?php
}

/*
 * Processes the data in the form and saves the CSV file
 *
 * @since 0.4.0
 *
 * @param string $google_sheets_id file path of google sheet to get the CSV file from
 * @return string file name of the CVS file saved from the URL. 
 *				  Relative to the current directory
 */
function get_csv_file($google_sheets_id, $save_as_file_name) {

	global $s2t_message;
	
	if ($google_sheets_id == "") {
		$s2t_message->print_message("Please set the Google Sheet ID", $s2t_message->error);
		
		return "";
	}

	# This is the path of the file once it is downloaded, or of the existing file
	$downloaded_file_path = $GLOBALS['Sheets2Table']->get_resources_dir() . "/$save_as_file_name";

	# Create a backup of the file, if one exists in case of download fail
	$s2t_csv_backup = new S2T_CSV($downloaded_file_path);
	$s2t_csv_backup->backup();
	
	# Construct the URL for the CSV file
	$csv_url = "https://docs.google.com/spreadsheets/d/" . 	
				$google_sheets_id .
				"/export?format=csv";

	$message = "Retrieving Google Sheet from <a href='$csv_url'>$csv_url</a>";
	$s2t_message->print_message($message);
	
	# Get the file contents
	$downloaded_csv_contents = @file_get_contents($csv_url);
	
	# Do not create an empty file if nothing was retrieved. An error will be generated later.
	if (!empty($downloaded_csv_contents)) {
		file_put_contents($downloaded_file_path, $downloaded_csv_contents);
	}

	# Get the CSV object of the downloaded file
	$s2t_csv = new S2T_CSV($downloaded_file_path);
	
	# Verify the file was downloaded. If not, display message and return empty string
	if (!$s2t_csv->is_valid_file()) {
	
		$message = "File not retrieved. Please verify: 1) The above URL downloads a .csv file. 
					2) The sharing settings are set to anyone with a link can view.";
		$s2t_message->print_message($message, $s2t_message->error);
		$downloaded_file_path = "";

		# Attempt to restore the file if a backup exists
		$s2t_csv_backup->restore();
		
	} else {
	
		# File retrieved, keep processing
		# Delete the backup file
		$s2t_csv_backup->delete();
		
		$message = "Saved Google Sheet to '$save_as_file_name' (" . $s2t_csv->get_file_size() . ")";
		$s2t_message->print_message($message, $s2t_message->success);
		
		# Prompt the user for the next steps
		display_final_steps();
		
		# Read and display the contents of the CSV file
		display_csv_data($s2t_csv);		
	}
	
	# Return the downloaded file name to process
	return $s2t_csv->get_file_path();
}

/* 
 * Displays the data to the screen for quick verification
 *
 * @since 0.4.0
 *
 * @param S2T_CSV $s2t_csv object containing the CSV data to display in the table 
 */
function display_csv_data($s2t_csv) {

?>
		<hr />
		<h3>Data preview</h3>
<?php

	# Display the results in a table for quick verification
	$s2t_table = new S2T_Table();
	$s2t_table->build_admin_table($s2t_csv);
}
?>
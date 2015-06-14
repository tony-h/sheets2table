<?php
/*
 * Contains file management operations that allow the user to perform basic file operations
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
		<h2>SheetsToTable File Management Tools</h2>
		<p>Perform basic file-based operations.</p>
		<hr />
<?php
	
	# Gets the GET value if the delete icon was pressed
	$delete_file_name = S2T_Functions::get_GET_string("delete-file");
	
	# Determine if the user pressed a delete button on the form. If, process it
	process_form_data($delete_file_name);
	
	# If the user pushed the delete file icon, display form to prompt for delete
	if ($delete_file_name != "") {
		display_delete_file_form($delete_file_name);
	} else {
	#otherwise, display the list of CSV files
?>		
		<div class="wrap admin-form">
			<h2>CSV File List</h2>
			<div>&nbsp;</div>
<?php display_files(); ?>
		</div>
	</div>

<?php	
	}

// --------- End of linear HTML process. Functions are below --------- //

/**
 * Displays the div of the list of files.
 *
 * @since 0.4.0
 *
 * @param string $delete_file_name file name of the CSV file to delete
 */
function process_form_data($delete_file_name) {

	# Gets the post value if a button was pressed on the delete file form
	$delete_file_form_submit = S2T_Functions::get_POST_string("delete-file-submit");

	# A button was pressed on the delete form.
	if ($delete_file_form_submit == "Y") {
	
		$file_name = S2T_Functions::get_POST_string("file-name");
		$submit_value = S2T_Functions::get_POST_string("Submit-Yes");
		
		# If the yes button, delete the file. Otherwise write a cancelled message.
		if ($submit_value == "Yes") {
			delete_csv_file($file_name);
		} else {
			global $s2t_message;
			$message = "Cancelled file delete for '$file_name'";
			$s2t_message->print_message($message, $s2t_message->information);
		}
	}
}

/**
 * Lists the CSV files
 *
 * @since 0.4.0
 */
function display_files() {

	# Get the list of files
	$csv_files = glob($GLOBALS['Sheets2Table']->get_resources_dir() . "/*.csv");

	# If no files are found, write a message and return
	if (count($csv_files) == 0 || !$csv_files) { 
		global $s2t_message;
		$message = "There are no CSV files.";
		$s2t_message->print_message($message, $s2t_message->warning);
		return;
	}
?>
		<table class="csv-files">
			<tr>
				<th class="file-name">Download</th><th class="delete">Delete</th>
			</tr>
<?php

	$delete_file_icon_url = S2T_Resources::get_delete_icon_url();
	
	foreach ($csv_files as $csv ) { 
	
		$s2t_csv = new S2T_CSV($csv);
	
		$file_name = $s2t_csv->get_file_name();
		$file_url = $s2t_csv->get_file_url();
		$file_size = $s2t_csv->get_file_size();
		$delete_url = S2T_Functions::get_server_path_request() . "&delete-file=" . $file_name;
?>         
			<tr>
				<td><a href="<?php echo $file_url;?>"><?php echo $file_name; ?></a> (<?php echo $file_size; ?>)</td>
				<td class="delete"><a href="<?php echo $delete_url; ?>" ><img height="16px" src="<?php echo $delete_file_icon_url; ?>"></a></td>
			</tr>
<?php 
	}
?> 
		</table>
<?php
}

/**
 * Displays the delete file form
 *
 * @since 0.4.0
 *
 * @param string $file_name of the CSV file to display in the delete file form
 */
function display_delete_file_form($file_name) {

	#remove the file name from URL
	$search = htmlspecialchars("&delete-file=$file_name");
	$subject = S2T_Functions::get_server_path_request();
	$action = str_replace($search, "", $subject);

?>
		<form name="delete_file_confirm" method="post" action="<?php echo $action; ?>">		
			<p>Are you sure you want to permanently delete file <code><?php echo $file_name; ?></code>?
			<input type="hidden" name="delete-file-submit" value="Y">
			<input type="hidden" name="file-name" value="<?php echo $file_name; ?>">
			<input type="submit" name="Submit-Yes" value="Yes" /> 
			<input type="submit" name="Submit-No" value="No" /> 
		</form>

<?php
}

/**
 * Deletes the CSV file from the server
 *
 * @since 0.4.0
 *
 * @param string $file_name of the CSV file to delete
 */
function delete_csv_file($file_name) {

	global $s2t_message;

	# Remove the file
	$s2t_csv = new S2T_CSV($file_name);
	$result = $s2t_csv->delete();

	#Display a message based on the result
	if ($result) {
		$message = $file_name . " successfully deleted.";
		$s2t_message->print_message($message, $s2t_message->success);
	} else {
		$message = $file_name . " failed to delete.";
		$s2t_message->print_message($message, $s2t_message->error);
	}
}

?>
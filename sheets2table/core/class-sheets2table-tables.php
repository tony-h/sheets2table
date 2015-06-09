<?php
/*
 * Builds an HTML table based on the data in the CSV file
 *	
 * LICENSE: GNU General Public License (GPL) version 3
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    https://www.gnu.org/licenses/gpl.html
*/

# Wordpress security recommendation
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once $GLOBALS['Sheets2Table']->get_library_dir() . '/class-sheets2table-shortcode-options.php';
require_once $GLOBALS['Sheets2Table']->get_library_dir() . '/class-sheets2table-csv.php';

/**
 * Builds an HTML table from the data in a CSV file
 *
 * @since 0.4.0
 *
 */
Class S2T_Table {

	/**
	 * Builds the admin table
	 *
	 * @since 0.4.0
	 *
	 * @param string $csv_file name of csv_file to get the data from
	 * @param array $columns_array array of columns to display
	 * @param array $columns_titles array of column titles to display
	 * @param array $options_array array of options for class S2T_Shortcode_Options
	 */
	public function build_table($csv_file, $columns_array, $columns_titles, $options_array) {

		$s2t_csv = new S2T_CSV($csv_file);
		$rows = $s2t_csv->convert_to_array();		

		# Get the options
		$shortcode_options = new S2T_Shortcode_Options($options_array);

		# Build the table
		# 1) Build the header
		S2T_Table::display_table_header($columns_titles, $shortcode_options);

		# 2) Build the tr and td elements in the table
		S2T_Table::display_table_row($rows, $columns_array);

		# 3) close out the table
		S2T_Table::display_table_footer();	
	}	
	
	/**
	 * Builds the admin table based on the CSV data
	 *
	 * @since 0.4.0
	 *
	 * @param S2T_CSV $s2t_csv object containing the CSV data to display in the table 
	 * @param S2T_Shortcode_Options $options UI control options
	 */
	public function build_admin_table($s2t_csv, $options = null) {
		
		if ($options == null) {
			$options = new S2T_Shortcode_Options();
		}
			
		$rows = $s2t_csv->convert_to_array();

		# Build the header
		S2T_Table::display_table_header($rows[0]);

		# Build the rows
		S2T_Table::display_admin_table_rows($rows);
		
		# Close out the table
		S2T_Table::display_table_footer();
	}	
	
	/**
	 * Builds the table header
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 * @param array $headers array of column titles to display
	 * @param S2T_Shortcode_Options $options UI control options
	 */
	private function display_table_header($headers, $options = null) {

		if ($options == null) {
			$options = new S2T_Shortcode_Options();
		}
		
		# This is only for foo tables, but having the attribute won't hurt
		# data-page=true (show pages)
		# data-page=false (hide pages)
		
		$footable = "";
		
		# hides the column control if set
		if ($options->use_foo_table()) {
			$footable = "footable";
		} 

	?>
		<table class="<?php echo $footable; ?>">
			<thead>
				<tr>
	<?php foreach ($headers as $header) { ?>
					<th><?php echo $header ?></th>
	<?php } ?>
				</tr>
			</thead>
			<tbody>
	<?php
	}

	/**
	 * Displays the column control
	 *
	 * @since 0.4.0
	 * @access private
 	 *
	 * @param array $headers array of column titles to display
	 */
	private function display_column_control($headers) {
	?>
		<div class="column-controls-container">
			<form id="column-controls">
	<?php 
			for ($i = 0; $i < count($headers); $i++) { 
				$separator = " |";
				if ($i+1 == count($headers))
					$separator = "";
	?>
				 <label><input type="checkbox" name="<?php echo $i+1; ?>" checked="checked" /> <?php echo $headers[$i]; ?><span class="separator"><?php echo $separator; ?></span></label> 
	<?php 	
			}
	 ?>	
			</form>
		</div>
	<?php
	}

	/**
	 * Builds the table rows and displays only the specified columns
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 * @param array $rows a 2D array containing the values for the table  
	 * @param array $columns_array array of column titles to display
	 */
	private function display_table_row($rows, $columns_array) {

# The complete <tr> row
$tr_html_format = <<<EOD

		<tr>
			%s
		</tr>
EOD;

# The complete <td> row
$td_html_format = <<<EOD

			<td>%s</td>
EOD;

		# If not data, exit function
		if (count($rows) <= 0) {
			return;
		}


		# Build the order of the column/cell data to display and their order
		# The array contains the index of the columns to display the data for. Columns not found
		# in the $columns_array are omitted
		$column_index_array = S2T_Table::get_column_indexes_to_display($columns_array, $rows[0]);

		# Iterate through each row to build a corresponding <tr> tag
		for($i = 1; $i < count($rows); $i++) {

			$td_html = "";

			# Iterate through each column to build a corresponding <td> tag
			# The column data may be a specific subset of the total column data
			for ($j = 0; $j < count($column_index_array); $j++) { 
			
				# Get the data for the particular column index
				$td_data = $rows[$i][$column_index_array[$j]];

				 # Assemble the HTML
				 $td_html .= sprintf($td_html_format, $td_data);
			} 
			# Dump the assembled HTML to the screen
			echo sprintf($tr_html_format, $td_html);	
		}
	}

	/**
	 * Builds the table row for the admin page
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 * @param array $rows array containing the data for each row
	 */
	private function display_admin_table_rows($rows) {

# The complete <tr> row
$tr_html_format = <<<EOD

		<tr>
			%s
		</tr>
EOD;

# Template for each <td> instance
$td_html_format = <<<EOD

			<td>%s</td>
EOD;

		#Loop through each row of the CSV file
		for($i = 1; $i < count($rows); $i++) {

			# The first <td> element is the row index
			$td_html = ""; # sprintf($td_html_format, "", $i);

			#Loop through each column
			for($j = 0; $j < count($rows[$i]); $j++) {	
		
				# Each consecutive row contains the actual data
				$td_html .= sprintf($td_html_format, $rows[$i][$j]);
			}
			
			#display full row
			echo sprintf($tr_html_format, $td_html);		
		}
	}

	/**
	 * Display the table footer
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 */
	private function display_table_footer() {
	?>
			</tbody>
		</table>
	<?php
	}

	/**
	 * Builds an array of ordered indexes of which column/cell data to
	 * display and in which order.
	 *
	 * @since 0.4.0
	 * @access private
	 *
	 * @param array $columns_array array of columns to display
	 * @param array $all_headers array of column all of the columns
	 */
	private function get_column_indexes_to_display($columns_array, $all_headers) {

		$index_array = array();
		
		for($i = 0; $i < count($columns_array); $i++) {
		
			for($j = 0; $j < count($all_headers); $j++) {
		
				# If a match is found, save the index
				if ($columns_array[$i] == $all_headers[$j]) {
					$index_array[$i] = $j;
				}
			}
		}
		
		return $index_array;
	}	
}
?>
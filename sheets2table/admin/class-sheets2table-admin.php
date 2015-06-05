<?php
/*
 * The S2T_Admin class which controls the sheets2table admin code
 *	
 * LICENSE: The MIT License (MIT)
 *
 * @author     Tony Hetrick
 * @copyright  [2015] [tonyhetrick.com]
 * @license    http://choosealicense.com/licenses/mit/
*/

// Initialize the plugin
add_action( 'plugins_loaded', create_function( '', '$S2T_Admin = new S2T_Admin;' ) );

# PHP includes go here. Any HTML includes go in function plugin_options_page()
include(SHEETS2TABLE_LIBRARY_DIR . '/class-sheets2table-message.php');

/*
 * The main admin class.  
 * Based on the template code from: 
	http://theme.fm/2011/10/how-to-create-tabs-with-the-settings-api-in-wordpress-2590/
	
	To create a new tab: 
		1) Create a new key and assign it to the value of the PHP file: 
			private $abc_admin_key = 'sheets2table-admin-abc'
		2) Duplicate the function that reads: function register_abc_admin()
		3) Rename it and change data
		4) Duplicate the add_action function call in the constructor (this determines the tab order)
		5) Match the function call to the above name	
 *
 * @since 0.4.0
 *
 */
class S2T_Admin {
	
	 # Keys used for the tab data and settings
	private $plugin_label = 'Sheets2Table';
	private $plugin_url_slug = 'sheets2table-admin';
	private $sheets_admin_key = 'sheets2table-admin-sheets';
	private $shortcodes_admin_key = 'sheets2table-admin-shortcodes';
	private $file_management_admin_key = 'sheets2table-admin-file-management';
	private $debug_admin_key = 'sheets2table-admin-debug';
	private $plugin_settings_tabs = array();
	
	/*
	 * Register the actions on init
	 *
	 * @since 0.4.0
	 *
	 */
	function __construct() {
		add_action('admin_init', array(&$this, 'register_sheets_admin'));
		add_action('admin_init', array(&$this, 'register_shortcodes_admin'));
		add_action('admin_init', array(&$this, 'register_file_management_admin'));
		add_action('admin_init', array(&$this, 'register_debug_admin'));
		add_action('admin_menu', array(&$this, 'add_admin_menus'));
	}
	
	/*
	 * Registers the sheets settings and appends the key to the tabs array
	 * of the object.
	 */
	function register_sheets_admin() {
		$this->plugin_settings_tabs[$this->sheets_admin_key] = 'Sheets';
	}
	
	/*
	 * Registers the shortcodes settings and appends the key to the tabs 
	 * array of the object.
	 *
	 * @since 0.4.0
	 *
	 */
	function register_shortcodes_admin() {
		$this->plugin_settings_tabs[$this->shortcodes_admin_key] = 'Shortcodes';
	}
	
	/*
	 * Registers the file management settings and appends the key to the plugin
	 * settings tabs array.
	 *
	 * @since 0.4.0
	 *
	 */
	function register_file_management_admin() {
		$this->plugin_settings_tabs[$this->file_management_admin_key] = 'File Management';
	}
	
	/*
	 * Registers the debug settings and appends the key to the tabs 
	 * array of the object.
	 *
	 * @since 0.4.0
	 *
	 */
	function register_debug_admin() {
		$this->plugin_settings_tabs[$this->debug_admin_key] = 'Troubleshooting';
	}
	
	/*
	 * Called during admin_menu, adds an option page under Settings, rendered
	 * using the plugin_options_page method.
	 *
	 * @since 0.4.0
	 *
	 */
	function add_admin_menus() {
		add_options_page(
			$this->plugin_label, 
			$this->plugin_label, 
			'manage_options', 
			$this->plugin_url_slug, 
			array( &$this, 'plugin_options_page' ) );
	}
	
	/*
	 * Plugin Options page rendering goes here, checks
	 * for active tab and replaces key with the related
	 * settings key. Uses the plugin_options_tabs method
	 * to render the tabs.
	 *
	 * @since 0.4.0
	 *
	 */
	function plugin_options_page() {

	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->sheets_admin_key;
		?> 
		<div class="wrap">
			<?php $this->plugin_options_tabs(); ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>
			</form>
		</div>
		<?php
		
		// This loads the code for the active tab
		require_once $tab . '.php';

		#--- This is where any common non-php includes for the admin code goes ---#
		include(SHEETS2TABLE_ADMIN_DIR . '/admin-styles.css');
	}
	
	/*
	 * Renders the tabs in the plugin options page
	 *
	 * @since 0.4.0
	 *
	 */
	function plugin_options_tabs() {

		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->sheets_admin_key;

		screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_url_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		
		echo '</h2>';
	}	
};
?>
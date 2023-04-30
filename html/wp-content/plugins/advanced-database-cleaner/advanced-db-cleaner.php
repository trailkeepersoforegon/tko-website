<?php

/*
Plugin Name: Advanced Database Cleaner
Plugin URI: https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner
Description: Clean database by deleting unused data such as 'old revisions', 'old drafts', 'orphan options', etc. Optimize database and more.
Version: 3.1.2
Author: Younes JFR.
Author URI: https://www.sigmaplugin.com
Contributors: symptote
Text Domain: advanced-database-cleaner
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) return;
if ( ! is_main_site() ) return;

class ADBC_Advanced_DB_Cleaner {

	public function __construct() {

		/***************************************************************************
		* Prevent conflict between free and pro versions
		***************************************************************************/

		if ( ! function_exists( 'deactivate_plugins' ) ) include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Prevent conflict between free and pro versions
		if ( is_plugin_active( 'advanced-database-cleaner-pro/advanced-db-cleaner.php' ) &&
			 is_plugin_active( 'advanced-database-cleaner/advanced-db-cleaner.php' ) ) {

			// Deactivate the free version in silent mode to keep scheduled tasks for pro
			deactivate_plugins( 'advanced-database-cleaner/advanced-db-cleaner.php', true );
			add_action( 'admin_notices', array( $this, 'aDBc_conflict_notice_free' ) );
			return;
		}

		// Prevent conflict between new pro and old pro versions
		if ( is_plugin_active( 'advanced-database-cleaner-pro/advanced-db-cleaner.php' ) &&
			 is_plugin_active( 'advanced-db-cleaner/advanced-db-cleaner.php' ) ) {

			// Deactivate the old pro in silent mode to keep scheduled tasks for pro
			deactivate_plugins( 'advanced-db-cleaner/advanced-db-cleaner.php', true );
			add_action( 'admin_notices', array( $this, 'aDBc_conflict_notice_old_pro' ) );
			return;
		}

		/***************************************************************************
		* Add actions and filters
		***************************************************************************/

		// Load plugin
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// Load CSS and JS
		add_action( 'admin_enqueue_scripts', array( $this, 'aDBc_load_styles_and_scripts' ) );

		// Add some schedules
		add_filter( 'cron_schedules', array( $this, 'aDBc_additional_schedules' ) );

		// Add actions for scheduled events
		add_action( 'aDBc_optimize_scheduler', 'aDBc_optimize_scheduled_tables' );
		add_action( 'aDBc_clean_scheduler', 'aDBc_clean_scheduled_elements' );

		// Register activation, deactivation and uninstall hooks of the plugin
		register_activation_hook	( __FILE__, array( $this, 'aDBc_activate_plugin' ) );
		register_deactivation_hook	( __FILE__, array( $this, 'aDBc_deactivate_plugin' ) );
		register_uninstall_hook		( __FILE__, array( 'ADBC_Advanced_DB_Cleaner', 'aDBc_uninstall' ) );

		// Ignore admin notice if needed + ignore double check if needed
		add_action( 'admin_init', array( $this, 'aDBc_ignore_notice' ) );

		// Add admin notice to rate plugin
		add_action( 'admin_notices', array( $this, 'aDBc_rate_notice' ) );

	}

	// Do this on plugins loaded : load text-domain and check if we should update settings in DB
	public function plugins_loaded() {

		/***************************************************************************
		* Define common constants and variables (we switch all "\" to "/" in paths)
		***************************************************************************/

		if( ! defined( "ADBC_PLUGIN_VERSION" ) ) 			define( "ADBC_PLUGIN_VERSION", "3.1.2" );

		if( ! defined( "ADBC_PLUGIN_PLAN" ) ) 				define( "ADBC_PLUGIN_PLAN", "free" );

		if ( ! defined( "ADBC_PLUGIN_DIR_PATH" ) ) 			define( "ADBC_PLUGIN_DIR_PATH", plugins_url( '' , __FILE__ ) );

		// Main Plugin file
		if ( ! defined( "ADBC_MAIN_PLUGIN_FILE_PATH" ) )	define( "ADBC_MAIN_PLUGIN_FILE_PATH", str_replace( '\\' ,'/', __FILE__ ) );

		// ABSPATH without trailing slash
		if ( ! defined( "ADBC_ABSPATH" ) ) 					define( "ADBC_ABSPATH", rtrim( str_replace( '\\' ,'/', ABSPATH ), "/" ) );

		// WP Content directory
		if ( ! defined( "WP_CONTENT_DIR" ) ) 				define( "WP_CONTENT_DIR", ABSPATH . 'wp-content' );

		if ( ! defined( "ADBC_WP_CONTENT_DIR_PATH" ) ) 		define( "ADBC_WP_CONTENT_DIR_PATH", str_replace( '\\' ,'/', WP_CONTENT_DIR ) );

		// WP Plugin directory path & name
		if ( ! defined( "WP_PLUGIN_DIR" ) ) 				define( "WP_PLUGIN_DIR", WP_CONTENT_DIR . '/plugins' );

		if ( ! defined( "ADBC_WP_PLUGINS_DIR_PATH" ) ) 		define( "ADBC_WP_PLUGINS_DIR_PATH", str_replace( '\\' ,'/', WP_PLUGIN_DIR ) );

		// MU Must have plugin path & name
		if ( ! defined( "WPMU_PLUGIN_DIR" ) ) 				define( "WPMU_PLUGIN_DIR", WP_CONTENT_DIR . '/mu-plugins' );

		if ( ! defined( "ADBC_WPMU_PLUGIN_DIR_PATH" ) ) 	define( "ADBC_WPMU_PLUGIN_DIR_PATH", str_replace( '\\' ,'/', WPMU_PLUGIN_DIR ) );

		// WP Uploads directory & name
		$aDBc_upload_dir = wp_upload_dir();

		if ( ! defined( "ADBC_UPLOAD_DIR_PATH" ) ) 			define( "ADBC_UPLOAD_DIR_PATH", str_replace( '\\' ,'/', $aDBc_upload_dir['basedir'] ) );

		/***************************************************************************
		* Include functions and add Ajax functions
		***************************************************************************/

		include_once 'includes/functions.php';

		// Add Ajax functions used by both free and pro versions
		add_action( 'wp_ajax_aDBc_save_settings_callback', 'aDBc_save_settings_callback' );

		// In pro, include these as well
		if ( ADBC_PLUGIN_PLAN == "pro" ) {

			include_once 'includes/functions_pro.php';

			register_shutdown_function( 'aDBc_shutdown_due_to_timeout' );

			include_once 'includes/license/adbc-edd-sample-plugin.php';

			add_action( 'wp_ajax_aDBc_license_actions_callback', 'aDBc_license_actions_callback' );
			add_action( 'wp_ajax_aDBc_new_run_search_for_items', 'aDBc_new_run_search_for_items' );
			add_action( 'wp_ajax_aDBc_get_progress_bar_width', 'aDBc_get_progress_bar_width' );
			add_action( 'wp_ajax_aDBc_double_check_items', 'aDBc_double_check_items' );
			add_action( 'wp_ajax_aDBc_stop_search', 'aDBc_stop_search' );
		}

		/***************************************************************************
		* Add default settings if needed
		***************************************************************************/

		$settings = get_option( 'aDBc_settings' );

		if ( empty( $settings ) ) {

			$settings['left_menu'] 			= "1";
			$settings['menu_under_tools'] 	= "1";
			$settings['plugin_version'] 	= ADBC_PLUGIN_VERSION;
			$settings['installed_on'] 		= date( "Y/m/d" );
			update_option( 'aDBc_settings', $settings, "no" );

		}

		/**************************************************************************************************************
		*
		* In pro, define & create adbc upload folder or rename existing one by adding security code for versions < 3.1.0
		* In version >= 3.0.0 of ADBC, we are using files to store scan data instead of database
		* In version >= 3.1.0, we've added a unique security code to the end of the folder
		*
		**************************************************************************************************************/

		if ( ADBC_PLUGIN_PLAN == "pro" ) {

			$aDBc_security_folder_code = get_option( 'aDBc_security_folder_code' );

			// If the security code does not exist, generate a new one
			if ( empty( $aDBc_security_folder_code ) ) {

				$permitted_chars 			= '00112233445566778899abcdefghijklmnopqrstuvwxyz';
				$aDBc_security_folder_code 	= substr( str_shuffle( $permitted_chars ), 0, 12 );
				update_option( 'aDBc_security_folder_code', $aDBc_security_folder_code, "no" );

			}

			// Define ADBC upload folder name with the security code
			if ( ! defined( "ADBC_UPLOAD_DIR_PATH_TO_ADBC" ) ) {

				define( "ADBC_UPLOAD_DIR_PATH_TO_ADBC", ADBC_UPLOAD_DIR_PATH . '/adbc_uploads_' . $aDBc_security_folder_code );

			}

			// Test of the old folder "/adbc_uploads" exists. If so, rename it by adding new security code
			if ( file_exists( ADBC_UPLOAD_DIR_PATH . '/adbc_uploads' ) ) {

				rename( ADBC_UPLOAD_DIR_PATH . '/adbc_uploads', ADBC_UPLOAD_DIR_PATH . '/adbc_uploads_' . $aDBc_security_folder_code );

			} else {

				// Create the new folder with the security code
				if ( ! file_exists( ADBC_UPLOAD_DIR_PATH_TO_ADBC ) ) {

					aDBc_create_folder_plus_index_file( ADBC_UPLOAD_DIR_PATH_TO_ADBC );

				}
			}
		}

		// Define & increase timeout
		if ( ! defined( "ADBC_ORIGINAL_TIMEOUT" ) )	{

			define( "ADBC_ORIGINAL_TIMEOUT", ini_get( 'max_execution_time' ) );

		}

		if ( ADBC_PLUGIN_PLAN == "pro" && function_exists( 'set_time_limit' ) && ADBC_ORIGINAL_TIMEOUT < 300 ) {

			// xxx do this only before scanning
			@set_time_limit( 300 );

		}

		// Add plugin menu to Wordpress menus
		add_action('admin_menu', array($this, 'aDBc_add_admin_menu'));

		// Load text-domain
		load_plugin_textdomain('advanced-database-cleaner', false, dirname(plugin_basename(__FILE__)) . '/languages');

		// If plugin get updated, make changes to old version
		$this->aDBc_update_plugin_check();

	}

	// Show notice if conflict detected between free and pro versions
	function aDBc_conflict_notice_free() {

		echo '<div class="error"><p>';
		_e( 'The free version of Advanced DB Cleaner has been de-activated since the pro version is active.', 'advanced-database-cleaner' );
		echo "</p></div>";

	}

	// Show notice if conflict detected between old pro and new pro versions
	function aDBc_conflict_notice_old_pro() {

		echo '<div class="error"><p>';
		_e( 'The old pro of Advanced DB Cleaner has been de-activated since the new pro version is active.', 'advanced-database-cleaner' );
		echo "</p></div>";

	}

	// Add 'Database Cleaner' to Wordpress menu
	function aDBc_add_admin_menu(){

		global $aDBc_left_menu, $aDBc_tool_submenu;

		$aDBc_settings = get_option('aDBc_settings');

		$icon_svg = 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#a0a5aa" d="M896 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-384q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-1152q208 0 385 34.5t280 93.5 103 128v128q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-128q0-69 103-128t280-93.5 385-34.5z"/></svg>');

		if(!empty($aDBc_settings['left_menu']) && $aDBc_settings['left_menu'] == "1"){
			$aDBc_left_menu = add_menu_page('Advanced DB Cleaner', 'WP DB Cleaner', 'manage_options', 'advanced_db_cleaner', array($this, 'aDBc_main_page_callback'), $icon_svg, '80.01123');
		}
		if(!empty($aDBc_settings['menu_under_tools']) && $aDBc_settings['menu_under_tools'] == "1"){
			$aDBc_tool_submenu = add_submenu_page('tools.php', 'Advanced DB Cleaner', 'WP DB Cleaner', 'manage_options', 'advanced_db_cleaner', array($this, 'aDBc_main_page_callback'));
		}
		// In case the settings has been deleted by accident, create left menu
		if(empty($aDBc_settings['left_menu']) && empty($aDBc_settings['menu_under_tools'])){
			$aDBc_left_menu = add_menu_page('Advanced DB Cleaner', 'WP DB Cleaner', 'manage_options', 'advanced_db_cleaner', array($this, 'aDBc_main_page_callback'), $icon_svg, '80.01123');
		}
	}

	// Load CSS and JS
	function aDBc_load_styles_and_scripts($hook){

		// Enqueue our js and css in the plugin pages only
		global $aDBc_left_menu, $aDBc_tool_submenu;
		if($hook != $aDBc_left_menu && $hook != $aDBc_tool_submenu){
			return;
		}

		wp_enqueue_style('aDBc_css', 				ADBC_PLUGIN_DIR_PATH . '/css/admin.css');
		wp_enqueue_style('aDBc_sweet2_css', 		ADBC_PLUGIN_DIR_PATH . '/css/sweetalert2.min.css');

		wp_enqueue_script('aDBc_js', 				ADBC_PLUGIN_DIR_PATH . '/js/admin.js');
		wp_enqueue_script('aDBc_sweet2_js', 		ADBC_PLUGIN_DIR_PATH . '/js/sweetalert2.min.js');

		// The wp_localize_script allows us to output the ajax_url path for our script to use.
		wp_localize_script('aDBc_js', 'aDBc_ajax_obj', array(

		'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
		'images_path'			=> ADBC_PLUGIN_DIR_PATH . "/images/",
		'ajax_nonce'	 		=> wp_create_nonce('aDBc_nonce'),
		'sentence_scanning' 	=> __( 'Scanning ...', 'advanced-database-cleaner' ),
		'please_wait' 			=> __( 'Please wait ...', 'advanced-database-cleaner' ),
		'all_items' 			=> __( 'All items', 'advanced-database-cleaner' ),
		'all_items2' 			=> __( 'Scan all items', 'advanced-database-cleaner' ),
		'uncategorized' 		=> __( 'Uncategorized', 'advanced-database-cleaner' ),
		'scan_time_depends' 	=> __( 'The scan time depends on your DB size and number of files', 'advanced-database-cleaner' ),
		'scan_all_only'			=> __( 'The scan will process all uncategorized items, continue?', 'advanced-database-cleaner' ),
		'scan_all_or_u' 		=> __( 'Do you want to scan all items or only uncategorized ones?', 'advanced-database-cleaner' ),
		'unexpected_error' 		=> __( 'Unexpected error! Please refresh the page and try again!', 'advanced-database-cleaner' ),
		'select_action' 		=> __( 'Please select an action!', 'advanced-database-cleaner' ),
		'no_items_selected' 	=> __( 'No items selected!', 'advanced-database-cleaner' ),
		'clean_items_warning' 	=> __( 'You are about to clean some of your unused data. This operation is irreversible!', 'advanced-database-cleaner' ),
		'empty_tables_warning' 	=> __( 'You are about to empty some of your tables. This operation is irreversible!', 'advanced-database-cleaner' ),
		'are_you_sure' 			=> __( 'Are you sure?', 'advanced-database-cleaner' ),
		'make_db_backup_first' 	=> __( 'Don\'t forget to make a backup of your database first!', 'advanced-database-cleaner' ),
		'cancel' 				=> __( 'Cancel', 'advanced-database-cleaner' ),
		'Continue' 				=> __( 'Continue', 'advanced-database-cleaner' ),
		'active' 				=> __( 'Active', 'advanced-database-cleaner' ),
		'inactive' 				=> __( 'Inactive', 'advanced-database-cleaner' )

						));

		//wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style('wp-jquery-ui-dialog');
	}

	/******************************************************************************************
	* Add some schedules
	* Get more info here: http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
	******************************************************************************************/
	function aDBc_additional_schedules($schedules){
		// hourly, daily and twice-daily are default schedules in WP, we will add weekly and monthly
		// Add weekly schedule
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __('Once weekly', 'advanced-database-cleaner')
		);
		// Add monthly schedule
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display'  => __('Once monthly', 'advanced-database-cleaner')
		);
		return $schedules;
	}

	// Register activation of the plugin (nothing to do)
	function aDBc_activate_plugin(){}

	// Register deactivation hook
	function aDBc_deactivate_plugin($network_wide){

		// Clean these in case there are any remaining tasks from older versions < 3.0.0. Those tasks were without arguments
		wp_clear_scheduled_hook('aDBc_optimize_scheduler');
		wp_clear_scheduled_hook('aDBc_clean_scheduler');

		// Clear all schedules and update options in DB
		// Clear clean tasks
		$aDBc_clean_schedules = get_option('aDBc_clean_schedule');
		$aDBc_clean_schedules = is_array($aDBc_clean_schedules) ? $aDBc_clean_schedules : array();
		foreach($aDBc_clean_schedules as $task_name => $task_info){
			wp_clear_scheduled_hook('aDBc_clean_scheduler', array($task_name));
			// Set active to no in DB
			$task_info['active'] = 0;
			$aDBc_clean_schedules[$task_name] = $task_info;
			update_option('aDBc_clean_schedule', $aDBc_clean_schedules, "no");
		}
		// Clear optimize tasks
		$aDBc_optimize_schedules = get_option('aDBc_optimize_schedule');
		$aDBc_optimize_schedules = is_array($aDBc_optimize_schedules) ? $aDBc_optimize_schedules : array();
		foreach($aDBc_optimize_schedules as $task_name => $task_info){
			wp_clear_scheduled_hook('aDBc_optimize_scheduler', array($task_name));
			// Set active to no in DB
			$task_info['active'] = 0;
			$aDBc_optimize_schedules[$task_name] = $task_info;
			update_option('aDBc_optimize_schedule', $aDBc_optimize_schedules, "no");
		}
	}

	// Register UNINSTALL hook
	public static function aDBc_uninstall() {

		// Delete these ones in case they still in DB from older version < 3.0.0

		delete_option( 'aDBc_options_status' );
		delete_option( 'aDBc_tables_status' );
		delete_option( 'aDBc_tasks_status' );

		// Delete these options in pro

		if ( __METHOD__ == 'ADBC_Advanced_DB_Cleaner_Pro::aDBc_uninstall' ) {

			// These options should not exist after the scan. Make sure to clean them just in case

			$array_items = array( 'options', 'tables', 'tasks' );

			foreach ( $array_items as $item ) {

				delete_option( 'aDBc_temp_last_iteration_' 	. $item );
				delete_option( 'aDBc_temp_still_searching_' . $item );
				delete_option( 'aDBc_temp_last_item_line_' 	. $item );
				delete_option( 'aDBc_temp_last_file_line_' 	. $item );
				delete_option( 'aDBc_last_search_ok_' 		. $item );
				delete_option( 'aDBc_temp_total_files_' 	. $item );
				delete_option( 'aDBc_temp_maybe_scores_' 	. $item );

			}

			// Delete folder containing scan results

			$aDBc_security_code 	= get_option( 'aDBc_security_folder_code' );
			$aDBc_upload_dir 		= wp_upload_dir();
			$aDBc_upload_dir 		= str_replace( '\\' ,'/', $aDBc_upload_dir['basedir'] ) . '/adbc_uploads_' . $aDBc_security_code;

			if(file_exists($aDBc_upload_dir)){
				$dir = opendir($aDBc_upload_dir);
				while(($file = readdir($dir)) !== false){
					if ($file != '.' && $file != '..'){
						unlink($aDBc_upload_dir . "/" . $file);
					}
				}
				closedir($dir);
				rmdir($aDBc_upload_dir);
			}

			// Just in case the old folder (in versions < 3.1.0) have not been cleaned for any reason, try to clean it
			$aDBc_upload_dir = wp_upload_dir();
			$aDBc_upload_dir = str_replace('\\' ,'/', $aDBc_upload_dir['basedir']) . '/adbc_uploads';
			if(file_exists($aDBc_upload_dir)){
				$dir = opendir($aDBc_upload_dir);
				while(($file = readdir($dir)) !== false){
					if ($file != '.' && $file != '..'){
						unlink($aDBc_upload_dir . "/" . $file);
					}
				}
				closedir($dir);
				rmdir($aDBc_upload_dir);
			}

			// Delete security code option
			delete_option('aDBc_security_folder_code');
			// Delete license options
			delete_option('aDBc_edd_license_key');
			delete_option('aDBc_edd_license_status');

			// Uninstall the license key? Maybe no need for deactivating the license!
			// if(!function_exists('aDBc_edd_deactivate_license_after_uninstall')){
				// include_once 'includes/license/adbc-edd-sample-plugin.php';
			// }
			// aDBc_edd_deactivate_license_after_uninstall();

		}

		// Options below are used by both free and pro version
		// Test if both version are installed to prevent deleting options
		if ( ( file_exists( WP_PLUGIN_DIR . "/advanced-database-cleaner-pro") &&
		       file_exists( WP_PLUGIN_DIR . "/advanced-database-cleaner" )
			 ) ||
			 ( file_exists( WPMU_PLUGIN_DIR . "/advanced-database-cleaner-pro" ) &&
			   file_exists( WPMU_PLUGIN_DIR . "/advanced-database-cleaner" )
			 )
		   ) {

			// Do nothing in this case because we want to keep settings for the remaining version of the plugin

		} else {

			delete_option( 'aDBc_optimize_schedule' );
			delete_option( 'aDBc_clean_schedule' );
			delete_option( 'aDBc_settings' );

		}
	}

	// If plugin get updated, make changes to old version
	function aDBc_update_plugin_check(){
		$settings = get_option('aDBc_settings');
		if(!empty($settings)){
			if(empty($settings['plugin_version'])){

				// If settings is not empty, this means that the users had already installed ADBC plugin
				// if empty($settings['plugin_version']) => the user will update to or will install the version >= 3.0.0 for the first time, because in previous versions, this option "plugin_version" does not exist

				// Before starting the update, make all previous plugin options to autoload "no"
				$options_array = array('aDBc_optimize_schedule', 'aDBc_clean_schedule', 'aDBc_settings', 'aDBc_edd_license_key', 'aDBc_edd_license_status');
				foreach($options_array as $option_name){
					$options_value = get_option($option_name);
					if(!empty($options_value)){
						update_option($option_name, "xyz");
						update_option($option_name, $options_value, "no");
					}
				}

				// Proceed to update routine. First, add some options

				// xxx we have deleted this process in all version from 09.02.2023. Delete $settings['ignore_premium'] later
				// $settings['ignore_premium'] = "no";
				$settings['plugin_version'] = ADBC_PLUGIN_VERSION;
				$settings['installed_on'] = date("Y/m/d");
				// Delete some unused options
				unset($settings['top_main_msg']);
				unset($settings['tables_cleanup_warning']);
				update_option('aDBc_settings', $settings, "no");

				// If there are any scheduled cleanup tasks, change corresponding structure in DB to meet the new version
				if($timestamp = wp_next_scheduled('aDBc_clean_scheduler')){
					// Prepare new structure
					// Since in version <= 3.0.0 the schedule cleans all elements, we will add only those elements not new introduced ones
					$new_schedule_params['elements_to_clean'] 	= array('revision','auto-draft','trash-posts','moderated-comments','spam-comments','trash-comments','orphan-postmeta','orphan-commentmeta','orphan-relationships');
					$repeat = get_option('aDBc_clean_schedule');
					$repeat = empty($repeat) ? 'weekly' : $repeat;
					$new_schedule_params['repeat'] 				= $repeat;
					$date = date('Y-m-d', $timestamp) . "";
					$time = date('H:i', $timestamp) . "";
					$new_schedule_params['start_date'] 			= $date;
					$new_schedule_params['start_time'] 			= $time;
					$new_schedule_params['active'] 				= "1";
					$clean_schedule_setting['cleanup_schedule'] = $new_schedule_params;
					update_option('aDBc_clean_schedule', $clean_schedule_setting, "no");
					// Reschedule the event with arg name
					wp_clear_scheduled_hook('aDBc_clean_scheduler');
					wp_schedule_event($timestamp, $repeat, "aDBc_clean_scheduler", array('cleanup_schedule'));
				}else{
					// If no scheduled task, delete any remaining option in DB
					delete_option('aDBc_clean_schedule');
				}

				// If there are any optimize cleanup tasks, change corresponding structure in DB to meet the new version
				if($timestamp = wp_next_scheduled('aDBc_optimize_scheduler')){
					// Prepare new structure
					$repeat = get_option('aDBc_optimize_schedule');
					$repeat = empty($repeat) ? 'weekly' : $repeat;
					$new_schedule_params['repeat'] 				= $repeat;
					$date = date('Y-m-d', $timestamp) . "";
					$time = date('H:i', $timestamp) . "";
					$new_schedule_params['start_date'] 			= $date;
					$new_schedule_params['start_time'] 			= $time;
					// Prepare operations to perform. Since in versions < 3.0.0 there were no repair, add only optimize to keep users settings
					$operations = array('optimize');
					$new_schedule_params['operations'] 			= $operations;
					$new_schedule_params['active'] 				= "1";
					$optimize_schedule_setting['optimize_schedule'] = $new_schedule_params;
					update_option('aDBc_optimize_schedule', $optimize_schedule_setting, "no");
					// Reschedule the event with arg name
					wp_clear_scheduled_hook('aDBc_optimize_scheduler');
					wp_schedule_event($timestamp, $repeat, "aDBc_optimize_scheduler", array('optimize_schedule'));
				}else{
					// If no scheduled task, delete any remaining option in DB
					delete_option('aDBc_optimize_schedule');
				}

				// Change categorization of tables, options, tasks from database to files
				$types_array = array('tables', 'options', 'tasks');
				foreach($types_array as $type){
					$aDBc_items = get_option("aDBc_" . $type . "_status");
					$aDBc_items_status = empty($aDBc_items['items_status']) ? "" : $aDBc_items['items_status'];
					if(!empty($aDBc_items_status)){
						$item_info = explode(",", $aDBc_items_status);
						$myfile = fopen(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/" . $type . ".txt", "a");
						foreach($item_info as $item){
							$columns = explode(":", $item);
							// A customer reported that the colon is not supported as separator in searching and all options with colon are not categorized!
							// == 5 means that the option does not contain a colon. Process only these cases since items with >=5 are by default uncategorized
							if(count($columns) == 5){
								fwrite($myfile, $item."\n");
							}
						}
						fclose($myfile);
					}
					// Delete options in DB that store searching status since they are not used anymore in version >= 3.0.0
					delete_option("aDBc_" . $type . "_status");
				}

				// When activating version >= 2.0.0, delete all options and tasks created by older versions in MU sites since only the main site can clean the network now
				if(function_exists('is_multisite') && is_multisite()){
					global $wpdb;
					$blogs_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
					foreach($blogs_ids as $blog_id){
						switch_to_blog($blog_id);
						if(!is_main_site()){
							delete_option('aDBc_optimize_schedule');
							delete_option('aDBc_clean_schedule');
							wp_clear_scheduled_hook('aDBc_optimize_scheduler');
							wp_clear_scheduled_hook('aDBc_clean_scheduler');
						}
						restore_current_blog();
					}
				}

			} else {

				// If plugin_version is not empty, we update the plugin version in DB

				if ( $settings['plugin_version'] != ADBC_PLUGIN_VERSION ) {

					// We update the plugin version in DB

					$settings['plugin_version'] = ADBC_PLUGIN_VERSION;
					update_option( 'aDBc_settings', $settings, "no" );

					// In pro, from V 3.1.2, we deleted some options and files

					if ( ADBC_PLUGIN_PLAN == "pro" ) {

						$array_items = array( 'options', 'tables', 'tasks' );

						foreach ( $array_items as $item ) {

							// This option is not used anymore from V 3.2.1

							delete_option( 'aDBc_temp_still_searching_' . $item );

							// Delete any temp option after each release

							delete_option('aDBc_temp_last_iteration_' 	. $item);
							delete_option('aDBc_temp_last_item_line_' 	. $item);
							delete_option('aDBc_temp_last_file_line_' 	. $item);
							delete_option('aDBc_last_search_ok_' 		. $item);
							delete_option('aDBc_temp_total_files_' 		. $item);
							delete_option("aDBc_temp_maybe_scores_" 	. $item);

							// From V 3.2.1, some temp files should not exist after the scan

							if(file_exists(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/timeout_" . $item . ".txt"))
									unlink(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/timeout_" . $item . ".txt");

							if(file_exists(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/maybe_scores_" . $item . ".txt"))
									unlink(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/maybe_scores_" . $item . ".txt");

							if(file_exists(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/total_items_" . $item . ".txt"))
									unlink(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/total_items_" . $item . ".txt");

							if(file_exists(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/progress_items_" . $item . ".txt"))
									unlink(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/progress_items_" . $item . ".txt");

							if(file_exists(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/" . $item . "_to_categorize.txt"))
									unlink(ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/" . $item . "_to_categorize.txt");

						}

						// From v 3.1.2, the "all_files_paths.txt" should not be present after the scan
						if ( file_exists( ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/all_files_paths.txt" ) )
								unlink( ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/all_files_paths.txt" );

					}
				}
			}
		}
	}

	// Add admin notice to rate plugin
	function aDBc_rate_notice(){
		$settings = get_option('aDBc_settings');
		if(!empty($settings['installed_on'])){
			$ignore_rating = empty($settings['ignore_rating']) ? "" : $settings['ignore_rating'];
			if($ignore_rating != "yes"){
				$date1 = $settings['installed_on'];
				$date2 = date("Y/m/d");
				$diff = abs(strtotime($date2) - strtotime($date1));
				$days = floor($diff / (60*60*24));
				if($days >= 7){
					$aDBc_new_URI = $_SERVER['REQUEST_URI'];
					$aDBc_new_URI = add_query_arg('adbc-ignore-notice', '0', $aDBc_new_URI);
					echo '<div class="updated"><p>';
					printf(__('Awesome! You have been using <a href="admin.php?page=advanced_db_cleaner">Advanced DB Cleaner</a> for more than 1 week. Would you mind taking a few seconds to give it a 5-star rating on WordPress? Thank you in advance :) <a href="%2$s" target="_blank">Ok, you deserved it</a> | <a href="%1$s">I already did</a> | <a href="%1$s">No, not good enough</a>', 'advanced-database-cleaner'), esc_url( $aDBc_new_URI ),
					'https://wordpress.org/support/plugin/advanced-database-cleaner/reviews/?filter=5');
					echo "</p></div>";
				}
			}
		}
	}

	function aDBc_ignore_notice(){
		// Disable rating notice
		if(isset($_GET['adbc-ignore-notice']) && $_GET['adbc-ignore-notice'] == "0"){
			$settings = get_option('aDBc_settings');
			$settings['ignore_rating'] = "yes";
			update_option('aDBc_settings', $settings, "no");
		}

		// xxx In pro, hide double check message, this is not working for now
		if ( ADBC_PLUGIN_PLAN == "pro" ) {
			if(isset($_GET['ignore-double-check-tables'])){
				delete_option('aDBc_last_search_ok_tables');
			}
			if(isset($_GET['ignore-double-check-options'])){
				delete_option('aDBc_last_search_ok_options');
			}
			if(isset($_GET['ignore-double-check-tasks'])){
				delete_option('aDBc_last_search_ok_tasks');
			}
		}
	}

	// The admin page of the plugin

	function aDBc_main_page_callback() {

		// Require WordPress List Table Administration API
		if ( ! class_exists( 'WP_List_Table' ) )  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	?>

		<div class="wrap">

			<div class="aDBc-header-wrap">
				<img class="aDBc-header-logo" src="<?php echo ADBC_PLUGIN_DIR_PATH; ?>/images/icon-128x128.png"/>

				<div class="aDBc-float-left">

					<div class="aDBc-header-title">

						<?php

						$aDBc_pro = "";

						if ( ADBC_PLUGIN_PLAN == "pro" ) {

							$aDBc_pro = " <b>PRO</b>";

						}

						echo "Advanced DB Cleaner" . $aDBc_pro . " <span class='aDBc-font-13'>" . ADBC_PLUGIN_VERSION . "</span>";

						?>

					</div>

					<div class="aDBc-header-meta">
						<span class="aDBc-header-by">
							<span><?php _e('By', 'advanced-database-cleaner'); ?></span>
							<a class="aDBc-link" href="https://profiles.wordpress.org/symptote/" target="_blank">
								Younes JFR.
							</a>
						</span>
						|
						<span class="aDBc-header-need-help-ls">
							<span><?php _e('Need help?', 'advanced-database-cleaner'); ?></span>
							<a class="aDBc-link" href="https://sigmaplugin.com/contact" target="_blank">
								<?php _e('Contact me', 'advanced-database-cleaner'); ?>
							</a>
						</span>
						<span class="aDBc-header-need-help-ss">
							<a class="aDBc-link" href="https://sigmaplugin.com/contact" target="_blank">
								<?php _e('Support', 'advanced-database-cleaner'); ?>
							</a>
						</span>
					</div>
				</div>

				<div class="aDBc-header-support">
					<a class="aDBc-link" href="https://sigmaplugin.com/contact" target="_blank">
						<img class="aDBc-header-help-svg" src="<?php echo ADBC_PLUGIN_DIR_PATH; ?>/images/help.svg"/>
						<br/>
						<?php _e('Support', 'advanced-database-cleaner'); ?>
					</a>
				</div>
			</div>

			<h2></h2>

			<?php

			if ( ADBC_PLUGIN_PLAN == "pro") {

				$visibility_style = aDBc_edd_is_license_activated() ? "display:none" : "";

				echo '<div class="aDBc-please-activate-msg notice is-dismissible" style="' . $visibility_style .'"><p>';
				echo __( 'Please activate your license key to get lifetime automatic updates and support.', 'advanced-database-cleaner' );
				echo ' <a href="?page=advanced_db_cleaner&aDBc_tab=license">' . __( 'Activate now', 'advanced-database-cleaner' ) . "</a>";
				echo '</p></div>';

			}

			// YY. Notice to users who will lose their pro after upgrading to 3.0.0 from an old version
			// YY. This process has been deleted, no need for it anymore

			$main_content_style = "";

			if ( ADBC_PLUGIN_PLAN == "free" ) {

				$main_content_style = "aDBc-main-content";

			}

			?>

			<div class="<?php echo $main_content_style; ?>">

				<div class="aDBc-tab-box">

					<?php

					$aDBc_tabs = array( 'general'  	=> __( 'General clean-up', 'advanced-database-cleaner' ),
									    'tables'   	=> __( 'Tables', 'advanced-database-cleaner' ),
									    'options'  	=> __( 'Options', 'advanced-database-cleaner' ),
									    'cron'  	=> __( 'Cron jobs', 'advanced-database-cleaner' ),
									    'overview' 	=> __( 'Overview & settings', 'advanced-database-cleaner' ),
									    'premium'  	=> __( 'Premium', 'advanced-database-cleaner' ),
									    'license' 	=> __( 'License', 'advanced-database-cleaner' )
									  );

					// Hide premium tab in pro + In free, test if the user chose to hide the premium tab
					$aDBc_settings = get_option('aDBc_settings');
					if ( ADBC_PLUGIN_PLAN == "pro" ||
					    ( ADBC_PLUGIN_PLAN == "free" && ! empty( $aDBc_settings['hide_premium_tab'] ) && $aDBc_settings['hide_premium_tab'] == "1" )
					   ) {

						unset( $aDBc_tabs['premium'] );

					}

					// Hide license in free
					if ( ADBC_PLUGIN_PLAN == "free" ) {

						unset( $aDBc_tabs['license'] );

					}

					$aDBc_dashicons_css = array('general' 	=> 'dashicons-format-aside dashicons',
											    'tables'   	=> 'dashicons-grid-view dashicons',
											    'options'  	=> 'dashicons-forms dashicons',
											    'cron'  	=> 'dashicons-backup dashicons',
											    'overview' 	=> 'dashicons-admin-settings dashicons',
											    'premium'  	=> 'dashicons-awards dashicons',
											    'license'  	=> 'dashicons-admin-network dashicons'
											   );

					$current_tab = isset( $_GET['aDBc_tab'] ) ? $_GET['aDBc_tab'] : 'general';

					echo '<ul class="nav-tab-wrapper">';
					foreach($aDBc_tabs as $tab => $name){
						$class = ($tab == $current_tab) ? ' nav-tab-active' : '';
						$link = "?page=advanced_db_cleaner&aDBc_tab=$tab";
						if($tab == "tables" || $tab == "options" || $tab == "cron"){
							$link .= '&aDBc_cat=all';
						}
						echo "<li><a class='nav-tab$class' href='$link'><span class='$aDBc_dashicons_css[$tab]'></span> $name</a></li>";
					}
					echo '</ul>';

					echo '<div class="aDBc-tab-box-div">';

					switch ( $current_tab ) {

						case 'general' :

							include_once 'includes/clean_db.php';
							break;

						case 'tables' :

							include_once 'includes/optimize_db.php';
							break;

						case 'options' :

							include_once 'includes/class_clean_options.php';
							break;

						case 'cron' :

							include_once 'includes/class_clean_cron.php';
							break;

						case 'overview' :

							include_once 'includes/overview_settings.php';
							break;

						case 'premium' :

							include_once 'includes/premium_page.php';
							break;

						case 'license' :
							aDBc_edd_license_page();
							break;
					}

					echo '</div>';

					?>
				</div>

				<?php

				if ( ADBC_PLUGIN_PLAN == "free" ) {

					include_once 'includes/sidebar.php';

				}

				?>

			</div>
		</div>

	<?php

	}
}

// Get instance
new ADBC_Advanced_DB_Cleaner();

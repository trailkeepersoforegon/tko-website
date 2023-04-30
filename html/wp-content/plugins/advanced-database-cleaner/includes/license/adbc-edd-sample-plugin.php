<?php

/**
* For further details please visit:
* http://docs.easydigitaldownloads.com/article/383-automatic-upgrades-for-wordpress-plugins
*/


define( 'ADBC_EDD_STORE_URL', 'https://sigmaplugin.com' );

define( 'ADBC_EDD_ITEM_ID', 10 );

define( 'ADBC_EDD_ITEM_NAME', 'WordPress Advanced Database Cleaner' );

if ( ! class_exists( 'ADBC_EDD_SL_Plugin_Updater' ) ) {

	// load our custom updater
	include dirname( __FILE__ ) . '/ADBC_EDD_SL_Plugin_Updater.php';

}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
function aDBc_edd_sl_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;

	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'aDBc_edd_license_key' ) );

	// setup the updater
	$edd_updater = new ADBC_EDD_SL_Plugin_Updater( ADBC_EDD_STORE_URL, ADBC_MAIN_PLUGIN_FILE_PATH, array(
			'version' => ADBC_PLUGIN_VERSION,
			'license' => $license_key,
			'item_id' => ADBC_EDD_ITEM_ID,
			'author'  => 'Younes JFR.',
			'beta'    => false,
		)
	);

}
add_action( 'init', 'aDBc_edd_sl_plugin_updater' );

/**
 * License page
 *
 * @return void
 */
function aDBc_edd_license_page() {

	$license = get_option('aDBc_edd_license_key');
	$status  = get_option('aDBc_edd_license_status');

	if ( $status !== false && $status == 'valid' ) {

		$license_key_hidden 	= substr( $license, 0, 4 ) . "************************" . substr( $license, -4 );
		$license_status 	  	= __( 'Active', 'advanced-database-cleaner' );
		$color 	  				= "color:green";
		$activate_btn_style     = "display:none";
		$deactivate_btn_style   = "";
		$input_disabled			= " disabled";

	} else {

		$license_key_hidden 	= "";
		$license_status 		= __( 'Inactive', 'advanced-database-cleaner' );
		$color 	  				= "color:red";
		$activate_btn_style     = "";
		$deactivate_btn_style   = "display:none";
		$input_disabled			= "";

	}
?>

	<div class="aDBc-content-max-width aDBc-padding-20">

		<div class="aDBc-license-container">

			<div class="aDBc-status-box">

				<div class="aDBc-div-status">

					<span class="aDBc-license-status-label"><?php _e( 'Status:', 'advanced-database-cleaner' ); ?></span>

					<span id="aDBc_license_status" style="<?php echo $color; ?>" class="aDBc-license-status">

						<?php echo $license_status ?>

					</span>

				</div>

				<div id="aDBc_check_license_btn" style="<?php echo $deactivate_btn_style; ?>" class="aDBc-check-license-btn">

					<a>

						<span class="dashicons dashicons-update-alt"></span>

						<?php _e( 'Check status', 'advanced-database-cleaner' ); ?>

					</a>

				</div>

				<div class="aDBc-license-account">

					<a href="https://sigmaplugin.com/login?utm_source=license_tab&utm_medium=adbc_plugin&utm_campaign=plugins" target="_blank">

						<span class="dashicons dashicons-admin-users aDBc-license-icon"></span>

						<?php _e( 'My account', 'advanced-database-cleaner' ); ?>

					</a>

				</div>

			</div>

			<div class="aDBc-license-box">

				<input id="aDBc_license_key_input" class="aDBc-license-key-input" placeholder="<?php _e( 'License key', 'advanced-database-cleaner' ); ?>" type="text" value="<?php echo $license_key_hidden; ?>" <?php echo $input_disabled; ?>/>

				<div id="aDBc_activate_license_btn" style="<?php echo $activate_btn_style; ?>" class="aDBc-license-btn">

					<span class="dashicons dashicons-admin-links aDBc-license-icon"></span>

					<?php _e( 'Activate license', 'advanced-database-cleaner' ); ?>

				</div>

				<div id="aDBc_deactivate_license_btn" style="<?php echo $deactivate_btn_style; ?>" class="aDBc-license-btn">

					<span class="dashicons dashicons-editor-unlink aDBc-license-icon"></span>

					<?php _e( 'Deactivate license', 'advanced-database-cleaner' ); ?>

				</div>

			</div>

		</div>

	</div>

<?php

}

/**
 * Activate/deactivate/check the license key.
 *
 * @return void
 */
function aDBc_license_actions_callback() {

	// Check nonce and user capabilities
	if ( false === check_ajax_referer( 'aDBc_nonce', 'security', false ) || ! current_user_can( 'administrator' ) )

		wp_send_json_error( __( 'Security check failed!', 'advanced-database-cleaner' ) );

	// Get button action
	$aDBc_edd_action = sanitize_text_field( $_REQUEST['aDBc_edd_action'] );

	switch ( $aDBc_edd_action ) {

		case 'aDBc_activate_license_btn':

			$license 	= trim( sanitize_text_field( $_REQUEST['license_key'] ) );
			$edd_action = "activate_license";
			break;

		case 'aDBc_deactivate_license_btn':

			$license 	= trim( get_option( 'aDBc_edd_license_key' ) );
			$edd_action = "deactivate_license";
			break;

		case 'aDBc_check_license_btn':

			$license 	= trim( get_option( 'aDBc_edd_license_key' ) );
			$edd_action = "check_license";
			break;

		default:

			wp_send_json_error( __( 'Cannot proceed!', 'advanced-database-cleaner' ) );
			break;
	}

	if ( false === $license || empty( $license ) )

		wp_send_json_error( __( 'Empty license field!', 'advanced-database-cleaner' ) );

	// Data to send in our API request
	$api_params = array(
		'edd_action'  => $edd_action,
		'license'     => $license,
		'item_id'     => ADBC_EDD_ITEM_ID,
		'item_name'   => rawurlencode( ADBC_EDD_ITEM_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);

	// Call the custom API
	$response = wp_remote_post(
		ADBC_EDD_STORE_URL,
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {

			wp_send_json_error( $response->get_error_message() );

		} else {

			wp_send_json_error( __( 'An error occurred, please try again.', 'advanced-database-cleaner' ) );

		}
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $edd_action == "activate_license" ) {

		if ( false === $license_data->success ) {

			switch ( $license_data->error ) {

				case 'expired':
					$message = sprintf(
						/* translators: the license key expiration date */
						__( 'Your license key expired on %s.', 'advanced-database-cleaner' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;

				case 'disabled':
				case 'revoked':
					$message = __( 'Your license key has been disabled.', 'advanced-database-cleaner' );
					break;

				case 'missing':
					$message = __( 'Invalid license.', 'advanced-database-cleaner' );
					break;

				case 'invalid':
				case 'site_inactive':
					$message = __( 'Your license is not active for this URL.', 'advanced-database-cleaner' );
					break;

				case 'item_name_mismatch':
					/* translators: the plugin name */
					$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'advanced-database-cleaner' ), ADBC_EDD_ITEM_NAME );
					break;

				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.', 'advanced-database-cleaner' );
					break;

				default:
					$message = __( 'An error has occurred, please try again.', 'advanced-database-cleaner' );
					break;
			}

			wp_send_json_error( sanitize_text_field( $message ) );

		}

		// $license_data->license will be either "valid" or "invalid"
		if ( 'valid' === $license_data->license ) {

			update_option( 'aDBc_edd_license_key', $license, 'no' );
			update_option( 'aDBc_edd_license_status', $license_data->license, 'no' );
			wp_send_json_success( __( 'Activated!', 'advanced-database-cleaner' ) );

		} else {

			wp_send_json_error( __( 'License cannot be activated.', 'advanced-database-cleaner' ) );

		}

	} else if ( $edd_action == "check_license" ) {

		// $license_data->license will be either "valid" or "invalid"
		if ( 'valid' === $license_data->license ) {

			wp_send_json_success( __( 'Your license is valid.', 'advanced-database-cleaner' ) );

		} else {

			wp_send_json_error( __( 'Your license is no longer valid.', 'advanced-database-cleaner' ) );

		}

	} else if ( $edd_action == "deactivate_license" ) {

		// $license_data->license will be either "deactivated" or "failed"
		if ( 'deactivated' === $license_data->license ) {

			delete_option( 'aDBc_edd_license_key' );
			delete_option( 'aDBc_edd_license_status' );
			wp_send_json_success( __( 'Deactivated!', 'advanced-database-cleaner' ) );

		} else {

			wp_send_json_error( __( 'License cannot be deactivated, please try again.', 'advanced-database-cleaner' ) );

		}
	}

	// If we are here, maybe un anknonw error occured
	wp_send_json_error( __( 'Unknown error occurred, please try again.', 'advanced-database-cleaner' ) );

}

/**
 * Checks if a license has been activated
 *
 * @return bool true if activated, false if not
 */
function aDBc_edd_is_license_activated() {

	$license_status = trim( get_option( 'aDBc_edd_license_status') );

	if ( $license_status == 'valid' ) {

		return true;

	} else {

		return false;

	}
}

/**
 * Deactivate a license key after uninstall. This will descrease the site count
 *
 * @return void
 */
function aDBc_edd_deactivate_license_after_uninstall() {

	$license = trim( sanitize_text_field( get_option( 'aDBc_edd_license_key' ) ) );

	// data to send in our API request
	$api_params = array(
		'edd_action'  => 'deactivate_license',
		'license' 	  => $license,
		'item_id' 	  => ADBC_EDD_ITEM_ID,
		'item_name'   => rawurlencode( ADBC_EDD_ITEM_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);

	// Call the custom API.
	$response = wp_remote_post(
		ADBC_EDD_STORE_URL,
		array(
			'timeout' => 15,
			'sslverify' => false,
			'body' => $api_params
		)
	);
}

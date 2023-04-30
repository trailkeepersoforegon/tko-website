<?php

// style et code ok

class ADBC_SCHEDULE_OPTIMIZE {

	private $aDBc_message 		= "";
	private $aDBc_class_message = "updated";

    /**
     * Constructor
     */
    function __construct() {

		$this->aDBc_prepare_elements_to_clean();
		$this->aDBc_print_page_content();
    }

	/** Prepare elements to display */
	function aDBc_prepare_elements_to_clean() {

		// Test if user wants to save the scheduled task
		if ( isset( $_POST['aDBc_schedule_name'] ) ) {

			//Quick nonce security check!
			if ( ! check_admin_referer( 'add_optimize_schedule_nonce', 'add_optimize_schedule_nonce' ) )
				return; //get out if we didn't click the save_schedule button

			$trim_schedule_name = trim( $_POST['aDBc_schedule_name'] );

			if ( ! empty( $trim_schedule_name ) ) {

				if ( preg_match( '/^[a-zA-Z0-9_]+$/', $_POST['aDBc_schedule_name'] ) ) {

					// Test if the name is used by other schedules.
					$clean_schedule_setting = get_option( 'aDBc_clean_schedule' );
					$clean_schedule_setting = is_array( $clean_schedule_setting ) ? $clean_schedule_setting : array();

					$optimize_schedule_setting = get_option( 'aDBc_optimize_schedule' );
					$optimize_schedule_setting = is_array( $optimize_schedule_setting ) ? $optimize_schedule_setting : array();

					if ( ! array_key_exists( $_POST['aDBc_schedule_name'], $clean_schedule_setting ) &&
						 ! array_key_exists( $_POST['aDBc_schedule_name'], $optimize_schedule_setting ) ) {

						if ( ! empty( $_POST['aDBc_date'] ) ) {

							if ( ! empty( $_POST['aDBc_time'] ) ) {

								if ( ! empty( $_POST['aDBc_operation1'] ) || ! empty( $_POST['aDBc_operation2'] ) ) {

									// We will create the new schedule
									$new_schedule_params['repeat'] 		= sanitize_html_class( $_POST['aDBc_schedule_repeat'] );
									$new_schedule_params['start_date'] 	= preg_replace( "/[^0-9-]/", '', $_POST['aDBc_date'] );
									$new_schedule_params['start_time'] 	= preg_replace( "/[^0-9:]/", '', $_POST['aDBc_time'] );

									// Prepare operations to perform
									$operations = array();

									if ( ! empty( $_POST['aDBc_operation1'] ) )

										array_push( $operations, sanitize_html_class( $_POST['aDBc_operation1'] ) );

									if ( ! empty( $_POST['aDBc_operation2'] ) )

										array_push( $operations, sanitize_html_class( $_POST['aDBc_operation2'] ) );

									$new_schedule_params['operations'] 	= $operations;
									$new_schedule_params['active'] 		= sanitize_html_class( $_POST['aDBc_status'] );

									$optimize_schedule_setting[$_POST['aDBc_schedule_name']] = $new_schedule_params;

									update_option( 'aDBc_optimize_schedule', $optimize_schedule_setting, "no" );

									list( $year, $month, $day ) = explode( '-', preg_replace( "/[^0-9-]/", '', $_POST['aDBc_date'] ) );
									list( $hours, $minutes ) 	= explode( ':', preg_replace( "/[^0-9:]/", '', $_POST['aDBc_time'] ) );

									$seconds = "0";
									$timestamp =  mktime( $hours, $minutes, $seconds, $month, $day, $year );

									if ( $_POST['aDBc_status'] == "1" ) {

										if ( $_POST['aDBc_schedule_repeat'] == "once" ) {
											wp_schedule_single_event( $timestamp, "aDBc_optimize_scheduler", array( $_POST['aDBc_schedule_name'] ) );
										} else {
											wp_schedule_event( $timestamp, sanitize_html_class( $_POST['aDBc_schedule_repeat'] ), "aDBc_optimize_scheduler", array( $_POST['aDBc_schedule_name'] ) );
										}

										$this->aDBc_message = __( 'The clean-up schedule saved successfully!', 'advanced-database-cleaner' );

									} else {
										$this->aDBc_message = __( 'The clean-up schedule saved successfully but it is inactive!', 'advanced-database-cleaner' );
									}
								} else {
									$this->aDBc_class_message = "error";
									$this->aDBc_message = __( 'Please choose at least one operation to perform!', 'advanced-database-cleaner' );
								}
							} else {
								$this->aDBc_class_message = "error";
								$this->aDBc_message = __( 'Please specify a valide time!', 'advanced-database-cleaner' );
							}
						} else {
							$this->aDBc_class_message = "error";
							$this->aDBc_message = __( 'Please specify a valide date!', 'advanced-database-cleaner' );
						}
					} else {
						$this->aDBc_class_message = "error";
						$this->aDBc_message = __( 'The name you have specified is already used by another schedule! Please change it!', 'advanced-database-cleaner' );
					}
				} else {
					$this->aDBc_class_message = "error";
					$this->aDBc_message = __( 'Please change the name! Only letters, numbers and underscores are allowed!', 'advanced-database-cleaner' );
				}
			} else {
				$this->aDBc_class_message = "error";
				$this->aDBc_message = __( 'Please give a name to your schedule!', 'advanced-database-cleaner' );
			}
		}
	}

	/** Print the page content */
	function aDBc_print_page_content() {

		// Print a message if any
		if ( $this->aDBc_message != "" ) {

			echo '<div id="aDBc_message" class="' . $this->aDBc_class_message . ' notice is-dismissible"><p>' . $this->aDBc_message . '</p></div>';

		}
		?>

		<div style="max-width:700px">

			<div class="aDBc-float-left aDBc-margin-t-10">

				<a href="?page=advanced_db_cleaner&aDBc_tab=tables&aDBc_cat=all" style="text-decoration:none">

					<span class="dashicons dashicons-controls-back aDBc-back-dashicon"></span>

					<span style="vertical-align:middle"><?php echo __( 'Return', 'advanced-database-cleaner' ); ?></span>

				</a>

			</div>

			<div class="aDBc-schedule-title">

				<span class="dashicons dashicons-plus aDBc-schedule-dashicon"></span>

				<?php echo __( 'Add optimize schedule', 'advanced-database-cleaner' ); ?>

			</div>

			<div class="aDBc-clear-both"></div>

			<form id="aDBc_form" action="" method="post">

				<!-- Print box info for tables that will be optimized -->
				<div class="aDBc-schedule-table-elements aDBc-schedule-tables-box-info">
					<div style="padding:40px 20px">
						<?php echo __( 'By default, all your database tables will be optimized and/or repaired (if needed) according to your schedule settings', 'advanced-database-cleaner' ); ?>
					</div>
				</div>

				<div class="aDBc-right-box">

					<div class="aDBc-right-box-content">

						<div style="text-align:center">
							<img width="60px" src="<?php echo ADBC_PLUGIN_DIR_PATH . '/images/alarm-clock.svg' ?>"/>
						</div>

						<div id="add_schedule" class="aDBc-schedule-info-container">

							<div class="aDBc-margin-t-10"></div>

							<div><?php _e( 'Schedule name', 'advanced-database-cleaner' ); ?></div>

							<input name="aDBc_schedule_name" class="aDBc-schedule-input-field" type="text" value="<?php echo isset( $_POST['aDBc_schedule_name'] ) ? esc_attr( $_POST['aDBc_schedule_name'] ) : "" ?>" maxlength="25">

							<div><?php _e( 'Frequency of execution', 'advanced-database-cleaner' ); ?></div>

							<select name="aDBc_schedule_repeat" class="aDBc-schedule-input-field">

								<?php
								$schedules_repeat = array( 'once' 		=> __( 'Once', 'advanced-database-cleaner' ),
														   'hourly' 	=> __( 'Hourly', 'advanced-database-cleaner' ),
														   'twicedaily' => __( 'Twice a day', 'advanced-database-cleaner' ),
														   'daily' 		=> __( 'Daily', 'advanced-database-cleaner' ),
														   'weekly' 	=> __( 'Weekly', 'advanced-database-cleaner' ),
														   'monthly' 	=> __( 'Monthly', 'advanced-database-cleaner' )
														  );

								foreach ( $schedules_repeat as $code_repeat => $name_repeat ) {

									if ( isset( $_POST['aDBc_schedule_repeat'] ) && $_POST['aDBc_schedule_repeat'] == $code_repeat ) {
										echo "<option value='$code_repeat' selected='selected'>$name_repeat</option>";
									} else {
										echo "<option value='$code_repeat'>$name_repeat</option>";
									}

								}
								?>

							</select>

							<div><?php _e( 'Start date', 'advanced-database-cleaner' ); ?></div>

							<input name="aDBc_date" class="aDBc-schedule-input-field" type="date" value="<?php echo isset( $_POST['aDBc_date'] ) ? esc_attr( $_POST['aDBc_date'] ) : date( "Y-m-d" ); ?>" min="<?php echo date( "Y-m-d" ); ?>">

							<div><?php _e( 'Start time (GMT)', 'advanced-database-cleaner' ); ?></div>

							<input name="aDBc_time" class="aDBc-schedule-input-field" type="time" value="<?php echo isset( $_POST['aDBc_time'] ) ? esc_attr( $_POST['aDBc_time'] ) : date( "H:i", time() ); ?>">

							<div><?php _e( 'Perform operations', 'advanced-database-cleaner' ); ?></div>

							<div class="aDBc-schedule-radio-container">

								<?php

								$optimize_checked 	= ( isset( $_POST['aDBc_operation1'] ) && $_POST['aDBc_operation1'] == "optimize" ) ? 'checked' : '';
								$repair_checked 	= ( isset( $_POST['aDBc_operation2'] ) && $_POST['aDBc_operation2'] == "repair" ) ? 'checked' : '';

								?>

								<input name="aDBc_operation1" type="checkbox" value="optimize" <?php echo $optimize_checked; ?>>

								<span style="margin-right:20px"><?php _e( 'Optimize', 'advanced-database-cleaner' ); ?></span>

								<input name="aDBc_operation2" type="checkbox" value="repair" <?php echo $repair_checked; ?>>

								<?php _e( 'Repair', 'advanced-database-cleaner' ); ?>

							</div>

							<div><?php _e( 'Schedule status', 'advanced-database-cleaner' ); ?></div>

							<div class="aDBc-schedule-radio-container">

								<input name="aDBc_status" type="radio" value="1" checked>

								<span style="margin-right:20px"><?php _e( 'Active', 'advanced-database-cleaner' ); ?></span>

								<input name="aDBc_status" type="radio" value="0" <?php echo ( isset( $_POST['aDBc_status'] ) && $_POST['aDBc_status'] == "0" ) ? 'checked' : ''; ?>>

								<?php _e( 'Inactive', 'advanced-database-cleaner' ); ?>

							</div>

							<div class="aDBc-schedule-save-btn-div">

								<input class="button-primary" type="submit"  value="<?php _e( 'Save the schedule', 'advanced-database-cleaner' ); ?>" style="width:100%"/>

							</div>

						</div>
					</div>
				</div>

				<?php wp_nonce_field( 'add_optimize_schedule_nonce', 'add_optimize_schedule_nonce' ); ?>

			</form>
			<div class="aDBc-clear-both"></div>
		</div>

	<?php
	}
}

?>
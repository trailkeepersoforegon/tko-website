<?php

// style et code ok

class EDIT_SCHEDULE_CLEANUP extends WP_List_Table {

	private $aDBc_message 					= "";
	private $aDBc_class_message 			= "updated";
	private $aDBc_elements_to_display 		= array();
	private $aDBc_total_elements_to_clean 	= 0;

    /**
     * Constructor
     */
    function __construct() {

        parent::__construct( array(

            'singular'  => __( 'Element', 'advanced-database-cleaner' ),
            'plural'    => __( 'Elements', 'advanced-database-cleaner' ),
            'ajax'      => false

		));

		$this->aDBc_prepare_elements_to_clean();
		$this->aDBc_print_page_content();
    }

	/** Prepare elements to display */
	function aDBc_prepare_elements_to_clean() {

		// Test if user wants to save the edited scheduled task
		if ( isset( $_POST['aDBc_schedule_name'] ) ) {

			//Quick nonce security check!
			if ( ! check_admin_referer( 'edit_cleanup_schedule_nonce', 'edit_cleanup_schedule_nonce' ) )
				return; //get out if we didn't click the save_schedule button

			if ( ! empty( $_POST['aDBc_elements_to_process'] ) ) {

				$trim_schedule_name = trim( $_POST['aDBc_schedule_name'] );

				if ( ! empty( $trim_schedule_name ) ) {

					if ( preg_match( '/^[a-zA-Z0-9_]+$/', $_POST['aDBc_schedule_name'] ) ) {

						// Test if the name is used by other schedules.
						$clean_schedule_setting = get_option( 'aDBc_clean_schedule' );
						$clean_schedule_setting = is_array( $clean_schedule_setting ) ? $clean_schedule_setting : array();

						$optimize_schedule_setting = get_option( 'aDBc_optimize_schedule' );
						$optimize_schedule_setting = is_array( $optimize_schedule_setting ) ? $optimize_schedule_setting : array();

						if ( $_POST['aDBc_schedule_name'] == $_GET['hook_name'] ||
						    ( $_POST['aDBc_schedule_name'] != $_GET['hook_name'] &&
						    ! array_key_exists( $_POST['aDBc_schedule_name'], $clean_schedule_setting ) &&
						    ! array_key_exists( $_POST['aDBc_schedule_name'], $optimize_schedule_setting ) )
						   ) {

							if ( ! empty( $_POST['aDBc_date'] ) ) {

								if ( ! empty( $_POST['aDBc_time'] ) ) {

									// Delete the old schedule and replace it with the new

									// We will create the new schedule
									$sanitized_elements_to_process = array();

									foreach ( $_POST['aDBc_elements_to_process'] as $element ) {
										array_push( $sanitized_elements_to_process, sanitize_html_class( $element ) );
									}

									$new_schedule_params['elements_to_clean'] 	= $sanitized_elements_to_process;
									$new_schedule_params['repeat'] 				= sanitize_html_class( $_POST['aDBc_schedule_repeat'] );
									$new_schedule_params['start_date'] 			= preg_replace( "/[^0-9-]/", '', $_POST['aDBc_date'] );
									$new_schedule_params['start_time'] 			= preg_replace( "/[^0-9:]/", '', $_POST['aDBc_time'] );
									$new_schedule_params['active'] 				= sanitize_html_class( $_POST['aDBc_status'] );
									$clean_schedule_setting[$_POST['aDBc_schedule_name']] = $new_schedule_params;

									update_option( 'aDBc_clean_schedule', $clean_schedule_setting, "no" );

									list( $year, $month, $day ) 	= explode( '-', preg_replace( "/[^0-9-]/", '', $_POST['aDBc_date'] ) );
									list( $hours, $minutes ) 		= explode( ':', preg_replace( "/[^0-9:]/", '', $_POST['aDBc_time'] ) );

									$seconds 	= "0";
									$timestamp 	=  mktime( $hours, $minutes, $seconds, $month, $day, $year );

									// Clear scheduled event
									wp_clear_scheduled_hook( 'aDBc_clean_scheduler', array( $_POST['aDBc_schedule_name'] . '' ) );

									if ( $_POST['aDBc_status'] == "1" ) {

										if ( $_POST['aDBc_schedule_repeat'] == "once" ) {
											wp_schedule_single_event( $timestamp, "aDBc_clean_scheduler", array( $_POST['aDBc_schedule_name'] ) );
										} else {
											wp_schedule_event( $timestamp, sanitize_html_class( $_POST['aDBc_schedule_repeat'] ), "aDBc_clean_scheduler", array( $_POST['aDBc_schedule_name'] ) );
										}

										$this->aDBc_message = __( 'The clean-up schedule saved successfully!', 'advanced-database-cleaner' );

									} else {
										$this->aDBc_message = __( 'The clean-up schedule saved successfully but it is inactive!', 'advanced-database-cleaner' );
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
			} else {
				$this->aDBc_class_message = "error";
				$this->aDBc_message = __( 'Please select at least one item to include in the schedule from the table below!', 'advanced-database-cleaner' );
			}
		}

		// Get all unused elements
		$aDBc_unused_elements = aDBc_return_array_all_elements_to_clean();

		// Get settings from DB
		$settings = get_option( 'aDBc_settings' );

		foreach ( $aDBc_unused_elements as $element_type => $element_name ) {

			// Get "keep_last" option. This option is added in ADBC version 3.0, so test if it is not empty before using it
			if ( empty( $settings['keep_last'] ) ) {

				$keep_number = '0';

			} else {

				$keep_setting = $settings['keep_last'];

				if ( empty( $keep_setting[$element_type] ) ) {
					$keep_number = '0';
				} else {
					$keep_number = $keep_setting[$element_type];
				}
			}
			// If the item can have keep_last, then prepare it, otherwise echo N/A
			if ( $element_type == "revision" ||
				 $element_type == "auto-draft" ||
				 $element_type == "trash-posts" ||
				 $element_type == "moderated-comments" ||
				 $element_type == "spam-comments" ||
				 $element_type == "trash-comments" ||
				 $element_type == "pingbacks" ||
				 $element_type == "trackbacks") {

				$keep_info = "<span>" . $keep_number . " " . __( 'days', 'advanced-database-cleaner' );

			} else {

				$keep_info = __( 'N/A', 'advanced-database-cleaner' );

			}

			array_push( $this->aDBc_elements_to_display, array(
				'element_to_schedule' 	=> "<a href='" . $element_name['URL_blog'] . "' target='_blank' class='aDBc-info-icon'>&nbsp;</a>" . $element_name['name'],
				'keep'   				=> $keep_info,
				'type'					=> $element_type
				)
			);
		}

		// Call WP prepare_items function
		$this->prepare_items();
	}

	/** WP: Get columns */
	function get_columns() {

		$aDBc_keep_last_toolip = "<span class='aDBc-tooltips-headers'>
									<img class='aDBc-info-image' src='".  ADBC_PLUGIN_DIR_PATH . '/images/information2.svg' . "'/>
									<span>" . __( 'Only data that is older than the number you have specified will be cleaned based on you schedule parameters. To change this value, click on "go back" button.', 'advanced-database-cleaner' ) ." </span>
								  </span>";

		$columns = array(
			'cb'        			=> '<input type="checkbox" />',
			'element_to_schedule' 	=> __( 'Elements to include in the schedule', 'advanced-database-cleaner' ),
			'keep'   				=> __( 'Keep last', 'advanced-database-cleaner' ) . $aDBc_keep_last_toolip,
			'type'   				=> 'Type'
		);

		return $columns;
	}

	/** WP: Prepare items to display */
	function prepare_items() {

		$columns 				= $this->get_columns();
		$hidden 				= $this->get_hidden_columns();
		$sortable 				= array();
		$this->_column_headers 	= array( $columns, $hidden, $sortable );
		$per_page 				= 50;
		$current_page 			= $this->get_pagenum();

		// Prepare sequence of elements to display
		$display_data = array_slice( $this->aDBc_elements_to_display, ( ( $current_page-1 ) * $per_page ), $per_page );

		$this->set_pagination_args( array(
			'total_items' => count( $this->aDBc_elements_to_display ),
			'per_page'    => $per_page
		));

		$this->items = $display_data;
	}

	/** WP: Get columns that should be hidden */
    function get_hidden_columns() {

		return array( 'type' );

    }

	/** WP: Column default */
	function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'element_to_schedule':
			case 'keep':
			case 'type':
				return $item[$column_name];
			default:
			  return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes

		}
	}

	/** WP: Column cb for check box */
	function column_cb( $item ) {

		$checked = "";

		if ( isset( $_POST['aDBc_schedule_name'] ) && ! empty( $_POST['aDBc_elements_to_process'] ) ) {

			if ( in_array( $item['type'], $_POST['aDBc_elements_to_process'] ) ) {
				$checked = "checked";
			}

		} else {

			$schedule_settings 			= get_option('aDBc_clean_schedule');
			$schedule_params 			= $schedule_settings[sanitize_html_class($_GET['hook_name'])];
			$schedule_elements_to_clean = $schedule_params['elements_to_clean'];
			$schedule_elements_to_clean = is_array($schedule_elements_to_clean) ? $schedule_elements_to_clean : array();

			if ( in_array($item['type'], $schedule_elements_to_clean ) ) {
				$checked = "checked";
			}
		}

		return sprintf( '<input type="checkbox" name="aDBc_elements_to_process[]" value="%s"' .  $checked . '/>', $item['type'] );
	}

	/** WP: Get bulk actions */
	function get_bulk_actions() {

		return array();

	}

	/** WP: Message to display when no items found */
	function no_items() {

		_e( 'Your database is clean!', 'advanced-database-cleaner' );

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

				<a href="?page=advanced_db_cleaner&aDBc_tab=general" style="text-decoration:none">

					<span class="dashicons dashicons-controls-back aDBc-back-dashicon"></span>

					<span style="vertical-align:middle"><?php echo __( 'Return', 'advanced-database-cleaner' ); ?></span>

				</a>

			</div>

			<div class="aDBc-schedule-title">

				<span class="dashicons dashicons-edit aDBc-schedule-dashicon"></span>

				<?php echo __( 'Edit cleanup schedule', 'advanced-database-cleaner' ); ?>

			</div>

			<div class="aDBc-clear-both"></div>

			<form id="aDBc_form" action="" method="post">

				<!-- Print the elements to clean -->
				<div class="aDBc-schedule-table-elements">

					<?php $this->display(); ?>

				</div>

				<?php
				// Prepare info of the original schedule to fill it into inputs...

				if ( isset( $_POST['aDBc_schedule_name'] ) ) {

					$hook_name 			= sanitize_html_class( $_POST['aDBc_schedule_name'] );
					$schedule_repeat 	= sanitize_html_class( $_POST['aDBc_schedule_repeat'] );
					$schedule_date 		= preg_replace( "/[^0-9-]/", '', $_POST['aDBc_date'] );
					$schedule_time		= preg_replace( "/[^0-9:]/", '', $_POST['aDBc_time'] );
					$schedule_status	= sanitize_html_class( $_POST['aDBc_status'] );

				} else {

					$schedule_settings 	= get_option( 'aDBc_clean_schedule' );
					$schedule_params 	= $schedule_settings[sanitize_html_class( $_GET['hook_name'] )];

					$hook_name 			= sanitize_html_class( $_GET['hook_name'] );
					$schedule_repeat 	= $schedule_params['repeat'];
					$timestamp 			= wp_next_scheduled( "aDBc_clean_scheduler", array( sanitize_html_class( $_GET['hook_name'] ) . '' ) );

					if ( $timestamp ) {

						$schedule_date 	= date( "Y-m-d", $timestamp );
						$schedule_time 	= date( "H:i", $timestamp );

					} else {

						$schedule_date 	= date( "Y-m-d" );
						$schedule_time 	= date( "H:i", time() );

					}

					$schedule_status	= $schedule_params['active'];
				}
				?>

				<div class="aDBc-right-box">

					<div class="aDBc-right-box-content">

						<div style="text-align:center">
							<img width="60px" src="<?php echo ADBC_PLUGIN_DIR_PATH . '/images/alarm-clock.svg' ?>"/>
						</div>

						<div id="add_schedule" class="aDBc-schedule-info-container">

							<div class="aDBc-margin-t-10"></div>

							<div><?php _e( 'Schedule name', 'advanced-database-cleaner' ); ?></div>

							<input class="aDBc-schedule-input-field" type="text" value="<?php echo $hook_name; ?>" maxlength="25" disabled>

							<input type="hidden" name="aDBc_schedule_name" value="<?php echo $hook_name; ?>" maxlength="25">

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

									if ( $code_repeat == $schedule_repeat ) {
										echo "<option value='$code_repeat' selected='selected'>$name_repeat</option>";
									} else {
										echo "<option value='$code_repeat'>$name_repeat</option>";
									}

								}
								?>

							</select>

							<div><?php _e( 'Start date', 'advanced-database-cleaner' ); ?></div>

							<input name="aDBc_date" class="aDBc-schedule-input-field" type="date" value="<?php echo $schedule_date; ?>" min="<?php echo date( "Y-m-d" ); ?>">

							<div><?php _e( 'Start time (GMT)', 'advanced-database-cleaner' ); ?></div>

							<input name="aDBc_time" class="aDBc-schedule-input-field" type="time" value="<?php echo $schedule_time; ?>">

							<div><?php _e( 'Schedule status', 'advanced-database-cleaner' ); ?></div>

							<div class="aDBc-schedule-radio-container">

								<input name="aDBc_status" type="radio" value="1" checked>

								<span style="margin-right:20px"><?php _e( 'Active', 'advanced-database-cleaner' ); ?></span>

								<input name="aDBc_status" type="radio" value="0" <?php echo $schedule_status == "0" ? 'checked' : ''; ?>>

								<?php _e( 'Inactive', 'advanced-database-cleaner' ); ?>

							</div>

							<div class="aDBc-schedule-save-btn-div">

								<input class="button-primary" type="submit"  value="<?php _e( 'Save the schedule', 'advanced-database-cleaner' ); ?>" style="width:100%"/>

							</div>

						</div>
					</div>
				</div>

				<?php wp_nonce_field( 'edit_cleanup_schedule_nonce', 'edit_cleanup_schedule_nonce' ); ?>

			</form>
			<div class="aDBc-clear-both"></div>
		</div>

	<?php
	}
}

?>
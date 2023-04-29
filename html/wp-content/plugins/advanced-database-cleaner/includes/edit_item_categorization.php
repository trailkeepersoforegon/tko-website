
<!-- style et code ok -->

<div style="max-width:700px">

	<form id="aDBc_form" action="" method="post">

		<div class="aDBc-edit-correction-title">

			<?php echo __( 'Manual correction of the categorization', 'advanced-database-cleaner' ); ?>

		</div>

		<?php

		// Get the current tab
		$item_type = $_GET['aDBc_tab'];

		// We change the cron tab name to tasks. No problem with tables and options
		if ( $item_type == "cron" ) {

			$item_type = "tasks";

		}

		// Open the file in which the items to edit have been saved
		$path_items = @fopen( ADBC_UPLOAD_DIR_PATH_TO_ADBC . "/" . $item_type . "_manually_correction_temp.txt", "r" );

		if ( $path_items ) {

			echo "<div style='margin-top:30px'>";
			$items_to_correct_array = array();

			while ( ( $item = fgets( $path_items ) ) !== false ) {

				$item = trim( $item );

				if ( ! empty( $item ) ) {

					array_push( $items_to_correct_array, $item );
					echo "<span class='aDBc-correction-item'>" . $item . "</span>";

				}
			}

			echo "</div>";
			fclose( $path_items );
		}
		?>

		<div class="aDBc-clear-both"></div>

		<div class="aDBc-correction-new-wrapper">

			<div>

				<?php
				if ( $item_type == "tables" ) {

					echo __( 'The tables above belong to:', 'advanced-database-cleaner' );

				} elseif ( $item_type == "options" ) {

					echo __( 'The options above belong to:', 'advanced-database-cleaner' );

				} elseif ( $item_type == "tasks" ) {

					echo __( 'The cron tasks above belong to:', 'advanced-database-cleaner' );

				}
				?>

			</div>

			<?php
			$plugins_folders_names 	= aDBc_get_plugins_folder_names();
			$themes_folders_names 	= aDBc_get_themes_folder_names();
			?>

			<select name="new_belongs_to" class="aDBc-correction-belongs-to">

				<optgroup label="<?php echo __( 'Plugins', 'advanced-database-cleaner' ); ?>">

					<?php
					foreach ( $plugins_folders_names as $plugin ) {
						echo "<option value='$plugin|p'>" . $plugin . "</option>";
					}
					?>

				</optgroup>

				<optgroup label="<?php echo __( 'Themes', 'advanced-database-cleaner' ); ?>">

					<?php
					foreach ( $themes_folders_names as $theme ) {
						echo "<option value='$theme|t'>" . $theme . "</option>";
					}
					?>

				</optgroup>

			</select>

		</div>

		<!--<div style="margin-top:15px">
			<div>
				<input type="checkbox" name="aDBc_send_correction_to_server"/>
				<span id="send_manual_correction_to_server">
					<?php //_e("Send this correction to the plugin server? (by sending this correction, you benefit from others' corrections)","advanced-database-cleaner") ?>
				</span>
			</div>-->
			<!-- xxx I should add link to read more -->
			<!--<div style="color:grey;margin-left:25px;">
				<?php //echo __("No sensitive info is sent","advanced-database-cleaner") . " <a href='#'>[" . __("Read more here", "advanced-database-cleaner") . "]</a>"; ?>
			</div>

		</div>-->

		<div class="aDBc-clear-both"></div>

		<div class="aDBc-correction-btns-div">

			<input name="aDBc_correct" class="button-primary aDBc-correction-btn" type="submit" value="<?php _e( 'Save', 'advanced-database-cleaner' ); ?>"/>

			<input name="aDBc_cancel" class="button-secondary aDBc-correction-btn" type="submit" value="<?php _e( 'Cancel', 'advanced-database-cleaner' ); ?>"/>

		</div>

	</form>

</div>
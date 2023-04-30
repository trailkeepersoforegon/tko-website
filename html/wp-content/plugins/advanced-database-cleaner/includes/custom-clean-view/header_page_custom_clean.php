<!-- style et code ok -->

<div class="aDBc-float-left aDBc-margin-t-10">

	<a href="?page=advanced_db_cleaner&aDBc_tab=general" style="text-decoration:none">

		<span class="dashicons dashicons-controls-back aDBc-back-dashicon"></span>

		<span style="vertical-align:middle"><?php echo __( 'Return', 'advanced-database-cleaner' ); ?></span>

	</a>

</div>

<div>

	<div class="aDBc-custom-clean-text">

		<?php
		echo __( 'Custom cleanup of', 'advanced-database-cleaner' ) . " ";
		echo "<strong>" . $this->aDBc_plural_title . "</strong> - ";
		echo __( 'Total Found', 'advanced-database-cleaner' ) . " : ";
		echo "<b><span class='aDBc-custom-total'>" . count( $this->aDBc_elements_to_display ) . "</span></b>";
		?>

	</div>

	<div class="aDBc-filter-container">

		<div class="aDBc-filter-section">

			<span class="aDBc-premium-tooltip">

				<?php

				$free_style = "";

				if ( ADBC_PLUGIN_PLAN == "free" ) {

					$free_style = "aDBc-filter-pro-only";

				}

				?>

				<form class="<?php echo $free_style; ?>" method="get">

					<?php
					// Generate current parameters in URL
					foreach ( $_GET as $name => $value ) {

						if ( $name != "s" && $name != "in" && $name != "paged" ) {

							$name 	= esc_attr( sanitize_text_field( $name ) );
							$value 	= esc_attr( sanitize_text_field( $value ) );
							echo "<input type='hidden' name='$name' value='$value'/>";

						}
					}

					// Return paged to page 1
					echo "<input type='hidden' name='paged' value='1'/>";
					?>

					<input class="aDBc-filter-search-input" type="search" placeholder="<?php _e( 'Search for', 'advanced-database-cleaner' ); ?>" name="s" value="<?php echo empty( $_GET['s'] ) ? '' : esc_attr( $_GET['s'] ); ?>"/>

					<div class="aDBc-custom-filter-radio-section">

						<span style="padding:0px 10px"><?php _e( 'Search in', 'advanced-database-cleaner' ); ?></span>

						<?php
						$in_checked 	= empty( $_GET['in'] ) || ( ! empty( $_GET['in'] ) && $_GET['in'] == "key" ) ? 'checked' : '';
						$value_checked 	= ! empty( $_GET['in'] ) && $_GET['in'] == "value" ? 'checked' : '';
						?>

						<input type="radio" name="in" value="key" checked <?php echo $in_checked; ?>><?php _e( 'Name', 'advanced-database-cleaner' ); ?> &nbsp;

						<input type="radio" name="in" value="value" <?php echo $value_checked; ?>><?php _e( 'Value', 'advanced-database-cleaner' ); ?>

					</div>

					<input class="button-secondary aDBc-filter-botton" type="submit" value="<?php _e( 'Filter', 'advanced-database-cleaner' ); ?>"/>

				</form>

				<?php
				if ( ADBC_PLUGIN_PLAN == "free" ) {
				?>

					<span style="width:150px" class="aDBc-premium-tooltiptext">

						<?php _e( 'Available in Pro version!', 'advanced-database-cleaner' ); ?>

					</span>

				<?php
				}
				?>

			</span>

		</div>

		<!-- Items per page -->
		<div class="aDBc-items-per-page">

			<form method="get">

				<?php
				// Generate current parameters in URL
				foreach ( $_GET as $name => $value ) {

					if ( $name != "per_page" && $name != "paged" ) {

						$name 	= esc_attr( sanitize_text_field( $name ) );
						$value 	= esc_attr( sanitize_text_field( $value ) );
						echo "<input type='hidden' name='$name' value='$value'/>";

					}
				}

				// Return paged to page 1
				echo "<input type='hidden' name='paged' value='1'/>";
				?>

				<span class="aDBc-items-per-page-label">
					<?php _e( 'Items per page', 'advanced-database-cleaner' ); ?>
				</span>

				<input name="per_page" class="aDBc-items-per-page-input" type="number" value="<?php echo empty( $_GET['per_page'] ) ? '50' : esc_attr( $_GET['per_page'] ); ?>"/>

				<input type="submit" class="button-secondary aDBc-show-botton" value="<?php _e( 'Show', 'advanced-database-cleaner' ); ?>"/>

			</form>

		</div>

		<?php
		if ( ( ! empty( $_GET['s'] ) && trim( $_GET['s'] ) != "" ) ||
			   ! empty( $_GET['in'] )
			) {

			// Remove args to delete custom filter
			$aDBc_new_URI = $_SERVER['REQUEST_URI'];
			$aDBc_new_URI = remove_query_arg( array( 's', 'in' ), $aDBc_new_URI );
		?>

			<div class="aDBc-delete-custom-filter">
				<a style="color:red" href="<?php echo esc_url( $aDBc_new_URI ); ?>">
					<?php _e( 'Delete custom filter', 'advanced-database-cleaner' ); ?>
				</a>
			</div>

		<?php
		}
		?>

	</div>

</div>
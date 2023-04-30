<?php
/**
 * Plugin Name:       Eventbrite Tickets Extension: Additional Options
 * Plugin URI:        https://theeventscalendar.com/extensions/eventbrite-additional-options/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-eventbrite-additional-options
 * Description:       Adds a new Eventbrite options section to the bottom of wp-admin > Events > Settings > Imports tab. Options include text above or below iframe ticket area, iframe height, moving ticket area's location on the Single Event view, displaying tickets for Private Eventbrite events, change API URL (e.g. from .com to .co.uk), and more.
 * Version:           1.0.3
 * Extension Class:   Tribe__Extension__Eventbrite_Addl_Opts
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tribe-ext-eventbrite-additional-options
 */

// Do not load unless Tribe Common is fully loaded.
if (
	class_exists( 'Tribe__Extension' )
	&& ! class_exists( 'Tribe__Extension__Eventbrite_Addl_Opts' )
) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Tribe__Extension__Eventbrite_Addl_Opts extends Tribe__Extension {
		/**
		 * Namespace prefix for this extension's database options.
		 *
		 * @var string
		 */
		protected $opts_prefix = 'tribe_ext_eventbrite_opts_';

		/**
		 * Setup the Extension's properties.
		 *
		 * This always executes even if the required plugins are not present.
		 */
		protected function construct() {
			$this->add_required_plugin( 'Tribe__Events__Main', '4.4' );

			// Version 4.4.6 is when the `tribe_events_eventbrite_iframe_height` filter was added.
			$this->add_required_plugin( 'Tribe__Events__Tickets__Eventbrite__Main', '4.4.6' );

			// Set the extension's TEC URL
			$this->set_url( 'https://theeventscalendar.com/extensions/eventbrite-additional-options/' );
		}

		/**
		 * Adds settings options.
		 */
		public function add_settings() {
			if ( ! class_exists( 'Tribe__Extension__Settings_Helper' ) ) {
				require_once dirname( __FILE__ ) . '/src/Tribe/Settings_Helper.php';
			}

			$setting_helper = new Tribe__Extension__Settings_Helper();

			$fields = array(
				$this->opts_prefix . 'heading'        => array(
					'type' => 'html',
					'html' => '<h3>' . esc_html__( 'Eventbrite Additional Options', 'tribe-ext-eventbrite-additional-options' ) . '</h3>',
				),
				$this->opts_prefix . 'content_before' => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Heading above ticket area', 'tribe-ext-eventbrite-additional-options' ),
					'tooltip'         => esc_html__( 'Adds a heading above the ticket area. By default, this is blank and no heading is shown.', 'tribe-ext-eventbrite-additional-options' ),
					'validation_type' => 'textarea',
				),

				$this->opts_prefix . 'content_after' => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Text underneath ticket area', 'tribe-ext-eventbrite-additional-options' ),
					'tooltip'         => esc_html__( 'By default, there is no content after the ticket area.', 'tribe-ext-eventbrite-additional-options' ),
					'validation_type' => 'textarea',
				),

				$this->opts_prefix . 'iframe_px' => array(
					'type'            => 'text',
					'label'           => esc_html__( 'Tickets area height', 'tribe-ext-eventbrite-additional-options' ),
					'default'         => 200,
					'tooltip'         => esc_html__( 'The height of the iframe ticket area in pixels.', 'tribe-ext-eventbrite-additional-options' ),
					'validation_type' => 'positive_int',
				),

				$this->opts_prefix . 'locale' => array(
					'type'            => 'dropdown',
					'label'           => esc_html__( 'Eventbrite Locale', 'tribe-ext-eventbrite-additional-options' ),
					'default'         => 'en_US',
					'tooltip'         => esc_html__( 'This tells eventbrite.com which locale to use.', 'tribe-ext-eventbrite-additional-options' ),
					'validation_type' => 'options',
					'options'         => $this->eb_locale_country_name_array(),
				),

				$this->opts_prefix . 'ticket_single_location' => array(
					'type'            => 'dropdown',
					'label'           => esc_html__( 'Location of Tickets form', 'tribe-ext-eventbrite-additional-options' ),
					'default'         => 'tribe_events_single_event_after_the_meta',
					'validation_type' => 'options',
					'options'         => $this->single_event_display_locations_array(),
				),

				$this->opts_prefix . 'show_tickets_private_events' => array(
					'type'            => 'checkbox_bool',
					'label'           => esc_html__( 'Display Tickets for Private Events', 'tribe-ext-eventbrite-additional-options' ),
					'tooltip'         => esc_html__( 'By default, Eventbrite Tickets only displays tickets for events marked Public on Eventbrite. Check this box to also display tickets for the Private events that you have imported.', 'tribe-ext-eventbrite-additional-options' ),
					'validation_type' => 'boolean',
				),

			);

			$setting_helper->add_fields(
				$fields,
				'imports', // not the 'event-tickets' ("Tickets" tab) because it doesn't exist without Event Tickets
				'import-defaults-update_authority',
				true
			);
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			load_plugin_textdomain( 'tribe-ext-eventbrite-additional-options', false, basename( dirname( __FILE__ ) ) . '/languages/' );

			add_action( 'admin_init', array( $this, 'add_settings' ) );

			$before = tribe_get_option( $this->opts_prefix . 'content_before' );
			if ( ! empty( $before ) ) {
				add_filter( 'tribe_events_eventbrite_before_the_tickets', array( $this, 'before_iframe' ) );
			}

			$after = tribe_get_option( $this->opts_prefix . 'content_after' );
			if ( ! empty( $after ) ) {
				add_filter( 'tribe_events_eventbrite_after_the_tickets', array( $this, 'after_iframe' ) );
			}

			$show_tickets_private_events = tribe_get_option( $this->opts_prefix . 'show_tickets_private_events' );
			if ( ! empty( $show_tickets_private_events ) ) {
				add_filter( 'tribe_events_eventbrite_the_tickets', array( $this, 'render_tickets_iframe_private_eb_events' ) );
			}

			add_filter( 'tribe_events_eventbrite_iframe_height', array( $this, 'iframe_height' ), 10, 1 );

			if ( 'en_US' !== $this->get_chosen_eb_locale() ) {
				add_filter( 'tribe-eventbrite-base_api_url', array( $this, 'api_url' ) );
				add_filter( 'tribe_eb_api_sync_event', array( $this, 'eb_currency_for_update_create' ), 10, 5 );
			}

			$eb_new_ticket_location     = tribe_get_option( $this->opts_prefix . 'ticket_single_location' );
			$eb_ticket_default_location = 'tribe_events_single_event_after_the_meta';
			if ( ! empty( $eb_new_ticket_location ) && $eb_ticket_default_location !== $eb_new_ticket_location ) {
				$display_tickets = array(
					tribe( 'eventbrite.main' ),
					'print_ticket_form'
				);
				remove_action( $eb_ticket_default_location, $display_tickets, 9 );
				add_action( $eb_new_ticket_location, $display_tickets );
			}
		}

		/**
		 * Get the HTML to be output before the iframe.
		 *
		 * @see 'tribe_events_eventbrite_before_the_tickets'
		 *
		 * @return mixed|string
		 */
		public function before_iframe() {
			$text = tribe_get_option( $this->opts_prefix . 'content_before' );

			$text = sprintf( '<h3 class="tribe_ext_eventbrite_opts">%s</h3>', $text );

			return $text;
		}

		/**
		 * Get the HTML to be output after the iframe.
		 *
		 * @see 'tribe_events_eventbrite_after_the_tickets'
		 *
		 * @return mixed|string
		 */
		public function after_iframe() {
			$text = tribe_get_option( $this->opts_prefix . 'content_after' );

			$text = sprintf( '<span class="tribe_ext_eventbrite_opts">%s</span>', $text );

			return $text;
		}

		/**
		 * Replace the iframe's height.
		 *
		 * @see 'tribe_events_eventbrite_iframe_height'
		 *
		 * @param string $iframe_height
		 *
		 * @return int
		 */
		public function iframe_height( $iframe_height = 200 ) {
			$new_height = absint( tribe_get_option( $this->opts_prefix . 'iframe_px' ) );

			if ( 0 === $new_height ) {
				$new_height = $iframe_height;
			}

			return (int) $new_height;
		}

		/**
		 * Get the Eventbrite Currency setting value.
		 *
		 * Affects the API call.
		 *
		 * @see 'tribe_eb_api_sync_event'
		 *
		 * @param array   $args
		 * @param string  $mode
		 * @param int     $eventbrite_id
		 * @param WP_Post $post
		 * @param array   $params
		 *
		 * @return mixed
		 */
		public function eb_currency_for_update_create( $args, $mode, $eventbrite_id, $post, $params ) {
			$currency = $this->get_currency_from_eb_locale();

			$args['event.currency'] = $currency;

			return $args;
		}

		/**
		 * List of locales supported by Eventbrite.
		 *
		 * This list is hand-crafted from the Region Selector in the global footer of Eventbrite.com pages. Screenshot at https://cl.ly/412T23182w1Q.
		 *
		 * @return array
		 */
		private function eb_locale_country_name_array() {
			$array = array(
				'es_AR' => __( 'Argentina', 'tribe-ext-eventbrite-additional-options' ),
				'en_AU' => __( 'Australia', 'tribe-ext-eventbrite-additional-options' ),
				'pt_BR' => __( 'Brazil', 'tribe-ext-eventbrite-additional-options' ),
				'en_CA' => __( 'Canada (EN)', 'tribe-ext-eventbrite-additional-options' ),
				'fr_CA' => __( 'Canada (FR)', 'tribe-ext-eventbrite-additional-options' ),
				'de_DE' => __( 'Germany', 'tribe-ext-eventbrite-additional-options' ),
				'es_ES' => __( 'Spain', 'tribe-ext-eventbrite-additional-options' ),
				'fr_FR' => __( 'France', 'tribe-ext-eventbrite-additional-options' ),
				'en_HK' => __( 'Hong Kong', 'tribe-ext-eventbrite-additional-options' ),
				'en_IE' => __( 'Ireland', 'tribe-ext-eventbrite-additional-options' ),
				'it_IT' => __( 'Italy', 'tribe-ext-eventbrite-additional-options' ),
				'nl_NL' => __( 'Netherlands', 'tribe-ext-eventbrite-additional-options' ),
				'en_NZ' => __( 'New Zealand', 'tribe-ext-eventbrite-additional-options' ),
				'pt_PT' => __( 'Portugal', 'tribe-ext-eventbrite-additional-options' ),
				'en_SG' => __( 'Singapore', 'tribe-ext-eventbrite-additional-options' ),
				'en_GB' => __( 'United Kingdom', 'tribe-ext-eventbrite-additional-options' ),
				'en_US' => __( 'United States', 'tribe-ext-eventbrite-additional-options' ),
			);

			$array = $this->append_array_keys_to_values( $array );

			return $array;
		}

		/**
		 * Joins the array keys with its values.
		 *
		 * Used for display purposes in the settings/choices.
		 *
		 * @param array $array
		 *
		 * @return array|bool
		 */
		private function append_array_keys_to_values( $array = array() ) {
			if ( ! is_array( $array ) || empty( $array ) ) {
				return false;
			}

			$new_array = array();

			foreach ( $array as $key => $value ) {
				$new_array[ $key ] = sprintf( '%s (%s)', $value, $key );
			}

			return $new_array;
		}

		/**
		 * Get user-selected Eventbrite locale.
		 *
		 * @return string
		 */
		private function get_chosen_eb_locale() {
			$locale = tribe_get_option( $this->opts_prefix . 'locale' );

			if (
				empty( $locale )
				|| ! is_string( $locale )
				|| ! array_key_exists( $locale, $this->eb_locale_country_name_array() )
			) {
				$locale = 'en_US';
			}

			return $locale;
		}

		/**
		 * Get Eventbrite currency from specified locale.
		 *
		 * This list is hand-crafted from noting the currency specified in the calculator at https://www.eventbrite.com/fees/ (per TLD) after selecting each region from Eventbrite's Region Selector. Could just play with PHP's https://secure.php.net/localeconv but decided against it to avoid changing system settings.
		 *
		 * @return string
		 */
		private function get_currency_from_eb_locale() {
			$locale = $this->get_chosen_eb_locale();

			$locale_currencies = array(
				'es_AR' => 'ARS',
				'en_AU' => 'AUD',
				'pt_BR' => 'BRL',
				'en_CA' => 'CAD',
				'fr_CA' => 'CAD',
				'de_DE' => 'EUR',
				'es_ES' => 'EUR',
				'fr_FR' => 'EUR',
				'en_HK' => 'HKD', // EB's fees calculator does not display
				'en_IE' => 'EUR',
				'it_IT' => 'EUR',
				'nl_NL' => 'EUR',
				'en_NZ' => 'NZD',
				'pt_PT' => 'EUR',
				'en_SG' => 'SGD', // EB's fees calculator does not display
				'en_GB' => 'GBP',
				'en_US' => 'USD',
			);

			$currency = $locale_currencies[ $locale ];

			return $currency;
		}

		/**
		 * Get Eventbrite Top-Level Domain (TLD) from specified locale.
		 *
		 * This list is hand-crafted from noting the domain change after selecting each region from Eventbrite's Region Selector.
		 *
		 * @return string
		 */
		private function eb_locale_to_tld() {
			$locale = $this->get_chosen_eb_locale();

			$locale_tlds = array(
				'es_AR' => '.com.ar',
				'en_AU' => '.com.au',
				'pt_BR' => '.br',
				'en_CA' => '.ca',
				'fr_CA' => '.ca', // it is the same!
				'de_DE' => '.de',
				'es_ES' => '.es',
				'fr_FR' => '.fr',
				'en_HK' => '.hk',
				'en_IE' => '.ie',
				'it_IT' => '.it',
				'nl_NL' => '.nl',
				'en_NZ' => '.co.nz',
				'pt_PT' => '.pt',
				'en_SG' => '.sg',
				'en_GB' => '.co.uk',
				'en_US' => '.com',
			);

			$tld = $locale_tlds[ $locale ];

			return $tld;
		}

		/**
		 * Get Eventbrite TLD from user-chosen Eventbrite Locale.
		 *
		 * @return string
		 */
		private function get_eb_tld() {
			return $this->eb_locale_to_tld( $this->get_chosen_eb_locale() );
		}

		/**
		 * Set the Top-Level Domain (TLD) to be used for the Eventbrite API call.
		 *
		 * @see 'tribe-eventbrite-base_api_url'
		 *
		 * @param string $url
		 *
		 * @return mixed|string
		 */
		public function api_url( $url ) {
			$replace_with = $this->get_eb_tld();

			$new_url = str_replace( '.com', $replace_with, $url );

			return esc_url( $new_url );
		}

		/**
		 * Displays Eventbrite Tickets iframe even if the Eventbrite event is Private.
		 *
		 * @see Tribe__Events__Tickets__Eventbrite__Template::the_tickets()
		 *
		 * @return string|void
		 */
		public function render_tickets_iframe_private_eb_events() {
			$post_id = get_the_ID();

			if ( class_exists( 'Tribe__Events__Tickets__Eventbrite__Event' ) ) {
				// Eventbrite Tickets version 4.5+
				$api = tribe( 'eventbrite.event' );
			} else {
				// Eventbrite Tickets version 4.4.8 and prior
				$api = tribe( 'eventbrite.api' );
			}

			$event = $api->get_event( $post_id );

			if ( ! $event ) {
				return;
			}

			$event_id = $event->id;

			$iframe_url = sprintf( 'https://www.eventbrite%s/tickets-external?eid=%d&amp;ref=etckt&v=2', $this->get_eb_tld(), $event_id );
			$iframe_url = apply_filters( 'tribe_events_eb_iframe_url', $iframe_url, $event_id );

			$iframe_height = self::instance()->iframe_height();

			$html = '';

			if (
				! empty( $event_id ) &&
				// Commented out because this is the whole point -- allowing Private events to be displayed -- in which case `$event->listed` would be `false`
				// ( isset( $event->listed ) && $event->listed ) &&
				$api->is_live( $post_id ) &&
				tribe_event_show_tickets( $post_id, $event )
			) {
				$html = sprintf(
					'<div class="eventbrite-ticket-embed">
						<iframe id="eventbrite-tickets-%1$s" src="%2$s" height="%3$s" width="100%%" frameborder="0" allowtransparency="true"></iframe>
						<div style="font-family:Helvetica, Arial, sans-serif; font-size:12px;margin:2px 0; width:100%; text-align:left;" >
							<a class="eventbrite-powered-by-eb" style="color: #ADB0B6; text-decoration: none;" target="_blank" rel="noopener" href="http://www.eventbrite.com/">Powered by Eventbrite</a>
						</div>
					</div>',
					$event_id,
					$iframe_url,
					$iframe_height
				);
			}

			/**
			 * Allows Eventbrite iframe HTML to be modified.
			 *
			 * @param string $html
			 * @param string $event_id The associated Eventbrite ID.
			 * @param int    $post_id
			 */
			return apply_filters( 'tribe_events_eb_iframe_html', $html, $event_id, $post_id );
		}

		/**
		 * Create the Single Event display locations options array.
		 *
		 * @return array
		 */
		private function single_event_display_locations_array() {
			$array = array(
				'tribe_events_single_event_before_the_content' => __( 'After Featured Image, Before Description', 'tribe-ext-eventbrite-additional-options' ),
				'tribe_events_single_event_after_the_content'  => __( 'After Description', 'tribe-ext-eventbrite-additional-options' ),
				'tribe_events_single_event_before_the_meta'    => __( 'Before the Meta', 'tribe-ext-eventbrite-additional-options' ),
				'tribe_events_single_event_after_the_meta'     => __( 'After the Meta', 'tribe-ext-eventbrite-additional-options' ),
			);

			return $array;
		}

	}
}
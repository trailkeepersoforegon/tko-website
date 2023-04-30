=== Eventbrite Tickets Extension: Additional Options ===
Contributors: ModernTribe
Donate link: http://m.tri.be/29
Tags: events, calendar
Requires at least: 4.7.0
Tested up to: 4.9.4
Requires PHP: 5.2.4
Stable tag: 1.0.3
License: GPL version 3 or any later version
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds a new Eventbrite options section to the bottom of wp-admin > Events > Settings > Imports tab.

== Description ==

Adds a new Eventbrite options section to the bottom of wp-admin > Events > Settings > Imports tab.

Options include text above or below iframe ticket area, iframe height, moving ticket area's location on the Single Event view, displaying tickets for Private Eventbrite events, change API URL (e.g. from .com to .co.uk), and more.

== Installation ==

Install and activate like any other plugin!

* You can upload the plugin zip file via the *Plugins ‣ Add New* screen
* You can unzip the plugin and then upload to your plugin directory (typically _wp-content/plugins)_ via FTP
* Once it has been installed or uploaded, simply visit the main plugin list and activate it

== Frequently Asked Questions ==

= Where can I find more extensions? =

Please visit our [extension library](https://theeventscalendar.com/extensions/) to learn about our complete range of extensions for The Events Calendar and its associated plugins.

= What if I experience problems? =

We're always interested in your feedback and our [premium forums](https://theeventscalendar.com/support-forums/) are the best place to flag any issues. Do note, however, that the degree of support we provide for extensions like this one tends to be very limited.

== Changelog ==

= 1.0.3 2018-05-22 =

* Feature - Add plugin header requirements to enable using GitHub Updater to keep this extension plugin updated automatically
* Fix - Avoid fatal caused by upcoming Eventbrite Tickets version 4.5+

= 1.0.2 2018-03-20 =

* Tweak - Now requires Eventbrite Tickets version 4.4.6+
* Tweak - Uses the `tribe_events_eventbrite_iframe_height` filter instead of performing HTML string manipulation
* Fix - Updated text domain to be unique and added a .pot file for translations

= 1.0.1 2018-01-08 =

* Fix - Now conditionally requires the Settings_Helper.php file to prevent the "Cannot declare class Tribe__Extension__Settings_Helper because the name is already in use" error.

= 1.0.0 2018-05-17 =

* Initial release

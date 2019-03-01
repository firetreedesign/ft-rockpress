=== RockPress ===
Contributors: firetree, danielmilner
Tags: church, rockrms, chms, rock rms
Requires at least: 4.3
Tested up to: 5.1
Requires PHP: 5.3
Stable tag: 1.0.13
License: GPLv2 or later
License URI: http://ww.gnu.org/licenses/gpl-2.0.html

Introducing the easiest way to display information from Rock RMS on your church WordPress site.

== Description ==

Introducing the easiest way to display information from Rock RMS on your church WordPress site.

> Requires your own [Rock RMS](https://rockrms.com/) installation. Check out our plugin in the Rock Shop for help with creating an API Key.

= Features: =

* Service Times Widget
* Campus Selector Widget

= Do More With Add-ons =

Extend the capabilities of RockPress with these add-ons:

* [Events](https://rockpresswp.com/downloads/events/) - Display event data from Rock RMS.
* [Lava](https://rockpresswp.com/downloads/lava/) - Execute and display Lava from Rock RMS.

== Installation ==

1. Upload the rockpress folder to the /wp-content/plugins/ directory.
2. Activate the RockPress plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin by going to the RockPress menu that appears in your WordPress Admin.

== Screenshots ==

1. Welcome
2. Getting Started

== Changelog ==

= 1.0.13 =
* Required library was not being loaded at the right time.

= 1.0.12 =
* Fixed an issue that prevented the Customizer from loading.

= 1.0.11 =
* Added compatibility with WordPress 5.0.

= 1.0.10 =
* Fixed some links that point to the RockPress website.

= 1.0.9 =
* Updated the Getting Started documentation to reference the new plugin available in the Rock Shop.

= 1.0.8 =
* Improved the data import functions.

= 1.0.7 =
* Added support for $orderby, $select, and $expand in GET requests.
* Removed some unnecessary CSS classes.
* Improved the interface during AJAX requests.
* Moved all JavaScript messages to script localization so that they are available to translate.

= 1.0.6 =
* Fixed an issue with the POST cache.

= 1.0.5 =
* The Service Times widget was not populating Campuses correctly.

= 1.0.4 =
* Fixed the inability to deactivate add-on licenses.

= 1.0.3 =
* The readme contained some invalid URLs.

= 1.0.2 =
* Fixed an issue with the support beacon not displaying.
* Expiration dates for add-on license keys are now displayed.
* Getting Started instructions have been updated.
* Updated the text domain to match our slug on WordPress.org.
* Fixed an issue with expired transients not being deleted.

= 1.0.1 =
* Added a function to POST data to Rock.
* Changed the shortcode button icon to the RockPress logo.

= 1.0.0 =
* Initial release

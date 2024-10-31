=== Sedox Performance Vehicle Catalogue ===
Tags: sedox performance,chiptuning,vehicle catalogue,ecu remaps
Requires at least: 5.1
Tested up to: 6.5.2
Requires PHP: 7.2
Stable tag: 1.5.1-build.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to include Sedox Performance Vehicle Catalogue directly into your Wordpress website.
The purchase of the Vehicle Catalogue API key is required for full functionality.

== Description ==

If you are a vehicle tuner or want to offer ECU remaps to your clients, Sedox Performance Vehicle Catalogue plugin allows you to integrate our vehicle data into your website so you don’t have to maintain it. It includes vehicle brands, manufacturer logos, models and engines data for bikes, cars, trucks, agriculture and marine vehicles, together with detailed Stage1, Stage2 remaps or deactivation information, an overview of engine and ECU characteristics and compatible flashing tools.

The purchase API key is mandatory to be able to use it. Please visit <a href="https://tuningfiles.com/vehicle-api/">Tuningfiles website</a> for more information about our API packages.

The plugin allows customization per your needs, including change of company logo, name, vehicle images, colors etc.


== Installation ==


1. Install through WP Plugins interface or upload `SedoxVDb` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress dashboard.
3. Enter API key in the plugin settings
4. Place shortcode [sedox-catalogue] in text block on pages where you want to show it.


== Screenshots ==

1. Look of the plugin on frontend
2. Plugin configuration
3. Frontend customization

== Changelog ==

= 1.5.1 =
* Improved plugin frontend styles and scripts loading

= 1.5.0 =
* Added a separate language selector for the plugin
  Note: selected language needs to be installed in Wordpress (under Settings -> General -> Site Language)

= 1.4.1 =
* Support PHP 7.2 again

= 1.4.0 =
* Added Nm/ft-lb switcher to tuning table

= 1.3.0 =
* Updated to support PHP 8.2
* Updated outdated build tools
* Replace outdated Logger package

= 1.2.2 =
* Update tuningfiles-php-sdk to 1.2.1

= 1.2.1 =
* Chart tooltip not visible on some themes

= 1.2.0 =
* Some bug fixes (scoped classes)

= 1.1.1 =
* Some bug fixes

= 1.1.0 =
* Remove bootstrap from plugin and rewrite css so it doesn't depend on bootstrap.

= 1.0.14 =
* Fix generation/model image border and sizing.

= 1.0.13 =
* Test for Wordpress 5.7

= 1.0.12 =
* Fix Hybrid engines not showing

= 1.0.11 =
* Add modifications needed

= 1.0.10 =
* Add fallback vehicle image

= 1.0.9 =
* Additional API outage checks

= 1.0.8 =
* Fixed no timeout when API is not working

= 1.0.7 =
* Fixed missing translations on charts. Fixed errors when API is not working.

= 1.0.6 =
* Added checks for engine properties to prevent errors.

= 1.0.5 =
* Fixed Engine list no name group listing.

= 1.0.4 =
* Fixed Vehicle Type not preselected from url.

= 1.0.3 =
* Fixed getting customization options on frontend and missing wp_enqueue_media in admin.

= 1.0.2 =
* Fixed displaying generation year instead of model year

= 1.0.1 =
* Small CSS fix, code cleanup

= 1.0 =
* Plugin release

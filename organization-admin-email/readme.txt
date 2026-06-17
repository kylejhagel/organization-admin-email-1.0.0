=== Organization Admin Email ===
Contributors: kylejhagel
Tags: admin email, recovery mode, notifications, settings
Requires at least: 5.2
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a dedicated Organization Admin Email for operational WordPress notifications while preserving the native Site Admin Email as the technical/developer contact.

== Description ==

Organization Admin Email lets a site define a separate organization-facing administrative email address in Settings > General.

The native WordPress Site Admin Email remains unchanged and can continue to represent the developer, agency, or technical maintainer. The Organization Admin Email receives operational WordPress alerts, including recovery mode and fatal error emails.

Version 1 behavior:

* Adds an Organization Admin Email field to Settings > General.
* Validates and saves the email address.
* Routes WordPress recovery mode / fatal error emails to the Organization Admin Email.
* Routes outgoing WordPress emails addressed to the native Site Admin Email to the Organization Admin Email.
* Does not reroute emails sent to other recipients.

== Installation ==

1. Upload the plugin ZIP through Plugins > Add New > Upload Plugin.
2. Activate Organization Admin Email.
3. Go to Settings > General.
4. Enter the Organization Admin Email.
5. Save changes.

== Developer Notes ==

The general admin email routing can be customized with the `oae_should_route_admin_email` filter.

Example:

`add_filter( 'oae_should_route_admin_email', '__return_false' );`

== Changelog ==

= 1.0.0 =
* Initial local testing release.

<?php
/**
 * Plugin Name: Organization Admin Email
 * Plugin URI:  https://example.com/organization-admin-email
 * Description: Adds a dedicated Organization Admin Email for operational WordPress notifications while preserving the native Site Admin Email for technical/developer contact.
 * Version:     1.0.0
 * Author:      Kyle J Hagel
 * Text Domain: organization-admin-email
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OAE_VERSION', '1.0.0' );
define( 'OAE_PLUGIN_FILE', __FILE__ );
define( 'OAE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OAE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'OAE_OPTION_ORGANIZATION_ADMIN_EMAIL', 'oae_organization_admin_email' );

require_once OAE_PLUGIN_DIR . 'includes/helpers.php';
require_once OAE_PLUGIN_DIR . 'includes/class-settings.php';
require_once OAE_PLUGIN_DIR . 'includes/class-recovery-email.php';
require_once OAE_PLUGIN_DIR . 'includes/class-email-router.php';
require_once OAE_PLUGIN_DIR . 'includes/class-plugin.php';

add_action(
	'plugins_loaded',
	static function () {
		\OAE\Plugin::init();
	}
);

<?php
/**
 * Main plugin coordinator.
 *
 * @package OrganizationAdminEmail
 */

namespace OAE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	/**
	 * Initialize plugin services.
	 *
	 * @return void
	 */
	public static function init(): void {
		Settings::init();
		Recovery_Email::init();
		Email_Router::init();
	}
}

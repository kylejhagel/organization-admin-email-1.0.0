<?php
/**
 * Recovery mode and fatal error email routing.
 *
 * @package OrganizationAdminEmail
 */

namespace OAE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Recovery_Email {
	/**
	 * Initialize recovery email hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_filter( 'recovery_mode_email', array( __CLASS__, 'route_recovery_mode_email' ), 10, 2 );
	}

	/**
	 * Route WordPress recovery mode/fatal error emails to the Organization Admin Email.
	 *
	 * @param array $email Email data with to, subject, message, headers, and attachments.
	 * @param string $url Recovery mode URL.
	 * @return array
	 */
	public static function route_recovery_mode_email( array $email, string $url ): array {
		$organization_email = \oae_get_organization_admin_email();

		if ( '' === $organization_email ) {
			return $email;
		}

		$email['to'] = $organization_email;

		return $email;
	}
}

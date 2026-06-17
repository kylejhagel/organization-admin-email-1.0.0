<?php
/**
 * Helper functions for Organization Admin Email.
 *
 * @package OrganizationAdminEmail
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'oae_get_organization_admin_email' ) ) {
	/**
	 * Get the saved Organization Admin Email.
	 *
	 * @return string
	 */
	function oae_get_organization_admin_email(): string {
		$email = get_option( OAE_OPTION_ORGANIZATION_ADMIN_EMAIL, '' );
		$email = is_string( $email ) ? trim( $email ) : '';

		return is_email( $email ) ? $email : '';
	}
}

if ( ! function_exists( 'oae_has_organization_admin_email' ) ) {
	/**
	 * Determine whether the Organization Admin Email is configured.
	 *
	 * @return bool
	 */
	function oae_has_organization_admin_email(): bool {
		return '' !== oae_get_organization_admin_email();
	}
}

if ( ! function_exists( 'oae_get_site_admin_email' ) ) {
	/**
	 * Get the native WordPress Site Admin Email.
	 *
	 * @return string
	 */
	function oae_get_site_admin_email(): string {
		$email = get_option( 'admin_email', '' );
		$email = is_string( $email ) ? trim( $email ) : '';

		return is_email( $email ) ? $email : '';
	}
}

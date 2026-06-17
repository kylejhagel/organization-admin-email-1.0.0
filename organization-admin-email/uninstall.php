<?php
/**
 * Uninstall cleanup for Organization Admin Email.
 *
 * @package OrganizationAdminEmail
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'oae_organization_admin_email' );

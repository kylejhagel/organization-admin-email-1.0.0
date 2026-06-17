<?php
/**
 * General WordPress admin email routing.
 *
 * @package OrganizationAdminEmail
 */

namespace OAE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Email_Router {
	/**
	 * Initialize email routing hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_filter( 'wp_mail', array( __CLASS__, 'maybe_route_admin_email' ), 20, 1 );
	}

	/**
	 * Route native/admin emails addressed to the technical Site Admin Email.
	 *
	 * Guardrail: This only replaces the native Site Admin Email recipient. It does not
	 * reroute messages sent to users or other recipients.
	 *
	 * @param array $args wp_mail arguments.
	 * @return array
	 */
	public static function maybe_route_admin_email( array $args ): array {
		$organization_email = \oae_get_organization_admin_email();
		$site_admin_email   = \oae_get_site_admin_email();

		if ( '' === $organization_email || '' === $site_admin_email ) {
			return $args;
		}

		if ( 0 === strcasecmp( $organization_email, $site_admin_email ) ) {
			return $args;
		}

		if ( empty( $args['to'] ) ) {
			return $args;
		}

		$recipients = self::normalize_recipients( $args['to'] );

		if ( empty( $recipients ) || ! self::recipient_list_contains( $recipients, $site_admin_email ) ) {
			return $args;
		}

		/**
		 * Allows site owners/developers to disable or customize general admin-email routing.
		 *
		 * Recovery mode emails are handled separately by the recovery_mode_email filter.
		 *
		 * @param bool  $should_route       Whether to route the email.
		 * @param array $args               wp_mail arguments.
		 * @param string $organization_email Organization Admin Email.
		 * @param string $site_admin_email   Native WordPress Site Admin Email.
		 */
		$should_route = (bool) apply_filters(
			'oae_should_route_admin_email',
			true,
			$args,
			$organization_email,
			$site_admin_email
		);

		if ( ! $should_route ) {
			return $args;
		}

		$args['to'] = self::replace_recipient( $args['to'], $site_admin_email, $organization_email );

		return $args;
	}

	/**
	 * Normalize wp_mail recipient values into individual email strings.
	 *
	 * @param string|array $to Recipient value.
	 * @return array<int, string>
	 */
	private static function normalize_recipients( $to ): array {
		if ( is_string( $to ) ) {
			$to = explode( ',', $to );
		}

		if ( ! is_array( $to ) ) {
			return array();
		}

		$emails = array();

		foreach ( $to as $recipient ) {
			if ( ! is_string( $recipient ) ) {
				continue;
			}

			$email = self::extract_email_address( $recipient );

			if ( '' !== $email ) {
				$emails[] = $email;
			}
		}

		return $emails;
	}

	/**
	 * Check whether normalized recipients contain an email.
	 *
	 * @param array<int, string> $recipients Recipients.
	 * @param string             $email Email to find.
	 * @return bool
	 */
	private static function recipient_list_contains( array $recipients, string $email ): bool {
		foreach ( $recipients as $recipient ) {
			if ( 0 === strcasecmp( $recipient, $email ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Replace the Site Admin Email recipient while preserving recipient format where practical.
	 *
	 * @param string|array $to Recipient value.
	 * @param string       $site_admin_email Site Admin Email.
	 * @param string       $organization_email Organization Admin Email.
	 * @return string|array
	 */
	private static function replace_recipient( $to, string $site_admin_email, string $organization_email ) {
		if ( is_array( $to ) ) {
			foreach ( $to as $index => $recipient ) {
				if ( ! is_string( $recipient ) ) {
					continue;
				}

				if ( 0 === strcasecmp( self::extract_email_address( $recipient ), $site_admin_email ) ) {
					$to[ $index ] = $organization_email;
				}
			}

			return array_values( array_unique( $to ) );
		}

		if ( is_string( $to ) ) {
			$parts = explode( ',', $to );

			foreach ( $parts as $index => $recipient ) {
				if ( 0 === strcasecmp( self::extract_email_address( $recipient ), $site_admin_email ) ) {
					$parts[ $index ] = $organization_email;
				}
			}

			$parts = array_values( array_unique( array_map( 'trim', $parts ) ) );
			return implode( ', ', $parts );
		}

		return $to;
	}

	/**
	 * Extract an email address from plain or formatted recipient strings.
	 *
	 * Supports values like "admin@example.com" and "Name <admin@example.com>".
	 *
	 * @param string $recipient Recipient string.
	 * @return string
	 */
	private static function extract_email_address( string $recipient ): string {
		$recipient = trim( $recipient );

		if ( preg_match( '/<([^>]+)>/', $recipient, $matches ) ) {
			$recipient = trim( $matches[1] );
		}

		$recipient = sanitize_email( $recipient );

		return is_email( $recipient ) ? $recipient : '';
	}
}

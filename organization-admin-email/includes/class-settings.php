<?php
/**
 * Settings registration and admin UI.
 *
 * @package OrganizationAdminEmail
 */

namespace OAE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Settings {
	/**
	 * Initialize settings hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register the Organization Admin Email setting on Settings > General.
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		register_setting(
			'general',
			OAE_OPTION_ORGANIZATION_ADMIN_EMAIL,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( __CLASS__, 'sanitize_email_setting' ),
				'default'           => '',
				'show_in_rest'      => false,
			)
		);

		add_settings_field(
			OAE_OPTION_ORGANIZATION_ADMIN_EMAIL,
			esc_html__( 'Organization Admin Email', 'organization-admin-email' ),
			array( __CLASS__, 'render_email_field' ),
			'general',
			'default',
			array(
				'label_for' => OAE_OPTION_ORGANIZATION_ADMIN_EMAIL,
			)
		);
	}

	/**
	 * Sanitize and validate the setting value.
	 *
	 * @param mixed $value Raw submitted value.
	 * @return string
	 */
	public static function sanitize_email_setting( $value ): string {
		$value = is_string( $value ) ? sanitize_email( trim( $value ) ) : '';

		if ( '' === $value ) {
			return '';
		}

		if ( ! is_email( $value ) ) {
			add_settings_error(
				OAE_OPTION_ORGANIZATION_ADMIN_EMAIL,
				'oae_invalid_email',
				esc_html__( 'Organization Admin Email must be a valid email address.', 'organization-admin-email' ),
				'error'
			);

			$previous = get_option( OAE_OPTION_ORGANIZATION_ADMIN_EMAIL, '' );
			return is_string( $previous ) ? $previous : '';
		}

		return $value;
	}

	/**
	 * Render the email setting field.
	 *
	 * @return void
	 */
	public static function render_email_field(): void {
		$value            = \oae_get_organization_admin_email();
		$site_admin_email = \oae_get_site_admin_email();
		?>
		<div class="oae-field-wrap">
			<input
				type="email"
				class="regular-text ltr"
				id="<?php echo esc_attr( OAE_OPTION_ORGANIZATION_ADMIN_EMAIL ); ?>"
				name="<?php echo esc_attr( OAE_OPTION_ORGANIZATION_ADMIN_EMAIL ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				placeholder="<?php echo esc_attr( $site_admin_email ); ?>"
				autocomplete="email"
			/>

			<p class="description oae-field-description">
				<?php esc_html_e( 'Receives organization-facing WordPress administrative notifications, including recovery mode and fatal error alerts. This does not replace the WordPress Site Admin Email.', 'organization-admin-email' ); ?>
			</p>

			<p class="description oae-inline-note">
				<strong><?php esc_html_e( 'Technical Site Admin Email:', 'organization-admin-email' ); ?></strong>
				<code><?php echo esc_html( $site_admin_email ); ?></code>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue admin CSS only on Settings > General.
	 *
	 * @param string $hook_suffix Admin page hook suffix.
	 * @return void
	 */
	public static function enqueue_admin_assets( string $hook_suffix ): void {
		if ( 'options-general.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'oae-admin',
			OAE_PLUGIN_URL . 'assets/admin/css/admin.css',
			array(),
			OAE_VERSION
		);
	}
}

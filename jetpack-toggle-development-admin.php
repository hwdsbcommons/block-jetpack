<?php
/**
 * Jetpack Toggle Development Mode Admin.
 *
 * @package HWDSB_JP
 */

/**
 * Admin class for Jetpack Toggle Development Mode.
 */
class HWDSB_JP_Admin {
	/**
	 * Initializer.
	 */
	public static function init() {
		return new self;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		if ( false === $this->enable_field() ) {
			// Remove the Jetpack main menu while we're at it!
			remove_submenu_page( 'jetpack', 'jetpack' );
			return;
		}

		register_setting( 'general', 'hwdsb_use_jetpack', array( $this, 'validate' ) );
		add_settings_field(
			'hwdsb-use-jetpack',
			__( 'Jetpack', 'hwdsb-jp' ),
			array( $this, 'display_field' ),
			'general'
		);
	}

	/**
	 * Validate our option.
	 */
	public function validate( $input ) {
		return intval( $input );
	}

	/**
	 * Display our field.
	 */
	public function display_field() {
		$setting = (int) get_option( 'hwdsb_use_jetpack' );
	?>

		<label for="hwdsb-use-jetpack">
			<input type="checkbox" <?php checked( $setting, 1 ); ?> value="1" id="hwdsb-use-jetpack" name="hwdsb_use_jetpack">
			<?php _e( 'Enable WordPress.com Jetpack functionality?' ); ?>
		</label>

	<?php
	}

	/**
	 * Helper method to determine if we should see this admin field.
	 */
	protected function enable_field() {
		if ( is_super_admin() ) {
			return true;
		}

		// If username contains numbers, this is a student, so bail!
		$user = wp_get_current_user();
		if ( 1 === preg_match( '#\d#', $user->user_login ) ) {
			return false;
		} else {
			return true;
		}
	}
}
<?php
/**
 * Admin Class for admin menu page to show API key to the admin
 *
 * @package WAC\Includes
 * @since 1.0.0
 */

namespace CKN\Includes;

use CKN\Includes\Traits\Get_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class for admin menu page to show API key to the admin
 *
 * @package WAC\Includes
 * @since 1.0.0
 */
class Admin {
	use Get_Instance;

	/**
	 * Admin constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
	}

	/**
	 * Register admin menu
	 *
	 * @access public
	 * @hooked admin_menu
	 * @since 1.0.0
	 * @return void
	 * */
	public function register_admin_menu() {

		// Check if user has permission.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Add menu page to the admin menu to show API key.
		add_menu_page( __( 'Cool Kids API', 'ckn' ), __( 'Cool Kids API', 'ckn' ), 'manage_options', 'ckn-api-settings', array( $this, 'admin_api_settings' ) );
	}

	/**
	 * Admin API settings page.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 * */
	public function admin_api_settings() {
		// Regenerate API key if the form is submitted and action is regenerate_api_key.
		if ( ! empty( $_POST['action'] ) && 'regenerate_api_key' === $_POST['action'] && wp_verify_nonce( $_POST['_wpnonce'], 'ckn_regenerate_api_key' ) ) {
			// Generate a random 32 character long API key.
			// If the API key is not set, generate a new one.
			$new_api_key = wp_generate_password( 32, true );
			// Update the API key in the database.
			update_option( 'ckn_api_key', $new_api_key );

			Logger::log( 'New API key generated at ' . gmdate( 'Y-m-d H:i:s' ) );
		} else {
			// Get the API key from the database.
			$new_api_key = get_option( 'ckn_api_key' );
		}

		// Render the API settings page.
		echo wp_kses( $this->render_api_settings_page( $new_api_key ), array(
				'div' => array( 'class' => array() ),
				'h2' => array(),
				'p' => array(),
				'code' => array( 'style' => array() ),
				'form' => array( 'method' => array(), 'action' => array(), 'style' => array() ),
				'button' => array( 'type' => array(), 'class' => array() ),
				'wp_nonce_field' => array( 'ckn_regenerate_api_key', '_wpnonce' ),
				'input' => array( 'type' => array(), 'name' => array(), 'value' => array(), 'style' => array( 'border', 'box-shadow', 'width', 'text-align', 'color' ), 'disabled' => array() ),
			)
		);
	}

	/**
	 * Render API settings page.
	 *
	 * @access private
	 * @since 1.0.0
	 * @param string $api_key API key.
	 * @return string Rendered HTML.
	 */
	private function render_api_settings_page( $api_key ) {
		return '<div class="wrap">
                    <h2>' . esc_html__( 'Cool Kids API', 'ckn' ) . '</h2>
                    <h2>' . esc_html__( 'API Key', 'ckn' ) . '</h2>
                    <p>
                        ' . esc_html__( 'Your API Key:', 'ckn' ) . '
                        <input 
                            style="border: none; box-shadow: none; width: 330px; text-align: center; color: black;"
                            disabled 
                            value="' . esc_html( $api_key ) . '">
                        </input>
                    </p>
                    ' . $this->generate_regenerate_button_form() . '
                    <h2>' . esc_html__( 'API Endpoints', 'ckn' ) . '</h2>
                    <p>' . esc_html__( 'Change Role: ', 'ckn' ) . '<code style="background: white;"><span style="color: red;">POST</span> /wp-json/ckn/v1/change-role</code></p>
                </div>';
	}

	/**
	 * Generate new API key button form.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return string Rendered HTML.
	 */
	private function generate_regenerate_button_form() {
		return '<form method="post" action="" style="margin-bottom: 20px;">' .
					wp_nonce_field( 'ckn_regenerate_api_key', '_wpnonce' )
                    . '<input type="hidden" name="action" value="regenerate_api_key">
                    <button type="submit" class="button button-primary">' . esc_html__( 'Generate new API Key', 'ckn' ) . '</button>
                </form>';
	}
}

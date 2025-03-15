<?php
/**
 * User Management Class for handling user registration and login
 *
 * @package CKN\Includes
 * @since 1.0.0
 */

namespace CKN\Includes;

use CKN\Includes\Traits\Get_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User Management Class for handling user registration and login
 *
 * @package CKN\Includes
 * @since 1.0.0
 */
class User_Management {
	use Get_Instance;

	/**
	 * User_Management constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		// AJAX actions for login and signup nopriv is used to allow non-logged in users to access the endpoint.
		add_action( 'wp_ajax_nopriv_ckn_signup', array( $this, 'ajax_signup' ) );
		add_action( 'wp_ajax_nopriv_ckn_login', array( $this, 'ajax_login' ) );
		add_action( 'wp_ajax_ckn_logout', array( $this, 'ajax_logout' ) );
	}

	/**
	 * AJAX handler for user signup
	 *
	 * @access public
	 * @hooked wp_ajax_nopriv_ckn_signup
	 * @since 1.0.0
	 * @return void
	 * @throws \Exception If response is not successful.
	 */
	public function ajax_signup() {
		check_ajax_referer( 'ckn-ajax-nonce', 'security' );

		$response = array();

		try {
			$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

			// Validate email.
			if ( empty( $email ) || ! is_email( $email ) ) {
				throw new \Exception( 'Invalid email address.' );
			}

			// Check if email already exists.
			if ( email_exists( $email ) ) {
				throw new \Exception( 'Email already registered.' );
			}

			// Generate a random username.
			$username = 'user_' . wp_generate_password( 8, false );

			// Generate a random password.
			$password = wp_generate_password( 12, false );

			// Generate character data using randomuser.me API.
			$character = $this->generate_character( $email );

			// Create user.
			$user_id = wp_create_user( $username, $password, $email );

			if ( is_wp_error( $user_id ) ) {
				throw new \Exception( $user_id->get_error_message() );
			}

			// Set default role.
			$user = new \WP_User( $user_id );
			$user->set_role( 'cool_kid' );

			// Save character data as user meta.
			update_user_meta( $user_id, 'first_name', $character['first_name'] );
			update_user_meta( $user_id, 'last_name', $character['last_name'] );
			update_user_meta( $user_id, 'country', $character['country'] );

			// Log the signup.
			Logger::log( 'User registered: ' . $email );

			// Log the user in.
			wp_set_auth_cookie( $user_id, true );

			$response['success']  = true;
			$response['message']  = 'Registration successful!';
			$response['redirect'] = site_url();
		} catch ( \Exception $e ) {
			Logger::log( 'Signup error: ' . $e->getMessage(), 'error' );

			$response['success'] = false;
			$response['message'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Handle user login via AJAX
	 *
	 * @access public
	 * @hooked wp_ajax_nopriv_ckn_login
	 * @since 1.0.0
	 * @return void
	 * @throws \Exception If response is not successful.
	 */
	public function ajax_login() {
		check_ajax_referer( 'ckn-ajax-nonce', 'security' );

		$response = array();

		try {
			$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

			// Validate email.
			if ( empty( $email ) || ! is_email( $email ) ) {
				throw new \Exception( 'Invalid email address.' );
			}

			// Get user by email.
			$user = get_user_by( 'email', $email );

			if ( ! $user ) {
				throw new \Exception( 'User not found.' );
			}

			// if user role is other than cool_kid, cooler_kid or coolest_kid, throw an exception.
			// it will prevent from loggin in as admin.
			if ( ! in_array( $user->roles[0], array( 'cool_kid', 'cooler_kid', 'coolest_kid' ), true ) ) {
				throw new \Exception( 'Invalid user role.' );
			}

			// Log the user in.
			wp_set_auth_cookie( $user->ID, true );

			Logger::log( 'User logged in: ' . $email );

			$response['success']  = true;
			$response['message']  = 'Login successful!';
			$response['redirect'] = site_url();
		} catch ( \Exception $e ) {
			Logger::log( 'Login error: ' . $e->getMessage(), 'error' );

			$response['success'] = false;
			$response['message'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Handle user logout via AJAX
	 *
	 * @access public
	 * @hooked wp_ajax_ckn_logout
	 * @since 1.0.0
	 * @return void
	 */
	public function ajax_logout() {
		check_ajax_referer( 'ckn-ajax-nonce', 'security' );

		$response = array();

		try {
			wp_logout();

			$response['success']  = true;
			$response['message']  = 'Logout successful!';
			$response['redirect'] = site_url();
		} catch ( \Exception $e ) {
			Logger::log( 'Logout error: ' . $e->getMessage(), 'error' );

			$response['success'] = false;
			$response['message'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Generate character data using randomuser.me API
	 *
	 * @access private
	 * @since 1.0.0
	 * @param string $email email address.
	 * @return array
	 * @throws \Exception If response is not successful.
	 */
	private function generate_character( $email ) {
		try {
			// Call randomuser.me API.
			$response = wp_remote_get( 'https://randomuser.me/api/' );

			if ( is_wp_error( $response ) ) {
				throw new \Exception( $response->get_error_message() );
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( empty( $data['results'][0] ) ) {
				throw new \Exception( 'Failed to generate character data.' );
			}

			$user_data = $data['results'][0];

			return array(
				'first_name' => $user_data['name']['first'],
				'last_name'  => $user_data['name']['last'],
				'country'    => $user_data['location']['country'],
				'email'      => $email,
			);
		} catch ( \Exception $e ) {
			Logger::log( 'Character generation error: ' . $e->getMessage(), 'error' );

			// Fallback to random data if API fails.
			return array(
				'first_name' => 'User' . wp_rand( 1000, 9999 ),
				'last_name'  => 'Character' . wp_rand( 1000, 9999 ),
				'country'    => 'Unknown',
				'email'      => $email,
			);
		}
	}
}

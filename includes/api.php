<?php
/**
 * API Class for handling REST API endpoints.
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
 * API Class for handling REST API endpoints
 *
 * @package CKN\Includes
 * @since 1.0.0
 */
class API {
	use Get_Instance;

	/**
	 * API constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
	}

	/**
	 * Register API endpoints
	 *
	 * @access public
	 * @hooked rest_api_init
	 * @since 1.0.0
	 * @return void
	 */
	public function register_api_endpoints() {
		// Register API endpoint for changing user role.
		register_rest_route(
			'ckn/v1',
			'/change-role',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'change_user_role' ),
				'permission_callback' => array( $this, 'verify_api_request' ),
			)
		);
	}

	/**
	 * Verify API request for authentication
	 *
	 * @access public
	 * @since 1.0.0
	 * @return bool
	 */
	public function verify_api_request() {
		// get the API key from the request.
		$request_key = isset( $_SERVER['HTTP_X_API_KEY'] ) ? sanitize_text_field( $_SERVER['HTTP_X_API_KEY'] ) : '';

		// Check if API key is present and matches the stored API key.
		if ( ! $request_key ) {
			Logger::log( 'API key missing in the request', 'error' );
			return false;
		}

		$api_key = get_option( 'ckn_api_key' );

		if ( $request_key !== $api_key ) {
			Logger::log( 'Invalid API key: ' . $request_key, 'error' );
			return false;
		}

		return true;
	}

	/**
	 * Change user role callback
	 *
	 * @access public
	 * @param \WP_REST_Request $request Request object.
	 * @since 1.0.0
	 * @return array|\WP_Error
	 */
	public function change_user_role( $request ) {
		try {
			$params = $request->get_params();

			// if role is not provided throw error.
			if ( empty( $params['role'] ) ) {
				return new \WP_Error( 'missing_role', 'Role parameter is required', array( 'status' => 400 ) );
			}

			// Validate if the role is valid - cool_kid, cooler_kid, coolest_kid.
			$valid_roles = array( 'cool_kid', 'cooler_kid', 'coolest_kid' );
			if ( ! in_array( $params['role'], $valid_roles, true ) ) {
				return new \WP_Error( 'invalid_role', 'Invalid role. Allowed roles: ' . implode( ', ', $valid_roles ), array( 'status' => 400 ) );
			}

			$user = null;

			// Find user by email.
			if ( ! empty( $params['email'] ) ) {
				$user = get_user_by( 'email', sanitize_email( $params['email'] ) );
			} elseif ( ! empty( $params['first_name'] ) && ! empty( $params['last_name'] ) ) {
				$users = get_users(
					array(
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'     => 'first_name',
								'value'   => sanitize_text_field( $params['first_name'] ),
								'compare' => '=',
							),
							array(
								'key'     => 'last_name',
								'value'   => sanitize_text_field( $params['last_name'] ),
								'compare' => '=',
							),
						),
					)
				);

				// If multiple users found, return the first one.
				if ( ! empty( $users ) ) {
					$user = $users[0];
				}
			}

			// If user not found, return error.
			if ( ! $user ) {
				return new \WP_Error( 'user_not_found', 'User not found', array( 'status' => 404 ) );
			}

			// if user has role other than cool_kid, cooler_kid, coolest_kid, return error.
			if ( ! in_array( $user->roles[0], $valid_roles, true ) ) {
				return new \WP_Error( 'invalid_role', 'User already has a role other than cool_kid, cooler_kid, coolest_kid. Cannot update user role.', array( 'status' => 400 ) );
			}

			// Check if the user already has the requested role.
			if ( $user->has_cap( $params['role'] ) ) {
				return new \WP_Error( 'already_has_role', 'User already has the requested role', array( 'status' => 400 ) );
			}

			// Fianlly, update the user role.
			$user->set_role( $params['role'] );

			// Log the role change.
			Logger::log( 'User role updated via API: User ID ' . $user->ID . ' set to ' . $params['role'] );

			return array(
				'success' => true,
				'message' => 'User role updated successfully',
				'user_id' => $user->ID,
				'role'    => $params['role'],
			);
		} catch ( \Exception $e ) {
			Logger::log( 'API error: ' . $e->getMessage(), 'error' );
			return new \WP_Error( 'api_error', $e->getMessage(), array( 'status' => 500 ) );
		}
	}
}

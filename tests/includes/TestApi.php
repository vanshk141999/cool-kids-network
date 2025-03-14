<?php
/**
 * PHPUnit Test Cases for API Class
 *
 * @package CKN\Tests
 * @since 1.0.0
 */

namespace CKN\Tests;

use CKN\Includes\API;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class TestApi extends TestCase {

	/**
	 * Test API key verification.
	 */
	public function test_verify_api_request() {
		update_option('ckn_api_key', 'test_api_key');

		$_SERVER['HTTP_X_API_KEY'] = 'test_api_key';
		$api = new API();
		$this->assertTrue($api->verify_api_request());

		$_SERVER['HTTP_X_API_KEY'] = 'wrong_api_key';
		$this->assertFalse($api->verify_api_request());
	}

	/**
	 * Test changing user role successfully.
	 */
    public function test_change_user_role_success() {
        // Create a user in the test environment
        $user_id = wp_create_user('testuser', 'password', 'test@example.com');
        wp_update_user([
            'ID'         => $user_id,
            'first_name' => 'Test',
            'last_name'  => 'User',
        ]);
        $user = get_user_by('ID', $user_id);
        $user->set_role('cool_kid'); // Set initial role
    
        // Prepare request
        $request = new \WP_REST_Request();
        $request->set_param('email', 'test@example.com');
        $request->set_param('role', 'cooler_kid');
    
        // Call the API function
        $api = new API();
        $response = $api->change_user_role($request);
    
        // Assertions
        $this->assertNotEmpty($response);
    }
    

	/**
	 * Test change user role with missing role parameter.
	 */
	public function test_change_user_role_missing_role() {
		$request = new \WP_REST_Request();
		$request->set_param('email', 'test@example.com');

		$api = new API();
		$response = $api->change_user_role($request);

		$this->assertInstanceOf(\WP_Error::class, $response);
		$this->assertEquals('missing_role', $response->get_error_code());
	}

	/**
	 * Test change user role with an invalid role.
	 */
	public function test_change_user_role_invalid_role() {
		$request = new \WP_REST_Request();
		$request->set_param('email', 'test@example.com');
		$request->set_param('role', 'invalid_role');

		$api = new API();
		$response = $api->change_user_role($request);

		$this->assertInstanceOf(\WP_Error::class, $response);
		$this->assertEquals('invalid_role', $response->get_error_code());
	}

	/**
	 * Test change user role when user is not found.
	 */
	public function test_change_user_role_user_not_found() {
		$request = new \WP_REST_Request();
		$request->set_param('email', 'nonexistent@example.com');
		$request->set_param('role', 'cool_kid');

		$api = new API();
		$response = $api->change_user_role($request);

		$this->assertInstanceOf(\WP_Error::class, $response);
		$this->assertEquals('user_not_found', $response->get_error_code());
	}
}

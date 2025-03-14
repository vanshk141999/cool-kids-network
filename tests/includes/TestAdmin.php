<?php
/**
 * PHPUnit test case for the Admin class.
 */

namespace CKN\Tests;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use CKN\Includes\Admin;

class TestAdmin extends TestCase {
    
    /**
     * Test that the Admin class instantiates correctly.
     */
    public function test_admin_class_instantiation() {
        $admin = new Admin();
        $this->assertInstanceOf(Admin::class, $admin);
    }

    /**
     * Test that regenerating the API key updates the option in the database.
     */
    public function test_api_key_regeneration() {
        $_POST['action'] = 'regenerate_api_key';
        $_POST['_wpnonce'] = wp_create_nonce('ckn_regenerate_api_key');

        $admin = new Admin();
        
        // Store old API key
        $old_api_key = get_option('ckn_api_key');
        
        $admin->admin_api_settings();

        // Get new API key
        $new_api_key = get_option('ckn_api_key');
        
        $this->assertNotEmpty($new_api_key);
        $this->assertNotEquals($old_api_key, $new_api_key);
    }
}

<?php
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use CKN\Includes\User_Management;

class TestUserManagement extends TestCase {

    public function testRegisterRoles() {
        $userManagement = new User_Management();
        $this->assertNull($userManagement->register_roles());
    }
    
    public function testAjaxSignupWithInvalidEmail() {
        $_POST['email'] = 'invalid-email';
        
        $userManagement = new User_Management();
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid email address.');
        
        $userManagement->ajax_signup();
    }

    public function testAjaxLoginWithNonExistentUser() {
        $_POST['email'] = 'user@example.com';
        
        $userManagement = new User_Management();
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found.');
        
        $userManagement->ajax_login();
    }
}

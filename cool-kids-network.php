<?php
/**
 * Plugin Name: Cool Kids Network
 * Description: Cool Kids Network is a WordPress plugin for user registration, role-based access control, and character management.
 * Plugin URI: https://vansh-kapoor.vercel.app/
 * Version: 1.0.0
 * Author: Vansh Kapoor
 * Author URI: https://vansh-kapoor.vercel.app/
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: ckn
 *
 * @package Cool_Kids_Network
 */

if ( ! defined( constant_name: 'ABSPATH' ) ) {
	exit;
}

define( 'CKN_VERSION', '1.0.0' );
define( 'CKN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CKN_ASSETS', plugin_dir_url( __FILE__ ) . 'assets' );

require_once CKN_PATH . 'plugin-loader.php';

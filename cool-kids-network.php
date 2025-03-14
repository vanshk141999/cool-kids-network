<?php
/**
 * Plugin Name: Cool Kids Network
 * Description: The Cool Kids Network plugin brings a unique user management system to your WordPress site. It allows users to sign up and automatically receive a fun, randomly generated character with a name, country, and role.
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

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
 * License: GPLv3
 * Text Domain: ckn
 *
 * @package CKN
 */

if ( ! defined( constant_name: 'ABSPATH' ) ) {
	exit;
}

define( 'CKN_VERSION', '1.0.0' );
define( 'CKN_FILE', __FILE__ );
define( 'CKN_PATH', plugin_dir_path( CKN_FILE ) );
define( 'CKN_ASSETS', plugin_dir_url( CKN_FILE ) . 'assets' );

require_once CKN_PATH . 'plugin-loader.php';

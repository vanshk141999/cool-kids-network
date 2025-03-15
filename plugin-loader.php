<?php
/**
 * Plugin Loader.
 *
 * @package CKN
 * @since 1.0.0
 */

namespace CKN;

use CKN\Includes\Admin;
use CKN\Includes\API;
use CKN\Includes\Frontend;
use CKN\Includes\User_Management;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Loader.
 *
 * @package Cool_Kids_Network
 * @since 1.0.0
 */
class Plugin_Loader {
	/**
	 * Instance object.
	 *
	 * @access private
	 * @var object Class Instance.
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Plugin loader constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * This function is added to load all classes this function will be called
		 * when PHP encounters a class that hasn't been loaded.
		 */
		spl_autoload_register( array( $this, 'autoload' ) );
		add_action( 'init', array( $this, 'load_core_files' ) );
	}

	/**
	 * Get instance function to create class instance only once.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Autoload classes.
	 *
	 * @access public
	 * @param string $classname class name.
	 * @since 1.0.0
	 * @return void
	 */
	public function autoload( $classname ) {
		// if the class is not in the plugin namespace, return.
		if ( 0 !== strpos( $classname, __NAMESPACE__ ) ) {
			return;
		}

		// convert the class name to a file name.
		$filename = preg_replace(
			array( '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ),
			array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ),
			$classname
		);

		// if the filename is a string then include it.
		if ( is_string( $filename ) ) {
			$filename = strtolower( $filename );
			$file     = __DIR__ . '/' . $filename . '.php';
			if ( is_readable( $file ) ) {
				require_once $file;
			}
		}
	}

	/**
	 * Initialize core classes for the frontend and admin areas.
	 *
	 * @access public
	 * @hooked init
	 * @since 1.0.0
	 * @return void
	 */
	public function load_core_files() {
		if ( is_admin() ) {
			Admin::get_instance();
		}
		API::get_instance();
		Frontend::get_instance();
		User_Management::get_instance();
	}
}

/**
 * Initializes the main plugin.
 */
Plugin_Loader::get_instance();

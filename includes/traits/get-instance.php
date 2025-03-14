<?php
/**
 * Get Instance Trait for each class to create the instance only once.
 *
 * @package CKN\Includes\Traits
 * @since 1.0.0
 */

namespace CKN\Includes\Traits;

if ( ! defined( constant_name: 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Instance Trait for each class to create the instance only once.
 *
 * @package CKN\Includes\Traits
 * @since 1.0.0
 */
trait Get_Instance {
	/**
	 * Instance object.
	 *
	 * @access private
	 * @var object Class Instance.
	 * @since 1.0.0
	 */
	private static $instance = null;

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
}

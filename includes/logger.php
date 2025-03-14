<?php
/**
 * Logger Class debugging
 *
 * @package CKN\Includes
 * @since 1.0.0
 */

namespace CKN\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logger Class debugging
 *
 * @package CKN\Includes
 * @since 1.0.0
 */
class Logger {

	/**
	 * Log a message to the log file.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param string $message message to log.
	 * @param string $level log level.
	 * @return void
	 */
	public static function log( $message, $level = 'info' ) {
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// get the upload directory.
		$upload_dir = wp_upload_dir();
		$log_dir    = $upload_dir['basedir'] . '/ckn-logs';

		// create the log directory if it doesn't exist.
		if ( ! file_exists( $log_dir ) ) {
			wp_mkdir_p( $log_dir );
		}

		// log the message to the log file.
		$log_file  = $log_dir . '/ckn-' . gmdate( 'Y-m-d' ) . '.log';
		$timestamp = gmdate( 'Y-m-d H:i:s' );
		$log_entry = "[$timestamp] [$level] $message" . PHP_EOL;

		$wp_filesystem->put_contents( $log_file, $log_entry, FILE_APPEND );
	}
}

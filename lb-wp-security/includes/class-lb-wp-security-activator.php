<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LB_WP_Security
 * @subpackage LB_WP_Security/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    LB_WP_Security
 * @subpackage LB_WP_Security/includes
 * @author     Your Name <email@example.com>
 */
class LB_WP_Security_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/* Create database tables */
		global $wpdb;

    $table_name = $wpdb->prefix . "littlebonsai_failed_logins";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
			ip tinytext NOT NULL,
			user_agent text NOT NULL,
			seen_count mediumint(9) DEFAULT '1' NOT NULL,
		  first_seen timestamp DEFAULT 0,
			last_seen timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
			reported boolean DEFAULT False NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

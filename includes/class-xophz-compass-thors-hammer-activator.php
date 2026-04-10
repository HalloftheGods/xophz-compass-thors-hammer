<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/includes
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Thors_Hammer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	    if ( !class_exists( 'Xophz_Compass' ) ) {  
	    	die('This plugin requires COMPASS to be active.</a></div>');
	    }

		global $wpdb;
		$table_name = $wpdb->prefix . 'xophz_thors_hammer_bans';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			type varchar(20) NOT NULL COMMENT 'ip, email, user_id',
			value varchar(255) NOT NULL,
			reason text DEFAULT '' NOT NULL,
			expires_at datetime DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY type (type),
			KEY value (value)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

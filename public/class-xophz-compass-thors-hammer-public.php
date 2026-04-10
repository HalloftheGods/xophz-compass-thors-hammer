<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/public
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Thors_Hammer_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Thors_Hammer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Thors_Hammer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/xophz-compass-thors-hammer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Thors_Hammer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Thors_Hammer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xophz-compass-thors-hammer-public.js', array( 'jquery' ), $this->version, false );

	}

	public function check_for_bans() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'xophz_thors_hammer_bans';
		
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xophz-compass-thors-hammer-activator.php';
			Xophz_Compass_Thors_Hammer_Activator::activate();
		}
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_id = get_current_user_id();
		$email = '';
		if ( $user_id ) {
			$user = get_userdata( $user_id );
			$email = $user->user_email;
		}

		$query = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE 
			(type = 'ip' AND value = %s) OR 
			(type = 'user_id' AND value = %d) OR 
			(type = 'email' AND value = %s)",
			$ip,
			$user_id,
			$email
		);

		$bans = $wpdb->get_results( $query );

		if ( ! empty( $bans ) ) {
			foreach ( $bans as $ban ) {
				// Check expiration
				if ( ! empty( $ban->expires_at ) && strtotime( $ban->expires_at ) < time() ) {
					continue;
				}
				
				$reason = ! empty( $ban->reason ) ? $ban->reason : 'Violation of terms.';
				wp_die( "<h1>SMITED!</h1><p>You have been banned by Thor's Hammer.</p><p><strong>Reason:</strong> " . esc_html( $reason ) . "</p>", "Banned", array( 'response' => 403 ) );
			}
		}
	}

}

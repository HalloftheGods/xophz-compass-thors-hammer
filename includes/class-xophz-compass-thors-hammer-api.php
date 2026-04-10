<?php

/**
 * The API functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/includes
 */

/**
 * The API functionality of the plugin.
 *
 * Defines the plugin name, version, and api hooks
 *
 * @package    Xophz_Compass_Thors_Hammer
 * @subpackage Xophz_Compass_Thors_Hammer/includes
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Thors_Hammer_API {

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

    private $table_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        global $wpdb;
        $this->table_name = $wpdb->prefix . 'xophz_thors_hammer_bans';

	}

    public function register_routes() {
        register_rest_route( 'xophz-compass/v1', '/thors-hammer/bans', array(
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_bans' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            ),
            array(
                'methods' => 'POST',
                'callback' => array( $this, 'add_ban' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            ),
        ) );

        register_rest_route( 'xophz-compass/v1', '/thors-hammer/bans/(?P<id>\d+)', array(
            array(
                'methods' => 'DELETE',
                'callback' => array( $this, 'delete_ban' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            ),
        ) );
    }

    public function permissions_check() {
        return current_user_can( 'manage_options' );
    }

    public function get_bans( $request ) {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM {$this->table_name} ORDER BY created_at DESC" );
        return new WP_REST_Response( $results, 200 );
    }

    public function add_ban( $request ) {
        global $wpdb;
        $params = $request->get_json_params();
        
        $type = sanitize_text_field( $params['type'] );
        $value = sanitize_text_field( $params['value'] );
        $reason = sanitize_textarea_field( $params['reason'] );
        $expires_at = isset($params['expires_at']) ? sanitize_text_field($params['expires_at']) : null;

        $wpdb->insert(
            $this->table_name,
            array(
                'type' => $type,
                'value' => $value,
                'reason' => $reason,
                'expires_at' => $expires_at,
            )
        );

        return new WP_REST_Response( array( 'id' => $wpdb->insert_id ), 201 );
    }

    public function delete_ban( $request ) {
        global $wpdb;
        $id = $request['id'];
        
        $wpdb->delete(
            $this->table_name,
            array( 'id' => $id )
        );

        return new WP_REST_Response( array( 'deleted' => true ), 200 );
    }

}

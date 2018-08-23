<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.0.1
 *
 * @package    LB-WP-Security
 * @subpackage LB-WP-Security/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LB-WP-Security
 * @subpackage LB-WP-Security/admin
 * @author     Your Name <email@example.com>
 */
class LB_WP_Security_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $lb_wp_security    The ID of this plugin.
	 */
	private $lb_wp_security;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $lb_wp_security       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lb_wp_security, $version ) {

		$this->lb_wp_security = $lb_wp_security;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LB-WP-Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LB-WP-Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lb_wp_security, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LB-WP-Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LB-WP-Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lb_wp_security, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	function login_failed($username) {
		global $wpdb;

		/* Collect information on source of login */
	  $ip = $_SERVER['REMOTE_ADDR'];
	  $user_agent = $_SERVER['HTTP_USER_AGENT'];

		$table_name = $wpdb->prefix . "littlebonsai_failed_logins";
		$results = $wpdb->get_results("SELECT id, seen_count, reported FROM $table_name WHERE ip='$ip' AND user_agent='$user_agent'");

		if (sizeof($results) == 0) {
			$wpdb->insert(
				$table_name,
				array(
					'ip' => $ip,
					'user_agent' => $user_agent,
					'first_seen' => current_time('mysql')
				)
			);
		} else {
			$ip_id = $results[0]->id;
			$seen_count = $results[0]->seen_count;
			$seen_count_new = $seen_count + 1;
			$reported = $results[0]->reported;

			/* Update seen count */
			$wpdb->update(
				$table_name,
				array('seen_count' => $seen_count_new),
				array('id' => $ip_id ),
				array('%d'),
				array('%d')
			);

			/* If there are more than two failed login send alert */
			if ($seen_count_new > 2) {
				if ($reported == False) {
					$file_path = WP_PLUGIN_DIR . "/lb-wp-security/api.key";
				  $myfile = fopen($file_path, "r") or die("Error reading api key.");
				  $api_key = trim(fread($myfile,filesize($file_path)));
				  fclose($myfile);

					$url = 'https://littlebonsai.co/api/v0.3/add_blacklist_ip.php';
				  $data = array('ip' => $ip, 'user_agent' => $user_agent, 'comment' => 'WordPress Login Brute-forcing', 'tags' => 'malicious-login', 'ref_url' => '');

				  $options = array(
				      'http' => array(
				          'method'  => 'POST',
				          'content' => http_build_query($data),
				          'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
				                       "Accept: application/json\r\n" .
				                       "Auth: $api_key\r\n"
				      )
				  );

				  $context  = stream_context_create($options);
				  $result = file_get_contents($url, false, $context);
				  if ($result === FALSE) {
						echo ("Error adm-inc-01");
					} else {
						/* Change status to reported */
						$wpdb->update(
							$table_name,
							array('reported' => 1),
							array('id' => $ip_id ),
							array('%d'),
							array('%d')
						);
					}
				}
			}
		}
	}

	function login_successful($user_login, $user) {
		global $wpdb;

		/* Collect information on source of login */
	  $ip = $_SERVER['REMOTE_ADDR'];
	  $user_agent = $_SERVER['HTTP_USER_AGENT'];

		/* Add successful login info to table */
		$table_name = $wpdb->prefix . "littlebonsai_successful_logins";

		$wpdb->insert(
			$table_name,
			array(
				'ip' => $ip,
				'user' => $user_login,
				'user_agent' => $user_agent
			)
		);
	}

}

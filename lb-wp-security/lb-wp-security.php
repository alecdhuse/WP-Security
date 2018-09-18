<?php
/*
* Little Bonsai WP Security
*
* @link              https://littlebonsai.co
* @since             0.0.1
* @package           lb-wp-security
*
* @wordpress-plugin
* Plugin Name:       Little Bonsai WP Security
* Plugin URI:        https://littlebonsai.co
* Description:       Basic security features for WordPress.
* Version:           0.0.2
* Author:            Little Bonsai
* Author URI:        https://littlebonsai.co
* License:           TBD
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       lb-wp-security
* Domain Path:       /languages
*/

defined('ABSPATH') or die('Direct access is not allowed.');

/**
 * Currently plugin version.
 * Start at version 0.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LB-WP-SECURITY', '0.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lb-wp-security-activator.php
 */
function activate_lb_wp_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lb-wp-security-activator.php';
	LB_WP_Security_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lb-wp-security-deactivator.php
 */
function deactivate_lb_wp_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lb-wp-security-deactivator.php';
	LB_WP_Security_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lb_wp_security' );
register_deactivation_hook( __FILE__, 'deactivate_lb_wp_security' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lb-wp-security.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_lb_wp_security() {
	$plugin = new LB_WP_Security();
	$plugin->run();
}

run_lb_wp_security();

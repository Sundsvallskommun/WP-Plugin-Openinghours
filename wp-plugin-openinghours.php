<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://cybercom.com
 * @since             1.0.0
 * @package           Wp_Plugin_Openinghours
 *
 * @wordpress-plugin
 * Plugin Name:       Sundsvall kommun - Öppettider
 * Plugin URI:        https://github.com/Sundsvallskommun/WP-Plugin-OpeningHours
 * Description:       This plugin lets you set opening hours on places you add via admin.
 * Version:           1.0.0
 * Author:            Andreas Färnstrand
 * Author URI:        http://cybercom.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-plugin-openinghours
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-plugin-openinghours-activator.php
 */
function activate_wp_plugin_openinghours() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-plugin-openinghours-activator.php';
	Wp_Plugin_Openinghours_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-plugin-openinghours-deactivator.php
 */
function deactivate_wp_plugin_openinghours() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-plugin-openinghours-deactivator.php';
	Wp_Plugin_Openinghours_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_plugin_openinghours' );
register_deactivation_hook( __FILE__, 'deactivate_wp_plugin_openinghours' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-plugin-openinghours.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_plugin_openinghours() {

	$plugin = new Wp_Plugin_Openinghours();
	$plugin->run();

}
run_wp_plugin_openinghours();

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://developer.p-r.io
 * @since             1.0.0
 * @package           Pr_Bazaarvoice
 *
 * @wordpress-plugin
 * Plugin Name:       Pernod Ricard Bazaarvoice
 * Plugin URI:        https://bitbucket.org/pernod-ricard/wordpress-plugin-pr-bazaarvoice/
 * Description:       This plugin allows you to embed the bazaar voice widget into the editor
 * Version:           1.0.0
 * Author:            Pernod Ricard
 * Author URI:        https://developer.p-r.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pr-bazaarvoice
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
* Currently plugin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'PR_BAZAARVOICE_VERSION', '1.0.0' );
define( 'PR_BAZAARVOICE_NAME', 'pr-bazaarvoice' );
define( 'PR_BAZAARVOICE_SLUG', 'pr-bazaarvoice' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/class-pr-bazaarvoice-activator.php
*/
function activate_pr_bazaarvoice() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pr-bazaarvoice-activator.php';
	Pr_Bazaarvoice_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-pr-bazaarvoice-deactivator.php
*/
function deactivate_pr_bazaarvoice() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pr-bazaarvoice-deactivator.php';
	Pr_Bazaarvoice_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pr_bazaarvoice' );
register_deactivation_hook( __FILE__, 'deactivate_pr_bazaarvoice' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-pr-bazaarvoice.php';

/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/
function run_pr_bazaarvoice() {

	$plugin = new Pr_Bazaarvoice();
	$plugin->run();

}

run_pr_bazaarvoice();

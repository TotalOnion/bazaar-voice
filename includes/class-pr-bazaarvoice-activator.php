<?php

/**
 * Fired during plugin activation
 *
 * @link       https://developer.p-r.io
 * @since      1.0.0
 *
 * @package    Pr_Bazaarvoice
 * @subpackage Pr_Bazaarvoice/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pr_Bazaarvoice
 * @subpackage Pr_Bazaarvoice/includes
 * @author     Pernod Ricard <gurvinderdaheley@gmail.com>
 */
class Pr_Bazaarvoice_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        // PR Core is required !
        $pr_core_plugin = 'pr-core/pr-core.php';
        if(
            ! file_exists( trailingslashit( WPMU_PLUGIN_DIR ) . $pr_core_plugin)
            && ! is_plugin_active( $pr_core_plugin )
        ) {
            wp_die(
                'You must activate <strong>PR CORE plugin</strong> to activate this plugin. <br><a href="' . admin_url( 'plugins.php' ) . '">Return to Plugins page</a>'
            );
        }
        
        // Empty WP cache and Pantheon cache to reload headers
        if ( function_exists( 'pantheon_clear_edge_all' ) ) {
            pantheon_clear_edge_all();
        }
        wp_cache_flush();
	}
}

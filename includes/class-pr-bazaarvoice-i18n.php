<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://developer.p-r.io
 * @since      1.0.0
 *
 * @package    Pr_Bazaarvoice
 * @subpackage Pr_Bazaarvoice/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pr_Bazaarvoice
 * @subpackage Pr_Bazaarvoice/includes
 * @author     Pernod Ricard <gurvinderdaheley@gmail.com>
 */
class Pr_Bazaarvoice_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pr-bazaarvoice',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

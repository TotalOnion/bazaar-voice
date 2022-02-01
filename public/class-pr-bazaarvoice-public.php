<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://developer.p-r.io
* @since      1.0.0
*
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/public
* @author     Pernod Ricard <gurvinderdaheley@gmail.com>
*/

class Pr_Bazaarvoice_Public {

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
	* Add the local js to footer
	*/
	public function add_js_to_footer() {
		global $sitepress;

		$block_found = false;

		// If bazaarvoice is not in the block then dont include the js
		if (!is_admin()) {
			$post = get_post();
			if ( has_blocks( $post->post_content ) ) {
				$blocks = parse_blocks( $post->post_content );
				foreach ($blocks as $block) {
					if (in_array($this->plugin_name.'/bazaarvoice', $block)) {
						$block_found = true;
						break;
					}
				}
			}
		}

		if ( ! $block_found ) {
			return;
		}

		$option_name = PR_BAZAARVOICE_NAME;
		$option_values = get_option($option_name);
		$default_field = PR_BAZAARVOICE_NAME . '-default-code';

		// Get the default field value
		if ( ! empty( $option_values ) ) {
			foreach ( $option_values as $option => $value ) {
				if ( $default_field === $option ) {
					$default_field_value = $value;
				}
			}
		}

		// Get the WPML settings or return if there are none (ie WPML has been deactivayed)
		$wpml_options = get_option( 'icl_sitepress_settings' );
		if ( empty( $wpml_options ) || empty( $wpml_options['active_languages'] ) ) {
			if ( ! empty( $default_field_value ) ) {
				echo '<!--- Code for bazaarvoice -->';
				echo $default_field_value;
				echo '<!--- End code for bazaarvoice -->';

				return;
			}
		}


		// Loop over all the markets and get the market code
		if (!empty($wpml_options) || !empty($wpml_options['active_languages']) ) {
			$market_data = array();
			foreach ( $wpml_options['active_languages'] as $active_language ) {
				$details = $sitepress->get_language_details( $active_language );
				if ( !$details ) {
					continue;
				}

				$market_data[] = array(
					'code'      => $details[ 'code' ]
				);
			}

			// Loop over the markets and get the fields
			foreach ($market_data as $market) {

				// Set the field name
				$bazaarvoice_language_field = PR_BAZAARVOICE_NAME . '-' . $market['code'] . '-code';

				foreach ($option_values as $option => $value) {
					if ($bazaarvoice_language_field == $option) {
						if (!empty($value)) {
							$default_field_value = $value;
						}
					}
				}
			}
		}

		// If a value is set we print the javascript in the footer
		if (!empty($default_field_value)) {
			echo '<!--- Code for bazaarvoice -->';
			echo $default_field_value;
			echo '<!--- End code for bazaarvoice -->';
		}

	}

	public function render_block( $attributes, $content )
	{
		return apply_filters(
			'bazaarvoice_filter',
			$attributes,
			$content
		);
	}

	/**
	 * Bazaarvoice shortcode in the form:
	 * [bazaarvoice bazaarvoice_product_id="44508001" type="reviews" title=""]
	 */
	public function bazaarvoice_shortcode($atts = [])
	{
		// Get the attributes
		$attributes = shortcode_atts([
			'bazaarvoice_product_id' => null,
			'type' => null,
			'title' => null
		], $atts, 'bazaarvoice');

		// Return the bazaar voice code if we have an id
		return apply_filters(
			'bazaarvoice_filter',
			$attributes,
			''
		);
	}

	/**
	 * Filter for the shortcode and Gutenberg block output
	 */
	public function bazaarvoice_block_filter( $attributes, $content )
	{
		if ( ! array_key_exists( 'bazaarvoice_product_id', $attributes ) ) {
			return '';
		}

		$bv_data_blocks  = '';
		$available_types = array(
			'ratingsummary',
			'reviews',
			'review_highlights',
			'inline_rating'
		);

		foreach ( $available_types as $available_type ) {
			if (
				array_key_exists( $available_type, $attributes )
				&& $attributes[$available_type]
			) {
				$bv_data_blocks .= sprintf(
					'<div data-bv-show="%s" data-bv-product-id="%s"></div>',
					$available_type,
					$attributes['bazaarvoice_product_id']
				);
			}
		}

		return <<<EOS
			<section class="wp-block-pr-bazaarvoice-bazaarvoice">
				$bv_data_blocks
			</section>
EOS;
	}
}

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

	const FORCE_ADD_JS_TO_PAGE = true;

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
	 * Enqueue the js
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		$this->add_js_to_footer();
	}

	/**
	 * Add the local js to footer, if there is a block on the page, or the shortcode is used
	 */
	public function add_js_to_footer( bool $force_add_to_page = false )
	{
		$script_reference = PR_BAZAARVOICE_NAME . '/public.js';

		// don't bother doing the checks below if the script has already been enqueued
		if( wp_script_is( $script_reference, 'enqueued' ) ) {
			return;
		}

		// If we're not forcing the js to be added to the page (via a shortcode),
		// make suree there is a BazaarVoice gutenberg block. If there isn't one,
		// we don't need the script
		if ( ! $force_add_to_page ) {
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
		}

		$option_values = get_option( PR_BAZAARVOICE_NAME );
		$default_field = PR_BAZAARVOICE_NAME . '-default-code';
		$bv_script_url = null;

		// Get the default field value
		if ( ! empty( $option_values ) ) {
			foreach ( $option_values as $option => $value ) {
				if ( $default_field === $option ) {
					$bv_script_url = $value;
				}
			}
		}

		// Get the WPML settings or return if there are none (ie WPML has been deactivayed)
		$wpml_options = get_option( 'icl_sitepress_settings' );

		// Loop over all the markets and get the market code
		if (
			! empty( $wpml_options )
			|| ! empty( $wpml_options['active_languages'] )
		) {
			global $sitepress;

			foreach ( $wpml_options['active_languages'] as $active_language ) {
				$details = $sitepress->get_language_details( $active_language );
				if ( !$details ) {
					continue;
				}

				// Set the field name
				$bazaarvoice_language_field = PR_BAZAARVOICE_NAME . '-' . $details['code'] . '-code';

				foreach ( $option_values as $option => $value ) {
					if ( $bazaarvoice_language_field == $option ) {
						if ( ! empty( $value ) ) {
							$bv_script_url = $value;
							break 2;
						}
					}
				}
			}
		}

		// If a value is set we print the javascript in the footer
		if ( ! empty( $bv_script_url ) ) {
			wp_enqueue_script(
				$script_reference,
				$bv_script_url,
				array(),
				$this->version,
				true
			);
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
	 * [bazaarvoice bazaarvoice_product_id="44508001" type="reviews"]
	 * @since 1.0.0
	 * @param array $attributes
	 * @return string
	 */
	public function bazaarvoice_shortcode( array $attributes = [] ): string
	{
		$this->add_js_to_footer( self::FORCE_ADD_JS_TO_PAGE );

		// Get the attributes
		$attributes = shortcode_atts([
			'bazaarvoice_product_id' => null,
			'type' => ''
		], $attributes, 'bazaarvoice');

		$types = array_map(
			function( $type ) {
				return trim( $type );
			},
			explode( ',', $attributes['type'] )
		);

		foreach ( $types as $type ) {
			$attributes[ $type ] = true;
		}

		unset( $attributes['type'] );

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
	public function bazaarvoice_block_filter( array $attributes, $content ): string
	{
		if (
			! array_key_exists( 'bazaarvoice_product_id', $attributes )
			|| empty( $attributes[ 'bazaarvoice_product_id' ] )
		) {
			return '';
		}

		$bv_data_blocks  = '';
		$available_types = array(
			'rating_summary',
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

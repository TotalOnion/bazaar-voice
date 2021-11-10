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
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {

		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Pr_Bazaarvoice_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Pr_Bazaarvoice_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

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
		* defined in Pr_Bazaarvoice_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Pr_Bazaarvoice_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pr-bazaarvoice-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	* Add the local js to footer
	*/
	public function add_js_to_footer() {
		global $sitepress;

		$block_found = 0;

		// If bazaarvoice is not in the block then dont include the js
		if (!is_admin()) {
			$post = get_post();
			if ( has_blocks( $post->post_content ) ) {
				$blocks = parse_blocks( $post->post_content );
				foreach ($blocks as $block) {
					if (in_array($this->plugin_name.'/bazaarvoice', $block)) {
						$block_found = 1;
						break;
					}
				}
			}
		}

		if ($block_found == 0) {
			return;
		}

		$option_name = PR_BAZAARVOICE_NAME;
		$option_values = get_option($option_name);
		$default_field = PR_BAZAARVOICE_NAME . '-default-code';

		// Get the default field value
		if (!empty($option_values)) {
			foreach ($option_values as $option => $value) {
				if ($default_field == $option) {
					$default_field_value = $value;
				}
			}
		}

		// Get the WPML settings or return if there are none (ie WPML has been deactivayed)
		$wpml_options = get_option( 'icl_sitepress_settings' );
		if (empty($wpml_options) || empty($wpml_options['active_languages']) ) {
			if (!empty($default_field_value)) {
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

	/**
	* Bazaarvoice shortcode
	*/
	public function bazaarvoice_shortcode($atts = []) {
		$type_array = array();
		$output = '';

		// Get the attributes
		$attributes = shortcode_atts([
			'id' => null,
			'type' => null,
			'title' => null
		], $atts, 'bazaarvoice');

		// Return the bazaar voice code if we have an id
		if (!empty($attributes['id'])) {
			return apply_filters('bazaarvoice_filter', $attributes['id'], $attributes['type'], $attributes['title']);
		}

	}

	/**
	* Bazaarvoice filter to modify html
	* called via apply_filters('bazaarvoice_filter', id='1234' type='reviews' title=''');
	*/
	public function bazaarvoice_block_filter($id, $types, $title) {

		// This will return the html for the bazaarvoice block
		$new_output = '<section class="review-block" id="'.$id.'">';
  		$new_output .= '<div>';

		// If you have a title
		if (!empty($title)) {
			$new_output .= '<h1>';
	  		$new_output .= $title;
			$new_output .= '</h1>';
		}

		if (is_array($types)) {
			foreach ($types as $type) {
				$new_output .= '<div data-bv-show="'.$type.'" data-bv-product-id="'.$id.'">Bazaarvoice shortcode - '.$type.'</div>';
			}
		} else {
			$new_output = '<div data-bv-show="'.$types.'" data-bv-product-id="'.$id.'">Bazaarvoice shortcode - '.$types.'</div>';
		}

		$new_output .= '</section>';

		return $new_output;
	}

}

<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://developer.p-r.io
* @since      1.0.0
*
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/admin
* @author     Pernod Ricard <gurvinderdaheley@gmail.com>
*/
class Pr_Bazaarvoice_Admin {

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
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	* Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pr-bazaarvoice-admin.css', array(), $this->version, 'all' );
	}

	/**
	* Register the JavaScript for the admin area.
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
		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pr-bazaarvoice-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-block', plugin_dir_url(__FILE__) . 'js/pr-bazaarvoice-block.js', array('wp-blocks','wp-editor', 'wp-components', 'wp-i18n'), true );
	}

	/**
	* Register the block for the gutenberg editor
	*/
	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			echo 'Gutenberg is not active.';
			return;
		}

		// Register the script
		wp_register_script(
			$this->plugin_name.'-block',
			plugins_url( 'js/pr-bazaarvoice-block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-editor', 'wp-components', 'wp-i18n' )
		);

		// Register the block
		register_block_type( $this->plugin_name.'/bazaarvoice', array(
			'editor_script' => $this->plugin_name.'-block',
		));
	}

	/**
	* Add plugin admin menu
	*/
	public function add_plugin_admin_menu() {
		// Add Pernod Ricard menu if not already added by an existing plugin
		$pr_icon = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPiAgPHBhdGggZmlsbD0iIzZiYTdkMiIgaWQ9InN2Z18xIiBkPSJtMjMwLjE0NCw0OTAuMzI1YzY4Ljk4MSwyNS42MjggMTE2LjI5Miw3MC45NjEgMTE2LjI5Miw3MC45NjFjMCwtMjEuNjgzIDUuOTEyLC00My4zNjUgMTMuNzk2LC02MS4xMDJjMCwwIC0yMS42ODMsLTQxLjM4NyAtODguNjk3LC04Ni43MjdjLTcwLjk2MSwtNDkuMjc4IC0xNTEuNzY2LC02MS4xMDIgLTE1MS43NjYsLTYxLjEwMmMtMTUuNzcsMzUuNDc0IC0yNy41OTUsNzIuOTI3IC0zMy41MDcsMTE0LjMxOGMwLC0xLjk2NiA1OS4xMzUsLTkuODU4IDE0My44ODcsMjMuNjQ5bC0wLjAwNSwwLjAwM3ptMTM5Ljk0NSwtMTEuODI1YzExLjgyNSwtMTkuNzEyIDI5LjU2MSwtMzcuNDUzIDQ3LjMwMywtNDkuMjc4YzAsMCAtMjUuNjI4LC03Ni44NzMgLTY1LjA0OCwtMTM0LjAyOXMtMTA0LjQ2OCwtMTA0LjQ2OCAtMTA0LjQ2OCwtMTA0LjQ2OGMtMzEuNTQxLDIzLjY0OSAtNTkuMTM1LDUzLjIxOSAtODIuNzg1LDg0Ljc1MmMwLDAgNjUuMDQ4LDI3LjU5NSAxMTQuMzE4LDc0Ljg5NWM2Ny4wMTUsNjMuMDY5IDkwLjY2NCwxMjguMTE3IDkwLjY2NCwxMjguMTE3bDAuMDE2LDAuMDExem02NS4wMzcsLTU5LjEzNmMxOS43MTIsLTkuODU4IDQ1LjMzMiwtMTcuNzM3IDY4Ljk4MSwtMTkuNzEyYzAsMCAxLjk2NiwtNzIuOTI3IC0xNS43NywtMTQ5LjhjLTE5LjcxMiwtODguNjk3IC00MS4zODcsLTE0My44ODcgLTQxLjM4NywtMTQzLjg4N2MtMzkuNDIsNS45MTIgLTc2Ljg3MywxNy43MzcgLTExMi4zNDcsMzMuNTA3YzAsMCA0MS4zODcsNzguODM5IDYxLjEwMiwxMzIuMDYzYzMxLjU0MSw3NC44OTUgMzkuNDIsMTQ3LjgyNiAzOS40MiwxNDcuODI2bDAuMDAxLDAuMDAzem0yMjguNjM5LDc4LjgzOWM3Ljg4LDE3LjczNyAxMy43OTYsNDEuMzg3IDEzLjc5Niw2MS4xMDJjMCwwIDQ1LjMzMiwtNDMuMzY1IDExNi4yOTIsLTcwLjk2MWM4NC43NTIsLTMxLjU0MSAxNDMuODg3LC0yNS42MjggMTQzLjg4NywtMjUuNjI4Yy01LjkxMiwtMzkuNDIgLTE3LjczNywtNzguODM5IC0zMy41MDcsLTExNC4zMThjMCwwIC04MC44MTEsMTMuNzk2IC0xNTEuNzY2LDYxLjEwMmMtNjUuMDQ4LDQ3LjMwMyAtODguNjk3LDg4LjY5NyAtODguNjk3LDg4LjY5N2wtMC4wMDUsMC4wMDZ6bS01Ny4xNTYsLTcwLjk1OGMxOS43MTIsMTMuNzk2IDM1LjQ3NCwyOS41NjEgNDcuMzAzLDQ5LjI3OGMwLDAgMjMuNjQ5LC02NS4wNDggOTAuNjY0LC0xMjguMTE3YzQ5LjI3OCwtNDcuMzAzIDExNC4zMTgsLTc0Ljg5NSAxMTQuMzE4LC03NC44OTVjLTIzLjY0OSwtMzEuNTQxIC01MS4yNDQsLTYxLjEwMiAtODIuNzg1LC04NC43NTJjMCwwIC02NS4wNDgsNDcuMzAzIC0xMDQuNDY4LDEwNC40NjhjLTQxLjM4Nyw1Ny4xNTYgLTY1LjA0OCwxMzQuMDI5IC02NS4wNDgsMTM0LjAyOWwwLjAxNiwtMC4wMTF6bS0xNy43MzcsLTcuODgxYzAsMCA3Ljg4LC03Mi45MjcgMzcuNDUzLC0xNDkuOGMyMS42ODMsLTUzLjIxOSA2My4wNjksLTEzMi4wNjMgNjMuMDY5LC0xMzIuMDYzYy0zNS40NzQsLTE1Ljc3IC03Mi45MjcsLTI3LjU5NSAtMTEyLjM0NywtMzMuNTA3YzAsMCAtMjEuNjgzLDU1LjE5IC00MS4zODcsMTQzLjg4N2MtMTUuNzcsNzYuODczIC0xNS43NywxNDkuOCAtMTUuNzcsMTQ5LjhjMjMuNjQ5LDEuOTY2IDQ5LjI3OCw5Ljg1OCA2OC45ODEsMjEuNjgzbDAuMDAxLDB6bS0xMzQuMDI5LDI5OS42Yy0yOS41NjEsLTExLjgyNSAtNTUuMTksLTI5LjU2MSAtNzQuODk1LC01NS4xOWMwLDAgLTc0Ljg5NSwtNy44OCAtMTMyLjA2MywxLjk2NmMtODYuNzI3LDE3LjczNyAtMTI0LjE3MSw0Ny4zMDMgLTEyNC4xNzEsNDcuMzAzYzE3LjczNywzNS40NzQgMzkuNDIsNjguOTgxIDY3LjAxNSw5OC41NTZjMCwwIDM1LjQ3NCwtMzMuNTA3IDk2LjU3NiwtNjMuMDY5YzgyLjc4NSwtMzcuNDUzIDE2Ny41MzcsLTI5LjU2MSAxNjcuNTM3LC0yOS41NjFsMC4wMDEsLTAuMDA1em0tODguNjk4LC03NC45MDdjLTkuODU4LC0xOS43MTIgLTE3LjczNywtMzkuNDIgLTE5LjcxMiwtNjMuMDY5YzAsMCAtNjcuMDE1LC0zNy40NTMgLTEzMC4wODMsLTQ3LjMwM2MtODYuNzI3LC0xMS44MjUgLTEzNS45OTYsNS45MTIgLTEzNS45OTYsNS45MTJjMS45NjYsNDEuMzg3IDcuODgsNzguODM5IDE5LjcxMiwxMTYuMjkyYzAsMCA1NS4xOSwtMjcuNTk1IDEyMi4yMDUsLTMxLjU0MWM4NC43NTIsLTMuOTQ2IDE0My44ODcsMTkuNzEyIDE0My44ODcsMTkuNzEybC0wLjAxMywtMC4wMDN6bTE0NS44NTQsODIuNzg1Yy0xNzUuNDE3LDAgLTI3Ny45MTcsMTI4LjExNyAtMjc3LjkxNywxMjguMTE3YzMxLjU0MSwyNS42MjggNjcuMDE1LDQ3LjMwMyAxMDQuNDY4LDY1LjA0OGMwLDAgNTMuMjE5LC0xMDQuNDY4IDE3My40NDksLTEwNC40NjhjMTIyLjIwNSwwIDE3My40NDksMTA0LjQ2OCAxNzMuNDQ5LDEwNC40NjhjMzcuNDUzLC0xNS43NyA3Mi45MjcsLTM5LjQyIDEwNC40NjgsLTY1LjA0OGMwLDAgLTEwMi40ODgsLTEyOC4xMTcgLTI3Ny45MTcsLTEyOC4xMTd6bTEzMi4wNjMsLTYzLjA2OGMtMTkuNzEyLDIzLjY0OSAtNDUuMzMyLDQzLjM2NSAtNzQuODk1LDU1LjE5YzAsMCA4NC43NTIsLTcuODggMTY3LjUzNywzMS41NDFjNjEuMTAyLDI5LjU2MSA5Ni41NzYsNjMuMDY5IDk2LjU3Niw2My4wNjljMjUuNjI4LC0yOS41NjEgNDkuMjc4LC02My4wNjkgNjcuMDE1LC05OC41NTZjMCwwIC0zNy40NTMsLTI5LjU2MSAtMTI0LjE3MSwtNDcuMzAzYy01Ny4xNTYsLTExLjgyNSAtMTMyLjA2MywtMy45NDYgLTEzMi4wNjMsLTMuOTQ2bDAuMDAxLDAuMDA1em0xNjMuNTkxLC0xMzAuMDljLTYxLjEwMiw3Ljg4IC0xMzAuMDgzLDQ3LjMwMyAtMTMwLjA4Myw0Ny4zMDNjLTEuOTY2LDIxLjY4MyAtOS44NTgsNDMuMzY1IC0xOS43MTIsNjMuMDY5YzAsMCA1OS4xMzUsLTIxLjY4MyAxNDMuODg3LC0xNy43MzdjNjcuMDE1LDMuOTQ2IDEyMi4yMDUsMzEuNTQxIDEyMi4yMDUsMzEuNTQxYzExLjgyNSwtMzcuNDUzIDE3LjczNywtNzYuODczIDE5LjcxMiwtMTE2LjI5MmMwLC0xLjk2NiAtNTEuMjQ0LC0xNy43MzcgLTEzNS45OTYsLTcuODhsLTAuMDEzLC0wLjAwNHoiLz48L3N2Zz4=";
		$menu_slug = 'pernod-ricard';
		if ( empty ( $GLOBALS['admin_page_hooks'][$menu_slug] ) ) {
			add_menu_page('Pernod Ricard', 'Pernod Ricard', 'manage_options', $menu_slug, '', $pr_icon);
		}

		// Sub menu
		add_submenu_page( $menu_slug, 'Bazaarvoice', 'Bazaarvoice', 'manage_options', 'pr-bazaarvoice', array($this, 'render_settings_page'));

		// trick to remove menu page becomming a submenu : https://wordpress.stackexchange.com/a/173476
		remove_submenu_page( $menu_slug, $menu_slug );
    }

	/**
	* Add the settings page. Reference:
	* https://developer.wordpress.org/reference/functions/add_options_page/
	*
	* @since    1.1.0
	*/

	public function add_settings_page() {
		add_options_page(
			'Bazaarvoice Settings',               		// page title
			'Bazaarvoice Settings',               		// menu title
			'manage_options',                         	// capability required to access / see it
			PR_BAZAARVOICE_SLUG . '-settings-page', 	// slug (needs to be unique)
			array( $this, 'render_settings_page' )    	// callable function to render the page
		);
	}

	/**
	* Render the settings page for this plugin.
	*/
	public function render_settings_page() {
		include_once( 'partials/pr-bazaarvoice-settings-form.php' );
	}

	public function register_settings() {
		global $sitepress;

		add_option(PR_BAZAARVOICE_NAME);

		//register our settings
		register_setting(
			PR_BAZAARVOICE_NAME ,
			PR_BAZAARVOICE_NAME. '-default-code',
			array(
				'type'         => 'string',
				'description'  => 'Default value for bazaarvoice',
				'show_in_rest' => false,
				'default'      => '',
			)
		);

		// Adds the settings *section*
		// reference https://developer.wordpress.org/reference/functions/add_settings_section/
		add_settings_section(
			PR_BAZAARVOICE_NAME . '_options_section', // Unique ID for the section
			'',  // Title for the section
			array( $this, 'render_section_intro' ),      // Callable function to echo the intro
			PR_BAZAARVOICE_SLUG . '-settings-page'    // the page this section appears on (defined in registerPage above)
		);

		// Create the default field
		$default_field = PR_BAZAARVOICE_NAME . '-default-code';

		// This adds the html field that renders the setting
		// reference https://developer.wordpress.org/reference/functions/add_settings_field/
		add_settings_field(
			PR_BAZAARVOICE_NAME . '-default-code', 			// id="" value
			'Default settings',                   			// <label> value
			array( $this, 'render_fields' ),    			// callback to actually do the rendering of the input
			PR_BAZAARVOICE_SLUG . '-settings-page',      	// Slug of the page to show this on (defined in registerPage above)
			PR_BAZAARVOICE_NAME . '_options_section',     	// slug of the sction the field appears in
			array($default_field) 							// Pass the default field to the html
		);

		// Get the WPML settings or return if there are none (ie WPML has been deactivayed)
		$wpml_options = get_option( 'icl_sitepress_settings' );
		if (!$wpml_options || empty($wpml_options['active_languages']) ) {
			return;
		}

		// Loop over all the markets and get the name, and if they are currently hidden
		$market_data = array();
		foreach ( $wpml_options['active_languages'] as $active_language ) {
			$details = $sitepress->get_language_details( $active_language );
			if ( !$details ) {
				continue;
			}

			$market_data[] = array(
				'code'      => $details[ 'code' ],
				'name'      => $details[ 'english_name' ]
			);
		}

		// Loop over the markets and output the fields
		foreach ($market_data as $market) {

			// Add settings field
			$bazaarvoice_language_field = PR_BAZAARVOICE_NAME . '-' . $market['code'] . '-code';

			add_settings_field(
				$bazaarvoice_language_field,				// id="" value
				$market['name'].' override',				// <label> vale
				array( $this, 'render_fields' ),			// callback to actually do the rendering of the input
				PR_BAZAARVOICE_SLUG . '-settings-page',		// Slug of the page to show this on (defined in registerPage above)
				PR_BAZAARVOICE_NAME . '_options_section',	// slug of the sction the field appears in
				array($bazaarvoice_language_field)			// Args for the field
			);
		}
	}

	/**
	 * Render a header if needed
	 */
	public function render_section_intro() {
		_e(
			'Add the default Bazaarvoice code below',
			'PR Bazaarvoice'
		);
	}

	/**
	 * Render textareas on the settings page
	 */
	public function render_fields($array) {

		$options = get_option(PR_BAZAARVOICE_NAME);

		if (!empty($array)) {
			$bazaarvoice_field_name = $array[0];
			if (!empty($options)) {
				$bazaarvoice_field_value = $options[$bazaarvoice_field_name];
			}
		}

		include __DIR__ . '/partials/pr-bazaarvoice-default-textarea.php';
	}

	/**
	 * Update the option when form is submitted
	 */
	public function update_settings() {
		register_setting(PR_BAZAARVOICE_NAME, PR_BAZAARVOICE_NAME);
	}

	public function filter_allowed_block_types( $allowed_block_types, $post)
	{
		if (is_array( $allowed_block_types ) && !in_array( $this->plugin_name.'/bazaarvoice', $allowed_block_types )) {
			$allowed_block_types[] = $this->plugin_name.'/bazaarvoice';
		}

		return $allowed_block_types;
	}
}

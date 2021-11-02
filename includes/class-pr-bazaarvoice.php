<?php

/**
* The file that defines the core plugin class
*
* A class definition that includes attributes and functions used across both the
* public-facing side of the site and the admin area.
*
* @link       https://developer.p-r.io
* @since      1.0.0
*
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/includes
*/

/**
* The core plugin class.
*
* This is used to define internationalization, admin-specific hooks, and
* public-facing site hooks.
*
* Also maintains the unique identifier of this plugin as well as the current
* version of the plugin.
*
* @since      1.0.0
* @package    Pr_Bazaarvoice
* @subpackage Pr_Bazaarvoice/includes
* @author     Pernod Ricard <gurvinderdaheley@gmail.com>
*/
class Pr_Bazaarvoice {
	/**
	* The loader that's responsible for maintaining and registering all hooks that power
	* the plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      Pr_Bazaarvoice_Loader    $loader    Maintains and registers all hooks for the plugin.
	*/
	protected $loader;

	/**
	* The unique identifier of this plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      string    $plugin_name    The string used to uniquely identify this plugin.
	*/
	protected $plugin_name;

	/**
	* The current version of the plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      string    $version    The current version of the plugin.
	*/
	protected $version;

	/**
	* Define the core functionality of the plugin.
	*
	* Set the plugin name and the plugin version that can be used throughout the plugin.
	* Load the dependencies, define the locale, and set the hooks for the admin area and
	* the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function __construct() {
		if ( defined( 'PR_BAZAARVOICE_VERSION' ) ) {
			$this->version = PR_BAZAARVOICE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pr-bazaarvoice';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	* Load the required dependencies for this plugin.
	*
	* Include the following files that make up the plugin:
	*
	* - Pr_Bazaarvoice_Loader. Orchestrates the hooks of the plugin.
	* - Pr_Bazaarvoice_i18n. Defines internationalization functionality.
	* - Pr_Bazaarvoice_Admin. Defines all hooks for the admin area.
	* - Pr_Bazaarvoice_Public. Defines all hooks for the public side of the site.
	*
	* Create an instance of the loader which will be used to register the hooks
	* with WordPress.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function load_dependencies() {

		/**
		* The class responsible for orchestrating the actions and filters of the
		* core plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pr-bazaarvoice-loader.php';

		/**
		* The class responsible for defining internationalization functionality
		* of the plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pr-bazaarvoice-i18n.php';

		/**
		* The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pr-bazaarvoice-admin.php';

		/**
		* The class responsible for defining all actions that occur in the public-facing
		* side of the site.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pr-bazaarvoice-public.php';

		$this->loader = new Pr_Bazaarvoice_Loader();
	}

	/**
	* Define the locale for this plugin for internationalization.
	*
	* Uses the Pr_Bazaarvoice_i18n class in order to set the domain and to register the hook
	* with WordPress.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function set_locale() {

		$plugin_i18n = new Pr_Bazaarvoice_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	* Register all of the hooks related to the admin area functionality
	* of the plugin.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function define_admin_hooks() {
		global $wp_version;

		$plugin_admin = new Pr_Bazaarvoice_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Register settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'update_settings');

		// Register the block
		$this->loader->add_action( 'init', $plugin_admin, 'register_block' );

		// Add bazaar block to allowed blocks
		if ( has_filter( 'allowed_block_types_all' ) ) {
			$this->loader->add_filter( 'allowed_block_types_all', $plugin_admin, 'filter_allowed_block_types', 1000, 2);
		} else {
			$this->loader->add_filter( 'allowed_block_types', $plugin_admin, 'filter_allowed_block_types', 1000, 2);
		}

	}

	/**
	* Register all of the hooks related to the public-facing functionality
	* of the plugin.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function define_public_hooks() {

		$plugin_public = new Pr_Bazaarvoice_Public( $this->get_plugin_name(), $this->get_version() );

		// Add bazaarcode js to footer
		$this->loader->add_action( 'wp_footer', $plugin_public, 'add_js_to_footer' );

		// Add shortcode
		add_shortcode('bazaarvoice', array($plugin_public, 'bazaarvoice_shortcode'));

		// Add filter to shortcode
		add_filter('bazaarvoice_filter', array($plugin_public, 'bazaarvoice_block_filter'), 10, 3);

	}

	/**
	* Run the loader to execute all of the hooks with WordPress.
	*
	* @since    1.0.0
	*/
	public function run() {
		$this->loader->run();
	}

	/**
	* The name of the plugin used to uniquely identify it within the context of
	* WordPress and to define internationalization functionality.
	*
	* @since     1.0.0
	* @return    string    The name of the plugin.
	*/
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	* The reference to the class that orchestrates the hooks with the plugin.
	*
	* @since     1.0.0
	* @return    Pr_Bazaarvoice_Loader    Orchestrates the hooks of the plugin.
	*/
	public function get_loader() {
		return $this->loader;
	}

	/**
	* Retrieve the version number of the plugin.
	*
	* @since     1.0.0
	* @return    string    The version number of the plugin.
	*/
	public function get_version() {
		return $this->version;
	}
}

<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://cybercom.com
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/includes
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
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/includes
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class Wp_Plugin_Openinghours {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Plugin_Openinghours_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'wp-plugin-openinghours';
		$this->version = '1.0.0';

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
	 * - Wp_Plugin_Openinghours_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Plugin_Openinghours_i18n. Defines internationalization functionality.
	 * - Wp_Plugin_Openinghours_Admin. Defines all hooks for the admin area.
	 * - Wp_Plugin_Openinghours_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-plugin-openinghours-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-plugin-openinghours-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-plugin-openinghours-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-plugin-openinghours-public.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-plugin-openinghours-posttype-location.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-plugin-openinghours-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-plugin-openinghours-widget-ajax.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-plugin-openinghours-settings.php';


		$this->loader = new Wp_Plugin_Openinghours_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Plugin_Openinghours_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Plugin_Openinghours_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	private function load_acf() {

		$this->loader->add_filter( 'acf/settings/load_json', $this, 'acf_json' );

	}


	/**
	 * Setting up acf-json sync for acf fields.
	 *
	 * @since 1.0.0
	 *
	 * @param $paths
	 *
	 * @return array
	 */
	public function acf_json( $paths ) {

		$paths []= plugin_dir_path( __FILE__ ) . 'acf-json';
		return $paths;

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Plugin_Openinghours_Admin( $this->get_plugin_name(), $this->get_version() );
		$wp_plugin_openinghours_settings = new WP_Plugin_Openinghours_Settings();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Plugin settings page
		$this->loader->add_action( 'admin_init', $wp_plugin_openinghours_settings, 'init' );
		$this->loader->add_action( 'admin_menu', $wp_plugin_openinghours_settings, 'options_page' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Plugin_Openinghours_Public( $this->get_plugin_name(), $this->get_version() );
		$wp_plugin_openinghours_posttype_location = new WP_Plugin_OpeningHours_Posttype_Location();
		//$wp_plugin_openinghours_widget = new WP_Plugin_OpeningHours_Widget();
		$wp_plugin_opening_hours_widget_ajax = new WP_Plugin_OpeningHours_Widget_Ajax();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $wp_plugin_openinghours_posttype_location, 'register' );
		$this->loader->add_action( 'wp_ajax_load_opening_hours', $wp_plugin_opening_hours_widget_ajax, 'load_opening_hours' );
		$this->loader->add_action( 'wp_ajax_nopriv_load_opening_hours', $wp_plugin_opening_hours_widget_ajax, 'load_opening_hours' );

		$this->loader->add_filter( 'acf/settings/load_json', $this, 'acf_json' );

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
	 * @return    Wp_Plugin_Openinghours_Loader    Orchestrates the hooks of the plugin.
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

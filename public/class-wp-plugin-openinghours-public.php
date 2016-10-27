<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://cybercom.com
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/public
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class Wp_Plugin_Openinghours_Public {

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

		wp_enqueue_style( 'wp-plugin-openinghours-datepicker', plugin_dir_url( __FILE__ ) . 'css/datepicker/datepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-plugin-openinghours-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-plugin-openinghours-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wp-plugin-openinghours-datepicker', plugin_dir_url( __FILE__ ) . 'js/datepicker/bootstrap-datepicker.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wp-plugin-openinghours-datepicker-locale', plugin_dir_url( __FILE__ ) . 'js/datepicker/locales/bootstrap-datepicker.sv.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'WP_PLUGIN_OPENINGHOURS',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'wp-plugin-openinghours' )
			)
		);

	}

}

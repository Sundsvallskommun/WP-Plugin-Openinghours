<?php
/**
 * Provide a settings page for the plugin
 *
 *
 * @link       http://cybercom.com
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/admin
 */
class WP_Plugin_Openinghours_Settings {

	/**
	 * Initialize the plugin settings page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function init() {
		register_setting( 'wp_plugin_openinghours', 'wp_plugin_openinghours_options' );

		add_settings_section(
			'section_permalinks',
			__( 'Inställningar för permalänkar', 'wp-plugin-openinghours' ),
			array( $this, 'section_permalinks'),
			'wp_plugin_openinghours'
		);


		add_settings_field(
			'wp_plugin_openinghours_permalinks',
			__( 'Ange permalänkbas för anläggningar', 'wp_plugin_openinghours' ),
			array( $this, 'field_permalinks_html' ),
			'wp_plugin_openinghours',
			'section_permalinks',
			[
				'label_for'         => 'wp_plugin_openinghours_field_permalink',
				'class'             => 'wp_plugin_openinghours_row',
				'wp_plugin_openinghours_custom_data' => 'custom',
			]
		);
	}


	/**
	 * Section description html
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function section_permalinks( $args ) {
		?>
		<p id="<?= esc_attr( $args['id'] ); ?>"><?= esc_html__( 'Kom ihåg att spara om permalänkarna efter att du har ändrat inställningarna här.', 'wp-plugin-openinghours' ); ?></p>
		<?php
	}



	/**
	 * Options for the permalink field
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_permalinks_html( $args ) {
		$options = get_option( 'wp_plugin_openinghours_options' );
		?>
		<input type="text" class="regular-text ltr" name="wp_plugin_openinghours_options[<?= esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[$args['label_for']]; ?>" />
		<p class="description">Permalänk kommer att blir <?php echo get_site_url(); ?>/permalänksbas/anläggningstitel</p>
		<?php
	}


	/**
	 * Add options page to admin menu
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function options_page() {

		add_submenu_page(
			'options-general.php',
			__( 'Inställningar för Anläggningar', 'wp-plugin-openinghours' ),
			__( 'Anläggningar', 'wp-plugin-openinghours' ),
			'manage_options',
			'wp_plugin_openinghours',
			array( $this, 'options_page_html' )
		);

	}


	/**
	 * Settings page layout and output
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function options_page_html() {
		if (!current_user_can('manage_options')) {
			return;
		}

		settings_errors('wp_plugin_openinghours_messages');
		?>
		<div class="wrap">
			<h1><?= esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'wp_plugin_openinghours' );
				do_settings_sections( 'wp_plugin_openinghours' );
				submit_button( __( 'Spara inställningar', 'wp-plugin-openinghours' ) );
				?>
			</form>
		</div>
		<?php
	}

}
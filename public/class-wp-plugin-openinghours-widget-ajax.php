<?php
/**
 * Class for handling calendar ajax events
 *
 *
 * @since      1.0.0
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/public
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class WP_Plugin_OpeningHours_Widget_Ajax {

	/**
	 * Ajax callback for opening hours widget
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return  json    json response
	 */
	public function load_opening_hours() {

		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'wp-plugin-openinghours' ) )
			die ( 'Busted!');

		$date = isset( $_POST['date'] ) ? $_POST['date'] : date_i18n();

		$location = isset( $_POST['location'] ) ? (int) $_POST['location'] : 'all';
		if ( $location == 0 ) $location = 'all';

		wp_send_json(
			$this->ajax_load_opening_hours( $date, $location )
		);
	}


	/**
	 * Setup the response for the ajax callback
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return  array
	 */
	private function ajax_load_opening_hours( $date, $location ) {

		return array(
			'date' => date_i18n('l j F', strtotime( $date ) ),
			'hours' => WP_Plugin_OpeningHours_Widget::hours( $date, $location )
		);

	}

}
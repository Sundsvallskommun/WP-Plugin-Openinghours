<?php
/**
 * Class for handling calendar and widget logic
 *
 * @since      1.0.0
 * @package    Wp_Plugin_Openinghours
 * @subpackage Wp_Plugin_Openinghours/public
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class WP_Plugin_OpeningHours_Widget {


	/**
	 * Class constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		add_shortcode( 'opening_hours', array( $this, 'opening_hours' ) );

	}


	/**
	 * Shortcode output for opening_hours
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return      string      html output for shortcode
	 */
	public function opening_hours( $atts ) {

		$attributes = shortcode_atts( array(
			'title' => __('Öppettider', 'msva'),
			'date' => date_i18n('Y-m-d'), // Default to today if no date is set
			'location' => 'all',
			'link'  => false
		), $atts );

		$this->enqueue_scripts_and_styles();

		ob_start();
		?>
		<div class="opening-hours-wrapper">
			<div class="opening-hours-header">
				<div class="opening-hours-nav">
					<ul>
						<li class="nav-left left-arrow"><?php material_icon( 'chevron left', array('size' => '3em') ); ?></li>
						<li class="nav-title">
							<div class="header">
								<p class="title"><?php echo $attributes['title']; ?></p>
								<p class="date"><?php echo ucfirst( date_i18n('l j F', strtotime( $attributes['date'] ) ) ); ?><span class="icon"><i class="material-icons">date_range</i></span></p>
								<div class="current-date"><?php echo date_i18n('Y-m-d'); ?></div>
								<div class="current-location"><?php echo $attributes['location'] ?></div>
								<div class="datepicker-wrapper">
									<input type="text" value="<?php $attributes['date']?>" id="opening-hours-datepicker" />
								</div>
							</div>

						</li>
						<li class="nav-right right-arrow"><?php material_icon( 'chevron right', array('size' => '3em') ); ?></li>
					</ul>
				</div>
				<div class="clearfix"></div>

			</div>
			<?php echo self::hours( $attributes['date'], $attributes['location'] ); ?>
			<div class="opening-hours-footer">
				<?php if( !empty( $attributes['link'] ) ) : ?>
				<a href="<?php echo $attributes['link']; ?>"><?php _e( 'Visa alla öppettider', 'msva' );?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}


	private function enqueue_scripts_and_styles() {

		wp_enqueue_script( 'wp-plugin-openinghours' );
		wp_enqueue_script( 'wp-plugin-openinghours-datepicker' );
		wp_enqueue_script( 'wp-plugin-openinghours-datepicker-locale' );

		wp_enqueue_style( 'wp-plugin-openinghours-datepicker' );
		wp_enqueue_style( 'wp-plugin-openinghours' );

	}


	/**
	 * The html output for the hours section
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return      string      html
	 */
	public static function hours( $date, $wanted_location ) {
		$opening_hours = self::setup_opening_hours( $date, $wanted_location );
		ob_start();
		?>
		<div class="loader"></div>
		<div class="opening-hours-widget list-group list-group-flush">
			<?php foreach( $opening_hours as $location ) : ?>
				<?php if ( is_array( $location['hours'] ) || is_string( $location['hours'] ) ) : ?>

					<div class="list-group-item">

						<div class="icon pull-xs-left"><?php material_icon( 'query builder', array('size' => '2.3em') ); ?></div>
						<div class="location-information pull-xs-left">
							<?php echo self::location_information( $location ); ?>
						</div>
						<div class="clearfix"></div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}


	/**
	 * The html output for a single location
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return      string      html
	 */
	private static function location_information( $location ) {
		ob_start();
		?>
		<div class="title"><a href="<?php echo get_permalink( $location['location_data']->ID ); ?>"><?php echo $location['location_data']->post_title; ?></a></div>
		<?php if ( is_array( $location['hours'] ) ) : ?>
			<?php
			$compiled_hours = array();

			foreach ( $location['hours'] as $hour ) {
				$openinghours = $hour['oppningstid'];
				$closinghours = $hour['stangningstid'];
				$compiled_hours []= $openinghours . ' - ' . $closinghours;
			}

			?>
			<p class="opening hours"><?php echo str_replace( ':', '.', implode( ', ', $compiled_hours ) ); ?></p>


		<?php else : ?>
			<?php if ( $location['hours'] !== 'STÄNGT' ) : ?>
				<p class="opening"><?php echo $location['hours']; ?></p>
			<?php else : ?>
				<p class="opening closed"><?php _e( 'Stängt', 'msva' ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

		<?php
		return ob_get_clean();
	}


	/**
	 * Parse location posts and their opening and close times
	 * to setup an array with compund information.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param   $check_date     the date to check against
	 *
	 * @return  array       the array with information about locations and times
	 */
	private static function setup_opening_hours( $check_date, $wanted_location ) {

		$locations = WP_Plugin_OpeningHours_Posttype_Location::locations( $wanted_location );
		$opening_hours = array();

		if ( count( $locations ) > 0 ) {

			foreach ( $locations as $location ) {

				$phone = get_field( 'telefon', $location->ID );
				$email = get_field( 'e-post', $location->ID );
				$override_hours = get_field( 'override_hours', $location->ID );


				$field_name = 'standard_' . date_i18n( 'w', strtotime( $check_date ) );
				$standard_opening_hours = get_field( $field_name, $location->ID );

				$opening_hours[$location->ID]['location_data'] = $location;
				$opening_hours[$location->ID]['location_data']->contact_phone = $phone;
				$opening_hours[$location->ID]['location_data']->contact_email = $email;

				if ( is_array( $standard_opening_hours ) && count( $standard_opening_hours ) > 0 ) {

					$opening_hours[$location->ID]['hours'] = $standard_opening_hours;
					$opening_hours[$location->ID]['type'] = 'standard';

				}

				$opening_hours = self::check_deviation_periods( $check_date, $location, $opening_hours );
				$opening_hours = self::check_deviation_dates( $check_date, $location, $opening_hours );

				if ( empty( $opening_hours[ $location->ID ]['type'] ) || ( isset( $opening_hours[$location->ID]['closed'] ) && $opening_hours[$location->ID]['closed'] == true ) ) {
					$opening_hours[ $location->ID ]['hours'] = 'STÄNGT';
				}

				// override all hours
				if( !empty( $override_hours ) ) {
					$opening_hours[ $location->ID ]['hours'] = $override_hours;
				}


			}


		}

		return $opening_hours;

	}


	/**
	 * Check given date against calendar deviation periods.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param   $check_date     the date to check against
	 * @param   $location       the location object
	 * @param   $opening_hours  the compund array with opening hours and locations
	 *
	 * @return  array       the array with information about locations and times
	 */
	private static function check_deviation_periods( $check_date, $location, $opening_hours ) {

		$deviation_periods = get_field( 'avvikelse', $location->ID );
		$day_number = date_i18n('w', strtotime( $check_date ) );

		// bug fix: sunday is zero but we saving it as seven with acf. So this fix change from 0 to 7.
		if ( $day_number === '0' ) {
			$day_number = '7';
		}

		if ( is_array( $deviation_periods ) && count( $deviation_periods ) > 0 ) {

			foreach ( $deviation_periods as $deviation ) {

				// Is this a date in the period
				if ( self::between_dates( $check_date, $deviation['startdatum'], $deviation['slutdatum'] ) ) {

					if( is_array( $deviation['veckodag'] ) && count( $deviation['veckodag'] ) > 0 ) {

						// Is the date in given weekday of the period
						if( in_array( $day_number, $deviation['veckodag'] ) ) {

							if( ! isset( $opening_hours[$location->ID]['type'] ) || $opening_hours[$location->ID]['type'] != 'single' ) {

								$opening_hours[$location->ID]['hours'] = $deviation['tider'];
								$opening_hours[$location->ID]['type'] = 'period';

								//echo '<pre>' . print_r( $deviation, true ) . '</pre>';
								//die();
								if ( $deviation['typ'] === 'stangt' ) {
									$opening_hours[$location->ID]['closed'] = true;
								} else {
									$opening_hours[$location->ID]['closed'] = false;
								}

							}

						}

					} else { // No weekdays given. Check all days unless a single date has overriden

						if( ! isset( $opening_hours[$location->ID]['type'] ) || $opening_hours[$location->ID]['type'] != 'single' ) {

							$opening_hours[$location->ID]['hours'] = $deviation['tider'];
							$opening_hours[$location->ID]['type'] = 'period';

							if ( $deviation['typ'] === 'stangt' ) {
								$opening_hours[$location->ID]['closed'] = true;
							} else {
								$opening_hours[$location->ID]['closed'] = false;
							}

						}

					}

				}

			}

		}

		return $opening_hours;

	}


	/**
	 * Check given date against calendar deviation dates.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param   $check_date     the date to check against
	 * @param   $location       the location object
	 * @param   $opening_hours  the compund array with opening hours and locations
	 *
	 * @return  array       the array with information about locations and times
	 */
	private static function check_deviation_dates( $check_date, $location, $opening_hours ) {

		$deviation_dates = get_field( 'avvikande_datum', $location->ID );
		if ( is_array( $deviation_dates ) && count( $deviation_dates ) > 0 ) {

			foreach ( $deviation_dates as $deviation ) {

				if ( $check_date == $deviation['datum'] ) {

					//echo '<pre>' . print_r( $deviation, true ) . '</pre>';
					// Found a deviation
					$opening_hours[$location->ID]['hours'] = $deviation['tider'];
					$opening_hours[$location->ID]['type'] = 'single';

					if ( $deviation['typ'] === 'stangt' ) {
						$opening_hours[$location->ID]['closed'] = true;
					} else {
						$opening_hours[$location->ID]['closed'] = false;
					}

				}

			}

		}

		return $opening_hours;

	}


	/**
	 * Check if a date falls bestween two dates
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param   $check_date     the date to check against
	 * @param   $first_date
	 * @param $second_date
	 *
	 * @return bool
	 */
	private static function between_dates( $check_date, $first_date, $second_date ) {

		if (  $check_date <= $second_date && $check_date >= $first_date ) return true;
		return false;

	}

}
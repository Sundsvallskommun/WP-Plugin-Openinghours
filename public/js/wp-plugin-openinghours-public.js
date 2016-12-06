(function( $ ) {
	'use strict';

	$(document).ready( function() {

		if( $('.opening-hours-wrapper').length ) {

			$('.opening-hours-nav .date').datepicker({
				'weekStart': 1,
				'autoclose': true,
				'language': 'sv',
				'container': '#opening-hours-datepicker'
			});

			$('.opening-hours-nav .date').datepicker().on('changeDate', function(ev){

				$('.opening-hours-wrapper .date').datepicker('hide');

				var date = new Date( ev.date.valueOf() );
				date.setDate( date.getDate() );
				date = getFormattedDate( date );

				load_opening_hours( date );

			});

			$('.right-arrow').on('click', function() {

				var date = new Date( $('.current-date').html() );
				date.setDate( date.getDate() + 1 );
				date = getFormattedDate( date );

				load_opening_hours( date );

			});

			$('.left-arrow').on('click', function() {

				var date = new Date( $('.current-date').html() );
				date.setDate( date.getDate() - 1 );
				date = getFormattedDate( date );

				load_opening_hours( date );

			});

		}


	} );



function load_opening_hours( date ) {
	var location = $('.current-location').html();

	$('.current-date').html( date );
	$('.loader').show();
	$('.opening-hours-widget .list-group-item').fadeTo('medium', 0.2);

	$.post( WP_PLUGIN_OPENINGHOURS.ajax_url, {

		'action': 'load_opening_hours',
		'nonce': WP_PLUGIN_OPENINGHOURS.ajax_nonce,
		'date': date,
		'location': location

	}, function( response ) {

		$('.opening-hours-header .header .date').empty().append(response.date);
		$('.loader').hide();
		$('.opening-hours-widget').replaceWith( response.hours );

	});
}

function getFormattedDate( date ) {
	var str = date.getFullYear() + "-" + getFormattedPartTime(date.getMonth()+1) + "-" + getFormattedPartTime(date.getDate());
	return str;
}

function getFormattedPartTime(partTime){
	if (partTime<10)
		return "0"+partTime;
	return partTime;
}

})( jQuery );
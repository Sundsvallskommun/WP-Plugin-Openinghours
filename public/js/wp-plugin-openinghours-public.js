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

				var location = $(this).closest('.opening-hours-wrapper').find('.current-location').html();
				load_opening_hours( this, location, date );


			});

			$('.right-arrow').on('click', function() {

				var date = new Date( $(this).closest('.opening-hours-wrapper').find('.current-date').html() );
				date.setDate( date.getDate() + 1 );
				date = getFormattedDate( date );

				var location = $(this).closest('.opening-hours-wrapper').find('.current-location').html();
				load_opening_hours( this, location, date );


			});

			$('.left-arrow').on('click', function() {

				var date = new Date( $(this).closest('.opening-hours-wrapper').find('.current-date').html() );
				date.setDate( date.getDate() - 1 );
				date = getFormattedDate( date );

				var location = $(this).closest('.opening-hours-wrapper').find('.current-location').html();
				load_opening_hours( this, location, date );

			});

		}


	} );



function load_opening_hours( elm, location, date ) {
	//var location = $( current ).find(' .current-location').html();
	console.log( elm );

	var wrapper = $(elm).closest('.opening-hours-wrapper');

	wrapper.find('.current-date').html( date );
	wrapper.closest('.opening-hours-wrapper').find('.loader').show();
	wrapper.closest('.opening-hours-wrapper').find('.opening-hours-widget .list-group-item').fadeTo('medium', 0.2);

	$.post( WP_PLUGIN_OPENINGHOURS.ajax_url, {

		'action': 'load_opening_hours',
		'nonce': WP_PLUGIN_OPENINGHOURS.ajax_nonce,
		'date': date,
		'location': location

	}, function( response ) {

		wrapper.closest('.opening-hours-wrapper').find('.opening-hours-header .header .date').empty().append(response.date + '<span class="icon"><i class="material-icons">date_range</i></span>');
		wrapper.closest('.opening-hours-wrapper').find('.loader').hide();
		wrapper.closest('.opening-hours-wrapper').find('.opening-hours-widget').replaceWith( response.hours );

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
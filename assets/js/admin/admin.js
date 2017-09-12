jQuery( document ).ready(function($) {

	jQuery( '.rockpress-rest-controllers.button' ).click( function() {
		jQuery('button#contextual-help-link').trigger('click');
		return false;
	});

	jQuery( '#rockpress-rock-connection-test-button' ).click( function() {
		jQuery( '#rockpress-rock-connection-test-button' ).text(rockpress_vars.messages.running).attr('disabled', true).addClass('updating-message');
		data = {
			action: 'rockpress_check_services',
			nonce: rockpress_vars.nonce
		};
		jQuery.post( ajaxurl, data,  function( response ) {
			jQuery( '#rockpress-rock-connection-test-results' ).html( response );
			jQuery( '#rockpress-rock-connection-test-button' ).text(rockpress_vars.messages.done).removeClass('updating-message').addClass('updated-message');
			setTimeout(function(){
				jQuery( '#rockpress-rock-connection-test-button' ).text(rockpress_vars.messages.connection_test_button).attr('disabled', false).removeClass('updated-message');
			}, 3000);
		});
		return false;
	});

});

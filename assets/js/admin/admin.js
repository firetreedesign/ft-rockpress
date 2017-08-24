jQuery( document ).ready(function($) {

	jQuery( '.rockpress-rest-controllers.button' ).click( function() {
		jQuery('button#contextual-help-link').trigger('click');
		return false;
	});

	jQuery( '#rockpress-rock-connection-test-button' ).click( function() {
		jQuery( '#rockpress-rock-connection-test-loading' ).show();
		jQuery( '#rockpress-rock-connection-test-button' ).attr('disabled', true);
		data = {
			action: 'rockpress_check_services',
			nonce: rockpress_vars.nonce
		};
		jQuery.post( ajaxurl, data,  function( response ) {
			jQuery( '#rockpress-rock-connection-test-results' ).html( response );
			jQuery( '#rockpress-rock-connection-test-loading' ).hide();
			jQuery( '#rockpress-rock-connection-test-button' ).attr('disabled', false);
		});
		return false;
	});

});

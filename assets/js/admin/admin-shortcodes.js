/* global ajaxurl, jQuery, scShortcodes, tinymce */

// var jq = jQuery.noConflict();

// var scShortcode, scButton;
var RockPressShortcode, RockPressShortcodeButton;

// var scForm = {
var RockPressShortcodeForm = {

	open: function( editor_id )
	{
		var editor = tinymce.get( editor_id );

		if ( ! editor ) {
			return;
		}

		var data, field, required, valid, win;

		data = {
			action    : 'rockpress_shortcode',
			shortcode : RockPressShortcode
		};

		jQuery.post( ajaxurl, data, function( response )
		{
			// what happens if response === false?
			if( ! response.body ) {
				console.error( 'Bad AJAX response!' );
				return;
			}

			if( response.body.length === 0 ) {
				window.send_to_editor( '[' + response.shortcode + ']' );

				RockPressSCForm.destroy();

				return;
			}

			var popup = {
				title   : response.title,
				body    : response.body,
				classes: 'rockpress-sc-popup',
				minWidth: 320,
				buttons : [
					{
						text    : response.ok,
						classes : 'primary rockpress-sc-primary',
						onclick : function()
						{
							// Get the top most window object
							win = editor.windowManager.getWindows()[0];

							// Get the shortcode required attributes
							required = RockPressShortcodes[ RockPressShortcode ];

							valid = true;

							// Do some validation voodoo
							for( var id in required ) {
								if( required.hasOwnProperty( id ) ) {

									field = win.find( '#' + id )[0];

									if( typeof field !== 'undefined' && field.state.data.value === '' ) {

										valid = false;

										alert( required[ id ] );

										break;
									}
								}
							}

							if( valid ) {
								win.submit();
							}
						}
					},
					{
						text    : response.close,
						onclick : 'close'
					},
				],
				onsubmit: function( e )
				{
					var attributes = '';

					for( var key in e.data ) {
						if( e.data.hasOwnProperty( key ) && e.data[ key ] !== '' ) {
							attributes += ' ' + key + '="' + e.data[ key ] + '"';
						}
					}

					// Insert shortcode into the WP_Editor
					window.send_to_editor( '[' + response.shortcode + attributes + ']' );
				},
				onclose: function()
				{
					RockPressShortcodeForm.destroy();
				}
			};

			// Change the buttons if server-side validation failed
			if( response.ok.constructor === Array ) {
				popup.buttons[0].text    = response.ok[0];
				popup.buttons[0].onclick = 'close';
				delete popup.buttons[1];
			}

			editor.windowManager.open( popup );
		});
	},

	destroy: function()
	{
		var tmp = jQuery( '#rockpress-sc-temp' );

		if( tmp.length ) {
			tinymce.get( 'rockpress-sc-temp' ).remove();
			tmp.remove();
		}
	}
	
};

jQuery( function( $ )
{
	var RockPressShortcodeOpen = function()
	{
		RockPressShortcodeButton.addClass( 'active' ).parent().find( '.rockpress-sc-menu' ).show();
	};

	var RockPressShortcodeClose = function()
	{
		if( typeof RockPressShortcodeButton !== 'undefined' ) {
			RockPressShortcodeButton.removeClass( 'active' ).parent().find( '.rockpress-sc-menu' ).hide();
		}
	};

	$( document ).on( 'click', function( e )
	{
		if( !$( e.target ).closest( '.rockpress-sc-wrap' ).length ) {
			RockPressShortcodeClose();
		}
	});

	$( document ).on( 'click', '.rockpress-sc-button', function( e )
	{
		e.preventDefault();

		RockPressShortcodeButton = $( this );

		if( RockPressShortcodeButton.hasClass( 'active' ) ) {
			RockPressShortcodeClose();
		}
		else {
			RockPressShortcodeOpen();
		}
	});

	$( document ).on( 'click', '.rockpress-sc-shortcode', function( e )
	{
		e.preventDefault();

		// rockpressShortcode is used by rockpressForm to trigger the correct popup
		RockPressShortcode = $( this ).attr( 'data-shortcode' );

		if( RockPressShortcode ) {
			if( ! tinymce.get( window.wpActiveEditor ) ) {

				if( ! $( '#rockpress-sc-temp' ).length ) {

					$( 'body' ).append( '<textarea id="rockpress-sc-temp" style="display: none;" />' );

					tinymce.init({
						mode     : "exact",
						elements : "rockpress-sc-temp",
						plugins  : ['rockpress_shortcode', 'wplink']
					});
				}

				setTimeout( function() { tinymce.execCommand( 'RockPress_Shortcode' ); }, 200 );
			}
			else {
				tinymce.execCommand( 'RockPress_Shortcode' );
			}

			setTimeout( function() { RockPressShortcodeClose(); }, 100 );
		}
		else {
			console.warn( 'That is not a valid shortcode link.' );
		}
	});
});

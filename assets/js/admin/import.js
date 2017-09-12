jQuery( document ).ready(function($) {

	/**
	 * RockPress Import
	 */
	var RockPress_Import = new function() {

		this.init = function() {
			var self = this;
			jQuery( '#rockpress-manual-import-button' ).on( 'click', { self: self }, self.startImport );
			if ( 'running' == jQuery( '#rockpress-manual-import-button' ).attr('data-rockpress-status') ) {
				self.disableButton();
				self.checkProgress( self );
			}
		};

		this.startImport = function( event ) {
			var self = event.data.self;
			self.disableButton();
			data = {
				action: 'rockpress_import',
				nonce: rockpress_vars.nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				if ( 'started' === response ) {
					self.updateProgress( [{'text':rockpress_vars.messages.process_running}], 'running');
					self.checkProgress( self );
				} else {
					self.enableButton();
				}
			});
			return false;
		}

		this.updateProgress = function( content, status ) {
			var container	= jQuery('#rockpress-import-status');
			var notice		= jQuery('<div />');
			var p			= jQuery('<p />');

			notice.attr('class', 'notice notice-info');

			for ( var key in content ) {
				if ( content.hasOwnProperty(key) ) {
					var br = jQuery('<br />');
					if ( content[key].hasOwnProperty('element') ) {
						var element = jQuery('<' + content[key]['element'] + ' />');
						element.text( content[key]['text'] );
						p.append(element);
						p.append(br);
					} else {
						p.text( content[key]['text'] );
						p.append(br);
					}
				}
			}

			notice.append(p);
			container.html('');
			container.append(notice);
		}

		this.progress = function( content ) {
			jQuery('#rockpress-import-status').html( content );
		}

		this.enableButton = function() {
			jQuery( '#rockpress-manual-import-button' ).text(rockpress_vars.messages.done).removeClass('updating-message').addClass('updated-message');
			setTimeout(function(){
				jQuery( '#rockpress-manual-import-button' ).text(rockpress_vars.messages.manual_import_button).attr('disabled', false).removeClass('updated-message');
			}, 3000);
		}

		this.disableButton = function() {
			jQuery( '#rockpress-manual-import-button' ).text(rockpress_vars.messages.running).attr('disabled', true).addClass('updating-message');
		}

		this.checkProgress = function( self ) {
			var checkProgressHandle = setInterval( function() {
				data = {
					action: 'rockpress_import_status',
					nonce: rockpress_vars.nonce
				};

				jQuery.post( ajaxurl, data,  function( response ) {
					if ( 'false' === response ) {
						clearInterval( checkProgressHandle );
						self.getLastImport();
						self.enableButton();
						self.updateProgress( [{'text':rockpress_vars.messages.done}], 'done');
						setTimeout(function(){
							self.progress('');
						}, 3000);
						return;
					}
					self.updateProgress( response, 'running');
				} );
			}, 3000 );
		}

		this.getLastImport = function() {
			data = {
				action: 'rockpress_last_import',
				nonce: rockpress_vars.nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				jQuery('.rockpress-last-import').text( response ).css( 'font-weight', 'bold' );
				setTimeout(function(){
	                jQuery('.rockpress-last-import').css( 'font-weight', 'normal' );;
	            }, 3000);
			});
		}

	}
	RockPress_Import.init();

	/**
	 * RockPress Import
	 */
	var RockPress_Import_Reset = new function() {

		this.init = function() {
			var self = this;
			jQuery( '#rockpress-reset-import-button' ).on( 'click', { self: self }, self.confirm );
		};

		this.confirm = function( event ) {
			var self = event.data.self;
			if ( confirm( rockpress_vars.messages.reset_import_confirmation ) == true) {
		        self.startReset();
		    }
		}

		this.startReset = function() {
			jQuery( '#rockpress-reset-import-button' ).text(rockpress_vars.messages.running).attr('disabled', true).addClass('updating-message');
			data = {
				action: 'rockpress_reset_import',
				nonce: rockpress_vars.nonce
			};

			jQuery.post( ajaxurl, data,  function( response ) {
				jQuery('.rockpress-last-import').text( response ).css( 'font-weight', 'bold' );
				jQuery( '#rockpress-reset-import-button' ).text(rockpress_vars.messages.done).removeClass('updating-message').addClass('updated-message');
				setTimeout(function(){
					jQuery('.rockpress-last-import').css( 'font-weight', 'normal' );
					jQuery( '#rockpress-reset-import-button' ).text(rockpress_vars.messages.reset_import_button).attr('disabled', false).removeClass('updated-message');
				}, 3000);
			});
			return false;
		}

	}
	RockPress_Import_Reset.init();

});

/* global jpConnection, jQuery, confirm */

(function( $, jpConnection ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////
	var originPoint,
		ajaxNonce,
		action,
		data = {
			'connectionLogic'   : jpConnection.connectionLogic,
			'isActive'          : jpConnection.jetpackIsActive,
			'isAdmin'           : jpConnection.isAdmin,
			'masterComData'     : jpConnection.masterComData,
			'userComData'       : jpConnection.userComData
		};

	$( document ).ready(function () {

		ajaxNonce = jpConnection.myConnectionNonce;
		action = 'jetpack_my_connection_ajax';

		$( '#jp-connection' ).on( 'click keypress', function(e) {

			renderModalTemplate( data );

			originPoint = this;

			$( '#jp-connection-modal, .shade' ).show();
			$( '#jp-connection-modal' )[0].setAttribute( 'tabindex', '0' );
			$( '#jp-connection-modal' ).focus();
			$( 'body' ).css( 'overflow', 'hidden' );

			e.preventDefault();

			// Call the ajax function to switch master user
			$( '#set-self-as-master' ).click(function(){
				setSelfAsMaster();
			});

		});

	});

	function renderModalTemplate( data ) {
		$( '#jp-connection-modal' ).html( wp.template( 'connection-modal' )( data )   );
		// Save the focused element, then shift focus to the modal window.
		closeConnectionModal();
		confirmJetpackDisconnect();
	}

	/*
	The ajax function to handle switching the master user
	 */
	function setSelfAsMaster() {
		if ( ! confirm( 'Are you sure?' ) ) {
			return false;
		} else {
			$( '#jp-connection-modal' ).html( wp.template( 'connection-modal-loading' ) );
			var postData = {
				switchMasterUser : 'switch-master-user',
				action: action,
				myConnectionNonce: ajaxNonce
			};
			$.post( jpConnection.ajaxurl, postData, function( response ) {
				renderModalTemplate( response );
				data = response;
			}, 'json' );
		}
	}


	/*
	The function used to handle closing the my connection modal
	 */
	function closeConnectionModal() {
		// Clicking outside modal, or close X closes modal
		$( '.shade, #jp-connection-modal .close' ).on( 'click', function () {
			$( '.shade, #jp-connection-modal' ).hide();
			$( '.manage-right' ).removeClass( 'show' );

			// Disable scroll
			$( 'body' ).css( 'overflow', '' );

			originPoint.focus();

			$( '#jp-connection-modal' )[0].removeAttribute( 'tabindex' );
			return false;
		});

		$( window ).on( 'keydown', function( e ) {
			// If pressing ESC close the modal
			if ( 27 === e.keyCode ) {
				$( '.shade, .modal' ).hide();
				$( '.manage-right' ).removeClass( 'show' );

				// Enable scroll
				$( 'body' ).css( 'overflow', '' );

				originPoint.focus();
				$( '#jp-module-modal' )[0].removeAttribute( 'tabindex' );
			}
		});
	}

	/*
	 The function used to display the disconnect confirmation and support buttons
	 */
	function confirmJetpackDisconnect() {
		$( '#jetpack-disconnect' ).click( function() {
			$( '#jetpack-disconnect-content' ).show();
			$( '#my-connection-content' ).hide();
		});
	}

})( jQuery, jpConnection );

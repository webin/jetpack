/* global jpConnection, jQuery, confirm */

(function( $, jpConnection ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////
	var originPoint,
		ajaxNonce,
		action;

	$( document ).ready(function () {

		ajaxNonce = jpConnection.myConnectionNonce;
		action = 'jetpack_my_connection_ajax';

		$('#jp-connection').on( 'click keypress', function(e) {

			renderModalTemplate( jpConnection );

			originPoint = this;

			$( '#jp-connection-modal, .shade' ).show();
			$( '#jp-connection-modal' )[0].setAttribute( 'tabindex', '0' );
			$( '#jp-connection-modal' ).focus();

			e.preventDefault();

			// Call the ajax function to switch master user
			$( '#set-self-as-master' ).click(function(){
				setSelfAsMaster();
			});

		});

	});

	function renderModalTemplate( templateData ) {
		var data = {
			'isMasterUser'      : templateData.connectionLogic.is_master_user,
			'masterUserLink'    : templateData.connectionLogic.master_user_link,
			'isUserConnected'   : templateData.connectionLogic.is_user_connected,
			'userToken'         : templateData.connectionLogic.user_token,
			'isActive'          : templateData.jetpackIsActive,
			'isAdmin'           : templateData.isAdmin,
			'masterComData'     : templateData.masterComData,
			'userComData'       : templateData.userComData
		};
		$('#jp-connection-modal').html( wp.template( 'connection-modal' )(data)   );
		// Save the focused element, then shift focus to the modal window.
		closeConnectionModal();
	};

	/*
	The ajax function to handle switching the master user
	 */
	function setSelfAsMaster() {
		if ( ! confirm( 'Are you sure?' ) ) {
			return false;
		} else {
			$('#jp-connection-modal').html( wp.template( 'connection-modal-loading') );
			$( '.spinner' ).show();
			var postData = {
				switchMasterUser : 'switch-master-user',
				action: action,
				myConnectionNonce: ajaxNonce
			};
			$.post( jpConnection.ajaxurl, postData, function (response) {
				renderModalTemplate( response );
				$( '.spinner' ).hide();
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

			originPoint.focus();

			$( '#jp-connection-modal' )[0].removeAttribute( 'tabindex' );
			return false;
		});

		$( window ).on( 'keydown', function( e ) {
			// If pressing ESC close the modal
			if ( 27 === e.keyCode ) {
				$( '.shade, #jp-connection-modal' ).hide();
				$( '.manage-right' ).removeClass( 'show' );
				originPoint.focus();
				$( '#jp-connection-modal' )[0].removeAttribute( 'tabindex' );
			}
		});
	}

})( jQuery, jpConnection );

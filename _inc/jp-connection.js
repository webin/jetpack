/* global jpConnection, jQuery, confirm */

(function( $, jpConnection ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////
	var originPoint,
		data;

	$( document ).ready(function () {
		var fetchingData = false;

		data = {
			'action'            : 'jetpack_my_connection_ajax',
			'isMasterUser'      : jpConnection.connectionLogic.is_master_user,
			'masterUserLink'    : jpConnection.connectionLogic.master_user_link,
			'isUserConnected'   : jpConnection.connectionLogic.is_user_connected,
			'userToken'         : jpConnection.connectionLogic.user_token,
			'isActive'          : jpConnection.jetpackIsActive,
			'isAdmin'           : jpConnection.isAdmin,
			'myConnectionNonce' : jpConnection.myConnectionNonce,
			'masterComData'     : jpConnection.masterComData,
			'userComData'       : jpConnection.userComData
		};
		$('#jp-connection').on( 'click keypress', function(e) {
			$('#jp-connection-modal').empty().html( wp.template( 'connection-modal' )( $.extend( {
				isMasterUser    : data.isMasterUser,
				masterUserLink  : data.masterUserLink,
				isUserConnected : data.isUserConnected,
				userToken       : data.userToken,
				isActive        : data.isActive,
				isAdmin         : data.isAdmin,
				masterComData   : data.masterComData,
				userComData     : data.userComData
			})));

			originPoint = this;

			$( '#jp-connection-modal, .shade' ).show();
			$( '#jp-connection-modal' )[0].setAttribute( 'tabindex', '0' );
			$( '#jp-connection-modal' ).focus();

			e.preventDefault();

			// Save the focused element, then shift focus to the modal window.
			closeConnectionModal();

			// Call the ajax function to switch master user
			$( '#set-self-as-master' ).click(function(){
				setSelfAsMaster();
			});

		});

	});

	/*
	The ajax function to handle switching the master user
	 */
	function setSelfAsMaster() {
		if ( ! confirm( 'Are you sure?' ) ) {
			return false;
		} else {
			$( '.spinner' ).show();
			data.switchMasterUser = 'switch-master-user';
			$.post( jpConnection.ajaxurl, data, function (response) {
				$( '#my-connection-content' ).html( response );
				$( '.spinner' ).hide();
			});
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

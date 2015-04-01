(function( $ ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

	var data;

	$( document ).ready(function () {

		data = {
			'action'            : 'jetpack_my_connection_ajax',
			'isMasterUser'      : jpConnection.connectionLogic.is_master_user,
			'masterUserLink'    : jpConnection.connectionLogic.master_user_link,
			'isUserConnected'   : jpConnection.connectionLogic.is_user_connected,
			'userToken'         : jpConnection.connectionLogic.user_token,
			'isActive'          : jpConnection.jetpackIsActive,
			'isAdmin'           : jpConnection.isAdmin,
			'myConnectionNonce' : jpConnection.myConnectionNonce,
			'masterComData'     : jpConnection.masterComData
		};
		$('#jp-connection').click(function(){
			$('#jp-connection-modal').empty().html( wp.template( 'connection-modal' )( $.extend( {
				isMasterUser    : data.isMasterUser,
				masterUserLink  : data.masterUserLink,
				isUserConnected : data.isUserConnected,
				userToken       : data.userToken,
				isActive        : data.isActive,
				isAdmin         : data.isAdmin,
				masterComData   : data.masterComData
			} ) ) );
			$('#jp-connection-modal, .shade').show();
			$('#jp-connection-modal')[0].setAttribute( 'tabindex', '0' );
			$('#jp-connection-modal').focus();

			// Call the ajax function to switch master user
			$('#set-self-as-master').click(function(){
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
				console.log( response );

				$( '#my-connection-content' ).html( response );

				$( '.spinner' ).hide();
			});
		}
	}

})( jQuery );

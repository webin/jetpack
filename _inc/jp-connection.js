(function( $ ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

	var data;

	$( document ).ready(function () {

		data = {
			'isMasterUser'    : jpConnection.connectionLogic.is_master_user,
			'masterUserLink'  : jpConnection.connectionLogic.master_user_link,
			'isUserConnected' : jpConnection.connectionLogic.is_user_connected,
			'userToken'       : jpConnection.connectionLogic.user_token
		};

		$('#jp-connection').click(function(){
			$('#jp-connection-modal').empty().html( wp.template( 'connection-modal' )( $.extend( {
				isMasterUser    : data.isMasterUser,
				masterUserLink  : data.masterUserLink,
				isUserConnected : data.isUserConnected,
				userToken       : data.userToken
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
			alert( 'do some ajaxy stuff here' );
		}
	}

})( jQuery );

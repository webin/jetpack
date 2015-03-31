(function( $ ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

	$( document ).ready(function () {

		$('#jp-connection').click(function(){
			$('#jp-connection-modal').empty().html( wp.template( 'connection-modal' ) );
			$('#jp-connection-modal, .shade').show();
			$('#jp-connection-modal')[0].setAttribute( 'tabindex', '0' );
			$('#jp-connection-modal').focus();
		});

	});

	///////////////////////////////////////
	// FUNCTIONS
	///////////////////////////////////////



})( jQuery );

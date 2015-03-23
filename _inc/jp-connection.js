(function( $ ) {

	///////////////////////////////////////
	// INIT
	///////////////////////////////////////

	$( document ).ready(function () {

		var $modal = $( '#jp-connection-modal' );
		$('#jp-connection').click(function(){
			$modal.empty().html( wp.template( 'modal' ) );
		});

	});

	///////////////////////////////////////
	// FUNCTIONS
	///////////////////////////////////////



})( jQuery );

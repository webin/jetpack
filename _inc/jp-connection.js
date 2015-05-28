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

        renderPageTemplate( data );

        // Call the ajax function to switch master user
        $( '#set-self-as-master' ).click(function(){
            setSelfAsMaster();
        });

    });

    function renderPageTemplate( data ) {
        $( '#my-connection-page-template' ).html( wp.template( 'connection-page' )( data ) );
        // Save the focused element, then shift focus to the modal window.
        confirmJetpackDisconnect();
    }

    /*
     The ajax function to handle switching the master user
     */
    function setSelfAsMaster() {
        if ( ! confirm( 'Are you sure?' ) ) {
            return false;
        } else {
            $( '#my-connection-page-template' ).html( wp.template( 'connection-page-loading' ) );
            var postData = {
                switchMasterUser : 'switch-master-user',
                action: action,
                myConnectionNonce: ajaxNonce
            };
            $.post( jpConnection.ajaxurl, postData, function( response ) {
                renderPageTemplate( response );
                data = response;
            }, 'json' );
        }
    }


    /*
     The function used to display the disconnect confirmation and support buttons
     */
    function confirmJetpackDisconnect() {
        $( '#jetpack-disconnect' ).click( function() {
            $( '#jetpack-disconnect-content' ).show();
            $( '#my-connection-page-template' ).hide();
        });
    }

})( jQuery, jpConnection );
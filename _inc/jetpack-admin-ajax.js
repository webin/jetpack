jQuery(document).ready(function($) {

    // Toggle miguel on and off
    $( '.toggle-miguel' ).click(function() {
        $('.spinner').show();

        var data = {
            'action': 'jetpack_admin_ajax',
            'miguelCanFly': 'toggle',
            'jetpack_quickconfig_nonce': jetpack_quickconfig_ajax_object.jetpack_quickconfig_nonce
        };

        $.post(ajaxurl, data, function(response) {
            console.log(response, data);
            $( '.ajax-result-miguel' ).html( response );
            $('.spinner').hide();
        });

        return false;
    });
    /*
    $( '.turn-miguel-off' ).click(function() {
        var data = {
            'action': 'jetpack_admin_ajax',
            'miguelCanFly': 0
        };

        $.post(ajaxurl, data, function(response) {
            console.log(response);
            $( ".ajax-result-miguel" ).html( response );
        });

        return false;
    });

    $( '.turn-miguel-on' ).click(function() {
        var data = {
            'action': 'jetpack_admin_ajax',
            'miguelCanFly': true
        };

        $.post(ajaxurl, data, function(response) {
            console.log(response);
            $( ".ajax-result-miguel" ).html( response );
        });

        return false;
    });
    */
});
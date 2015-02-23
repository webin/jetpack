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
            $( '.ajax-result-miguel' ).html( response );
            $('.spinner').hide();
        });

        return false;
    });
});
(function( $ ) {
    $('body').on('submit', '.tsunoa-license-form', function(e) {
        e.preventDefault();

        var $this = $(this);
        var submit = $this.find('input[type="submit"]');
        var action = $this.find('input[name="action"]').val(); // activate or deactivate

        if( $this.find('input[type="text"][name$="-license-key"]').val() == '' ) {
            tsunoa_message('Insert a valid license', 'error', $this);

            return false;
        }

        if( $this.find('.tsunoa-message').length ) {
            $this.find('.tsunoa-message').fadeOut();
        }

        // Disable button, preventing more clicks during ajax request
        submit.prop('disabled', true);

        // Show the spinner
        $this.find('.spinner').addClass('is-active');

        $.ajax({
            url: tsunoa.ajax_url,
            method: 'post',
            data: $this.serialize(),
            cache: false,
            success: function (response) {
                console.log(response);

                // Re-enable the load more button
                submit.prop('disabled', false);

                // Remove spinner
                $this.find('.spinner').removeClass('is-active');

                if( response !== null && response.license !== undefined ) {
                    var old_action = ( action == 'tsunoa_deactivate_license' ) ? 'deactivate' : 'activate';
                    var new_action = ( action == 'tsunoa_deactivate_license' ) ? 'activate' : 'deactivate';

                    // On success
                    if( response.success == true && ( old_action == 'activate' && response.license == 'valid' )) {
                        var license = $this.find('input[name$="-license-key"]').val();

                        // Switch action
                        $this.find('input[name="action"]').val( 'tsunoa_' + new_action + '_license' );

                        // License read only property
                        $this.find('input[name$="-license-key"]').val(license.substring(0, 4) + '*'.repeat(license.length-8) + license.substring(license.length-4, license.length));
                        $this.find('input[name$="-license-key"]').prop('readOnly', ( new_action == 'deactivate' ));

                        // Submit button
                        submit.attr('name', submit.attr('name').replace( '-' + old_action, '-' + new_action ));
                        submit.attr('id', submit.attr('id').replace( '-' + old_action, '-' + new_action ));
                        submit.val(submit.val().replace( tsunoa_capitalize(old_action), tsunoa_capitalize(new_action) ));

                        // Success message
                        tsunoa_message('License ' + old_action  + 'd successfully', 'success', $this);
                    } else {
                        // Error message
                        if( old_action == 'activate' ) {
                            tsunoa_message('Invalid license', 'error', $this);
                        } else {
                            tsunoa_message('Can not deactivate license', 'error', $this);
                        }
                    }
                } else {
                    tsunoa_message( 'Something wrong happens', 'error', $this );
                }
            }
        });
    });

    function tsunoa_capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function tsunoa_message( text, type, form ) {
        if( form.find('.tsunoa-message').length == 0 ) {
            $('<span class="tsunoa-message" style="display: none;"></span>').insertAfter( form.find('.spinner') );
        }

        form.find('.tsunoa-message')
            .removeClass('error').removeClass('success')
            .addClass(type)
            .html(text).fadeIn();
    }
})( jQuery );
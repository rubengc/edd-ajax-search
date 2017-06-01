(function( $ ) {
    /* CMB2 EDD thumbnail size preview utility
     Automatically updates the thumbnail size of a download parts form

     Setup:
     - Inside a CMB2 meta box setup a field that outputs the contents of uframework_edd_download_template_form()
     - Also and inside the same meta box, add a field with id = download_thumbnail_size
     -------------------------------------------------------- */
    $('body').on('change', '.cmb2-id-download-thumbnail-size input[name="download_thumbnail_size"]', function() {
        var target = $(this).closest('.cmb2-metabox').find('.download-parts .download-part-thumbnail');

        if( target !== undefined && $(this).val().length && ! isNaN( $(this).val() ) ) {
            target
                .attr('style', 'width: ' + $(this).val() + 'px; height: ' + $(this).val() + 'px;' )
                .find('span')
                    .attr('style', 'line-height: ' + $(this).val() + 'px;' )
                    .html($(this).val() + 'x' + $(this).val());
        }
    });
})( jQuery );
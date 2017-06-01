(function($) {
    $(".edd-ajax-search-search").each(function() {
        $(this).autocomplete({
            delay: 250, // Delay between searches
            minLength: parseInt(edd_ajax_search.minimum_characters), // Minimum characters to start search
            source: function(request, response) {
                var form_serialized = $(this.element).closest('#edd-ajax-search-form').serialize();

                // Ajax request to edd_ajax_search action
                $.ajax({
                    url: edd_ajax_search.ajax_url + '?action=edd_ajax_search&nonce=' + edd_ajax_search.nonce,
                    type: 'GET',
                    dataType: 'json',
                    data: form_serialized,
                    error: function() {
                        response();
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui){
                // If selected item has a link attr, then navigate to this url
                if(ui.item.link !== undefined) {
                    window.location.href = ui.item.link;
                }
            }
        }).autocomplete( 'instance' )._renderItem = function( ul, item ) {
            // Autocomplete class
            $(ul).addClass('edd-ajax-search-autocomplete');

            // Append the item html inside a li under ul element
            return $("<li>")
                .append(item.html)
                .appendTo(ul);
        };
    });
})(jQuery);
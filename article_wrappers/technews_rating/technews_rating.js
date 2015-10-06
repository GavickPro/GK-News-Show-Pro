jQuery(document).ready(function() {
    jQuery(document).find('.gk-technews-rating').each(function(i, widget) {
        widget = jQuery(widget);

        if(!widget.hasClass('active')) {
            widget.addClass('active');
        }
    });
});

// EOF
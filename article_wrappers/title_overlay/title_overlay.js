// GK NSP Title Overlay
(function() {
    "use strict";
    jQuery(document).ready(function() {
        jQuery(document).find('.gk-title-overlay').each(function(i, widget) {
            widget = jQuery(widget);

            if (!widget.hasClass('active')) {
                widget.addClass('active');
                gkNspTitleOverlayInit(widget);
            }
        });
    });

    var gkNspTitleOverlayInit = function(widget) {
        widget = jQuery(widget);
        // add the basic events
        widget.find('figure').each(function(i, figure) {
            figure = jQuery(figure);
            var overlay = widget.find('.gk-img-overlay');
            overlay.click(function() {
                window.location.href = jQuery(figure.find('a').first()).attr('href');
            });
        });

        widget.mouseenter(function() {
            widget.addClass('hover');
        });

        widget.mouseleave(function() {
            widget.removeClass('hover');
        });
    };
})();

// GK NSP Event lists
(function() {
    "use strict";
    jQuery(document).ready(function() {
        jQuery(document).find('.gk-nsp-events').each(function(i, widget) {
            widget = jQuery(widget);

            if (!widget.hasClass('active')) {
                widget.addClass('active');
                gkNspEventsInit(widget);
            }
        });
    });

    var gkNspEventsInit = function(widget) {
    	widget = jQuery(widget);
    	// add the basic events
    	var gk_events = widget.find('time');
    	
    	if(gk_events.length) {
    		gk_events.each(function(i, event) {
    			event = jQuery(event);
    			var progress = event.parent().find('.gk-events-list-progress');
    			var progress_bar = jQuery('<div></div>');
    			progress_bar.appendTo(progress)
    			var end = event.attr('datetime').split('T')[0].split('-');
    			var end_date = new Date(end[0], end[1]-1, end[2], 0, 0);
    			
    			var start = event.attr('data-start').split('T')[0].split('-');
    			var start_date = new Date(start[0], start[1]-1, start[2], 0, 0);
    					
    			var diff = end_date - start_date;
    			var current = new Date();
    			var current_date = new Date(current.getFullYear(), current.getMonth(), current.getDate(), 0, 0);
    			var progress = 1 - Math.round(((end_date - current_date) / diff) * 1000) / 1000;
    			progress = Math.round(progress * 1000) / 1000;
    			
    			setTimeout(function() {
    				progress_bar.css('width', progress * 100 + "%");
    			}, 1000);
    		});
    	}
    };
    
})();

// GK NSP Highlighter
(function() {
    "use strict";
    jQuery(document).ready(function() {
        jQuery(document).find('.gk-nsp-highlighter').each(function(i, widget) {
            widget = jQuery(widget);

            if (!widget.hasClass('active')) {
                widget.addClass('active');
                gkNspHighlighterInit(widget);
            }
        });
    });

    var gkNspHighlighterInit = function(widget) {
        widget = jQuery(widget);
        // check if the UI exists
        if(widget.find('.gk-nsp-highlighter-ui a').length > 0) {
	        // get the basic params
	        var anim_speed = widget.attr('data-speed');
	        var anim_interval = widget.attr('data-interval');
	        var current = 0;
	        // add the animation events
	        var btn_prev = jQuery(widget.find('.gk-nsp-highlighter-ui a')[0]);
	        var btn_next = jQuery(widget.find('.gk-nsp-highlighter-ui a')[1]);
	        var items = widget.find('.gk-nsp-highlighter li');
	        var first_item = jQuery(items[0]);
	        var blank_animation = false;
	
	        btn_prev.click(function(e) {
	            if(e) {
	                e.preventDefault();
	            }
	
	            var prev = current;
	            current = current > 0 ? current - 1 : items.length - 1;
	            first_item.animate({
	                'margin-top': current * -1 * first_item.outerHeight()  
	            }, anim_speed);
	
	            jQuery(items[prev]).removeClass('active');
	            jQuery(items[current]).addClass('active');
	
	            if(e) {
	                blank_animation = true;
	            }
	        });
	
	        btn_next.click(function(e) {
	            if(e) {
	                e.preventDefault();
	            }
	
	            var prev = current;
	            current = current < items.length - 1 ? current + 1 : 0;
	            first_item.animate({
	                'margin-top': current * -1 * first_item.outerHeight()  
	            }, anim_speed);
	
	            jQuery(items[prev]).removeClass('active');
	            jQuery(items[current]).addClass('active');
	
	            if(e) {
	                blank_animation = true;
	            }
	        });
	
	        if(anim_interval > 0) {
	            setTimeout(function() {
	                gkNspHighlighterAutoanim();
	            }, anim_interval);
	        }
	
	        function gkNspHighlighterAutoanim() {
	            if(!blank_animation) {
	                var prev = current;
	                current = current < items.length - 1 ? current + 1 : 0;
	                first_item.animate({
	                    'margin-top': current * -1 * first_item.outerHeight()  
	                }, anim_speed);
	
	                jQuery(items[prev]).removeClass('active');
	                jQuery(items[current]).addClass('active');
	
	                setTimeout(function() {
	                    gkNspHighlighterAutoanim();
	                }, anim_interval);
	            } else {
	                blank_animation = false;
	                
	                setTimeout(function() {
	                    gkNspHighlighterAutoanim();
	                }, anim_interval);
	            }
	        }
        }
    };
})();

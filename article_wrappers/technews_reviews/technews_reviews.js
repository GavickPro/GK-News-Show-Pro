jQuery(document).ready(function() {
    jQuery(document).find('.gk-technews-reviews').each(function(i, widget) {
        widget = jQuery(widget);

        if(!widget.hasClass('active')) {
            widget.addClass('active');
            gk_technews_reviews_init(widget);
        }
    });
});

function gk_technews_reviews_init(widget) {
    var list = jQuery('.gk-sidebar-list li');
    var items = jQuery('.gk-content-review');
    var current = 0;
    var circles = [];
    
    list.each(function(i, item) {
        if(ProgressBar && jQuery(items[current]).find('.gk-review-sum-value').length) {
            circles[i] = new ProgressBar.Circle(jQuery(items[i]).find('.gk-review-sum-value')[0], {
                color: '#07c958',
                strokeWidth: 4,
                trailWidth: 4,
                duration: 1500,
                easing: 'easeInOut'
            });
        }
    
        jQuery(item).find('span').click(function(e) {
            item = jQuery(item);
            e.preventDefault();
            list.removeClass('gk-active');
            item.addClass('gk-active');
            current = i;
            items.removeClass('gk-active');
            jQuery(items[current]).addClass('gk-active');
            
            if(ProgressBar) {
                var sum = jQuery(items[i]).find('.gk-review-sum-value').first();
                circles[current].set(0);
                circles[current].animate(jQuery(sum).attr('data-final'));
            }
        });
    });
    
    circles[0].animate(jQuery(jQuery(items[0]).find('.gk-review-sum-value')[0]).attr('data-final'));
    
    if(widget.attr('data-autoanim') == '1') {
        gk_technews_reviews_autoanim(widget);
    }
}

function gk_technews_reviews_autoanim(widget) {
    setTimeout(function() {
        var list = jQuery('.gk-sidebar-list');
        
        if(list.find('.gk-active').next().length) {
            list.find('.gk-active').next().find('span').trigger('click');
        } else {
            list.find('li').first().find('span').trigger('click');
        }
        
        gk_technews_reviews_autoanim(widget);        
    }, widget.attr('data-interval'));
}

// EOF
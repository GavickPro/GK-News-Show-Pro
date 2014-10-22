// GK NSP News Gallery
(function() {
    "use strict";
    jQuery(document).ready(function() {
        jQuery(document).find('.gk-news-gallery').each(function(i, module) {
            module = jQuery(module);

            if (!module.hasClass('active')) {
                module.addClass('active');
                gkNspNewsGalleryInit(module);
            }
        });
    });

    var gkNspNewsGalleryInit = function(module) {
        module = jQuery(module);
        // set the basic module variables
        module.attr('data-current', 1);
        module.attr('data-blank', 0);
        module.attr('data-stop', 0);
        module.attr('data-all-pages', Math.ceil(module.find('.gk-image').length / module.attr('data-cols')));

        // check if pagination exists
        if (module.find('.gk-pagination').length) {
            module.find('.gk-pagination li').each(function(i, el) {
                el = jQuery(el);
                el.click(function(e) {
                    e.preventDefault();

                    if (i + 1 !== module.attr('data-current') * 1.0) {
                        module.attr('data-blank', 1);
                        gkNspNewsGalleryAnim(module, i + 1);
                    }
                });
            });
        }

        var arts_pos_start_x = 0;
        var arts_pos_start_y = 0;
        var arts_time_start = 0;
        var arts_swipe = false;

        module.bind('touchstart', function(e) {
            arts_swipe = true;
            var touches = e.originalEvent.changedTouches || e.originalEvent.touches;

            if (touches.length > 0) {
                arts_pos_start_x = touches[0].pageX;
                arts_pos_start_y = touches[0].pageY;
                arts_time_start = new Date().getTime();
            }
        });

        module.bind('touchmove', function(e) {
            var touches = e.originalEvent.changedTouches || e.originalEvent.touches;

            if (touches.length > 0 && arts_swipe) {
                if (
                    Math.abs(touches[0].pageX - arts_pos_start_x) > Math.abs(touches[0].pageY - arts_pos_start_y)
                ) {
                    e.preventDefault();
                } else {
                    arts_swipe = false;
                }
            }
        });

        module.bind('touchend', function(e) {
            var touches = e.originalEvent.changedTouches || e.originalEvent.touches;

            if (touches.length > 0 && arts_swipe) {
                if (
                    Math.abs(touches[0].pageX - arts_pos_start_x) >= 30 &&
                    new Date().getTime() - arts_time_start <= 500
                ) {
                    if (touches[0].pageX - arts_pos_start_x > 0) {
                        module.attr('data-blank', 1);
                        gkNspNewsGalleryAnim(module, 'prev');
                    } else {
                        module.attr('data-blank', 1);
                        gkNspNewsGalleryAnim(module, 'next');
                    }
                }
            }
        });

        // check if autoanimation is enabled
        if (module.hasClass('gk-auto-anim')) {
            setTimeout(function() {
                gkNspNewsGalleryAutoAnim(module);
            }, module.attr('data-autoanim-time'));
        }

        // add stop event
        module.find('.gk-image').each(function(i, img) {
            img = jQuery(img);
            img.mouseenter(function() {
                module.attr('data-stop', 1);
                var overlay = img.find('.gk-img-overlay');
                var realImg = img.find('img');
                overlay.css({
                    'margin-left': (-1.0 * (realImg.outerWidth() / 2.0)) + "px",
                    'width': realImg.outerWidth() + "px"
                });
                overlay.attr('class', 'gk-img-overlay active');
            });

            img.mouseleave(function() {
                module.attr('data-stop', 0);
                var overlay = img.find('.gk-img-overlay');
                overlay.attr('class', 'gk-img-overlay');
            });
        });
    };

    var gkNspNewsGalleryAutoAnim = function(module) {
        if (module.attr('data-blank') === 1 || module.attr('data-stop') === 1) {
            setTimeout(function() {
                module.attr('data-blank', 0);
                gkNspNewsGalleryAutoAnim(module);
            }, module.attr('data-autoanim-time'));
        } else {
            gkNspNewsGalleryAnim(module, 'next');

            setTimeout(function() {
                gkNspNewsGalleryAutoAnim(module);
            }, module.attr('data-autoanim-time'));
        }
    };

    var gkNspNewsGalleryAnim = function(module, dir) {
        // amount of news per page
        var perPage = module.attr('data-cols') * 1.0;
        var current = module.attr('data-current') * 1.0;
        var allPages = module.attr('data-all-pages') * 1.0;
        var next = 0;
        // select next page
        if (dir === 'next') {
            if (current === allPages) {
                next = 1;
            } else {
                next = current + 1;
            }
        } else if (dir === 'prev') {
            if (current === 1) {
                next = allPages;
            } else {
                next = current - 1;
            }
        } else {
            if (current !== dir) {
                next = dir;
            }
        }
        // set the current page
        module.attr('data-current', next);
        // hide current elements
        module.find('.gk-image').each(function(i, img) {
            img = jQuery(img);

            if (img.hasClass('active')) {
                gkNspNewsGalleryImgClass(img, 'active', false, 0);
                gkNspNewsGalleryImgClass(img, '', true, 300);
            }
        });
        // pagination classes
        if (module.find('.gk-pagination').length) {
            var items = module.find('.gk-pagination li');
            jQuery(items).removeClass('active');
            jQuery(items.get(next - 1)).addClass('active');
        }
        // show next elements	
        setTimeout(function() {
            module.find('.gk-image').each(function(i, img) {
                img = jQuery(img);

                if (i >= (next - 1) * perPage && i < (next * perPage)) {
                    gkNspNewsGalleryImgClass(img, 'active', false, 0);
                    gkNspNewsGalleryImgClass(img, 'active show', true, 300);
                }
            });
        }, 300);
    };

    var gkNspNewsGalleryImgClass = function(img, className, delay, time) {
        if (!delay) {
            img.attr('class', 'gk-image ' + className);
        } else {
            setTimeout(function() {
                img.attr('class', 'gk-image ' + className);
            }, time);
        }
    };
})();

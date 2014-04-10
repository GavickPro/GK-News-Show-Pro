/* 
* GK News Show Pro
*
* @version: 1.0.0
* @date: 22-03-2014
* @desc: Complex widget for displaying WordPress posts, WooCommerce products, XML/JSON file content and RSS feeds.
* @author: GavickPro 
* @email: info@gavick.com
*
*/

function GK_NSP_UI() {
    // properties
    var wrapper = null;
    // private methods
    var initUI = function() {
        // togglers
        var togglers = wrapper.find('.gk-toggler');
        togglers.each(function(i, toggler) {
            toggler = jQuery(toggler);
            toggler.click(function() {
                if (!toggler.hasClass('active')) {
                    togglers.each(function(j, el) {
                        el = jQuery(el);
                        if (i !== j) {
                            el.removeClass('active');
                        }
                    });
                    toggler.addClass('active');
                } else {
                    toggler.removeClass('active');
                }
            });
        });
        // data source config
        var data_source_type = wrapper.find('.gk-data-source-type');
        var depends = {};
        var selected = data_source_type.val();

        jQuery.each(
            [
                wrapper.find('.gk-data-source'),
                wrapper.find('.gk-one-per-category'),
                wrapper.find('.gk-order-by'),
                wrapper.find('.gk-order'),
                wrapper.find('.gk-offset'),
                wrapper.find('.gk-xml-filelist'),
                wrapper.find('.gk-json-filelist'),
                wrapper.find('.gk-wp-category-list'),
                wrapper.find('.gk-woocommerce-category-list'),
                wrapper.find('.gk-post-types-list')
            ],
            function(i, item) {
                depends[item.attr('class')] = item.attr('data-depends').split(',');

                if (depends[item.attr('class')].indexOf(data_source_type.val()) === -1) {
                    if (item.attr('class') === 'gk-order') {
                        item.css('display', 'none');
                    } else {
                        item.parent().css('display', 'none');
                    }
                }


                if (selected.indexOf('-') !== -1) {
                    selected = selected.substr(0, selected.indexOf('-'));
                }
            }
        );

        //
        wrapper.find('.gk-article-wrapper-selector option').each(function(i, item) {
            item = jQuery(item);
            var support = item.attr('data-support') === 'all' ? false : item.attr('data-support').split(',');
            if (support) {
                if (support.indexOf(selected) === -1) {
                    item.attr('disabled', 'disabled');
                } else {
                    item.removeAttr('disabled');
                }
            }
        });

        data_source_type.change(function() {
            jQuery.each(
                [
                    wrapper.find('.gk-data-source'),
                    wrapper.find('.gk-one-per-category'),
                    wrapper.find('.gk-order-by'),
                    wrapper.find('.gk-order'),
                    wrapper.find('.gk-offset'),
                    wrapper.find('.gk-json-filelist'),
                    wrapper.find('.gk-xml-filelist'),
                    wrapper.find('.gk-wp-category-list'),
                    wrapper.find('.gk-woocommerce-category-list'),
                    wrapper.find('.gk-post-types-list')
                ],
                function(i, item) {
                    var state = depends[item.attr('class')].indexOf(data_source_type.val()) === -1 ? 'none' : 'block';
                    if (item.attr('class') === 'gk-order') {
                        item.css('display', state === 'block' ? 'inline' : 'none');
                    } else {
                        item.parent().css('display', state);
                    }
                }
            );

            var selected = data_source_type.val();
            if (selected.indexOf('-') !== -1) {
                selected = selected.substr(0, selected.indexOf('-'));
            }

            wrapper.find('.gk-article-wrapper-selector option').each(function(i, item) {
                item = jQuery(item);
                var support = item.attr('data-support') === 'all' ? false : item.attr('data-support').split(',');
                if (support) {
                    if (support.indexOf(selected) === -1) {
                        item.attr('disabled', 'disabled');
                    } else {
                        item.removeAttr('disabled');
                    }
                }
            });
        });
        // reordering
        jQuery.each(['title', 'image', 'text', 'info', 'readmore'], function(iter, item) {
            var el = wrapper.find('.gk-article-' + item + '-order');
            el.change(function() {
                changeOrder(el);
            });

            el.blur(function() {
                changeOrder(el);
            });
        });
        // hiding article wrapper unnecessary elements
        if (wrapper.find('.gk-article-wrapper-selector').val() !== 'default') {
            wrapper.find('.gk-article-wrapper-hide').css('display', 'none');
            wrapper.find('*[data-aw]').css('display', 'none');
            wrapper.find('*[data-aw="' + wrapper.find('.gk-article-wrapper-selector').val() + '"]').css('display', 'block');
        } else {
            wrapper.find('.gk-article-wrapper-hide').css('display', 'block');
            wrapper.find('*[data-aw]').css('display', 'none');
        }

        wrapper.find('.gk-article-wrapper-selector').change(function() {
            if (wrapper.find('.gk-article-wrapper-selector').val() !== 'default') {
                wrapper.find('.gk-article-wrapper-hide').css('display', 'none');
                wrapper.find('*[data-aw]').css('display', 'none');
                wrapper.find('*[data-aw="' + wrapper.find('.gk-article-wrapper-selector').val() + '"]').css('display', 'block');
            } else {
                wrapper.find('.gk-article-wrapper-hide').css('display', 'block');
                wrapper.find('*[data-aw]').css('display', 'none');
            }
        });

        // reorder elements on start
        var elements_list = wrapper.find('.gk-article-elements');
        var elements_list_items = [
                                    elements_list.find('.gk-article-element[data-sort-pos="1"]'),
                                    elements_list.find('.gk-article-element[data-sort-pos="2"]'),
                                    elements_list.find('.gk-article-element[data-sort-pos="3"]'),
                                    elements_list.find('.gk-article-element[data-sort-pos="4"]'),
                                    elements_list.find('.gk-article-element[data-sort-pos="5"]')
                                ];

        for(var num = 1; num <= 5; num++) {
            var el = jQuery(elements_list_items[num]);
            var x = el.attr('data-sort-pos');

            if(x === 1) {
                el.insertBefore(elements_list.find('.gk-article-element[data-sort-pos="2"]'));
            } else {
                el.insertAfter(elements_list.find('.gk-article-element[data-sort-pos="'+(x - 1)+'"]'));
            }
        }


        // adding sortable UI to article elements
        elements_list.sortable({
            update: function(event, ui) {
                wrapper.find('.gk-article-element').each(function(i, el) {
                    wrapper.find('.gk-article-'+jQuery(el).attr('data-element-name')+'-order').val(i+1);
                });
            }
        });
    };

    var changeOrder = function(current) {
        var unexisting = [false, false, false, false, false];
        var searched = 0;
        var elms = ['title', 'image', 'text', 'info', 'readmore'].map(function(item) {
            return wrapper.find('.gk-article-' + item + '-order');
        });

        jQuery.each(elms, function(iter, item) {
            unexisting[item.val() - 1] = true;
        });

        for (var i = 0; i < 5; i++) {
            if (unexisting[i] === false) {
                searched = i + 1;
            }
        }

        jQuery.each(elms, function(iter, item) {
            if (item.attr('class') !== current.attr('class') && item.val() === current.val()) {
                item.val(searched);
            }
        });
    };
    // id of the clicked field
    var uploadID = '';

    var gkMediaInit = function()  {
        // image uploaders
        jQuery('.gk-nsp-ui .gk-media-input').each(
            function (i, el) {
                el = jQuery(el);
                var btnid = el.attr('id') + '_button';

                jQuery('#' + btnid).click(function (event) {
                    event.preventDefault();
                    var btn = jQuery(event.target);
                    var options, attachment;

                    // if the frame already exists, open it
                    if ( wp.media.frames.gkNSPImageFrame ) {
                        wp.media.frames.gkNSPImageFrame.open();
                        return;
                    }

                    // set our settings
                    wp.media.frames.gkNSPImageFrame = wp.media({
                        title: 'Choose Image',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: 'Use This Image'
                        }
                    });
                    
                    wp.media.frames.gkNSPImageFrame.on('close',function() {
                        // get selections and save to hidden input plus other AJAX stuff etc.
                        var selection = wp.media.frames.gkNSPImageFrame.state().get('selection');
                        
                        // loop through the selected files
                        selection.each( function( attachment ) {
                            var url = attachment.attributes.url;
                            jQuery(btn).prev('input').val(url);
                        });
                    });

                    // set up our select handler
                    wp.media.frames.gkNSPImageFrame.on( 'select', function() {
                        var selection = wp.media.frames.gkNSPImageFrame.state().get('selection');

                        if ( ! selection ) {
                            return;
                        }

                        // loop through the selected files
                        selection.each( function( attachment ) {
                            var url = attachment.attributes.url;
                            jQuery(btn).prev('input').val(url);
                        });
                    });

                    // open the frame
                    wp.media.frames.gkNSPImageFrame.open();
                });
            }
        );
    };

    // public API
    var API = {
        init: function(wrap) {
            wrapper = wrap;
            initUI();
            gkMediaInit();
        }
    };

    return API;
}

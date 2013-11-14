/* 
* GK News Show Pro
*
* @version: 0.1 beta
* @date: 10-11-2013
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
                wrapper.find('.gk-wp-category-list'),
                wrapper.find('.gk-woocommerce-category-list')
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
                    wrapper.find('.gk-wp-category-list'),
                    wrapper.find('.gk-woocommerce-category-list')
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
    // public API
    var API = {
        init: function(wrap) {
            wrapper = wrap;
            initUI();
        }
    };

    return API;
}

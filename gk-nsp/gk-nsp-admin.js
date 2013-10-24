// GK NSP Widget back-end

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
        var data_source = wrapper.find('.gk-data-source');
        var data_source_type = wrapper.find('.gk-data-source-type');
        var data_source_depends = data_source.attr('data-depends').split(',');

        if (data_source_depends.indexOf(data_source_type.val()) === -1) {
            data_source.css('display', 'none');
        }

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

        data_source_type.change(function() {
            data_source.css('display', data_source_depends.indexOf(data_source_type.val()) === -1 ? 'none' : 'block');

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

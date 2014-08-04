var GKNSPPortfolio = function(module) {
	var filter = module.find('.gk-portfolio-categories');

	if(filter.length || module.attr('data-popup') === 'on') {
		var images = module.find('.gk-images-wrapper a');

		if(filter.length) {
			var btns = filter.find('li');

			btns.each(function(i, btn) {
				btn = jQuery(btn);
				btn.click(function() {
					images.removeClass('active');
					btns.removeClass('active');
					jQuery(btns[i]).addClass('active');

					if(i > 0) {
						module.find('.gk-images-wrapper a[data-cat*="' + btn.html() + '"]').addClass('active');
					} else {
						images.addClass('active');
					}
				});
			});
		}
		// check if popup is enabled
		if(module.attr('data-popup') === 'on') {
			var popup_content = '<a href="#" class="gk-portfolio-prev">&laquo;</a><a href="#" class="gk-portfolio-next">&raquo;</a><a href="#" class="gk-portfolio-close">&times;</a><div><div class="gk-portfolio-image"></div><div class="gk-portfolio-desc"><h3 class="gk-portfolio-title"></h3><small class="gk-portfolio-category"></small><span class="gk-portfolio-author"></span><span class="gk-portfolio-date"></span></div></div>';
			var popup = jQuery('<div class="gk-portfolio-popup">' + popup_content + '</div>');
			module.append(popup);
			var popup_image_wrap = popup.find('.gk-portfolio-image');
			var popup_close = popup.find('.gk-portfolio-close');
			var popup_prev = popup.find('.gk-portfolio-prev');
			var popup_next = popup.find('.gk-portfolio-next');
			var popup_title = popup.find('.gk-portfolio-title');
			var popup_cat = popup.find('.gk-portfolio-category');
			var popup_author = popup.find('.gk-portfolio-author');
			var popup_date = popup.find('.gk-portfolio-date');
			var current_popup_image = 0;
			var blank = false;

			popup_close.click(function(e) {
				e.preventDefault();
				popup.removeClass('active');
				popup_image_wrap.removeClass('active');
				popup_title.removeClass('active');
				popup_cat.removeClass('active');
				popup_author.removeClass('active');
				popup_date.removeClass('active');

				setTimeout(function() {
					popup_image_wrap.html('');
					popup.removeClass('activated');
				}, 300);
			});

			popup_prev.click(function(e) {
				e.preventDefault();

				if(!blank) {
					blank = true;
					popup_image_wrap.removeClass('active');
					popup_title.removeClass('active');
					popup_cat.removeClass('active');
					popup_author.removeClass('active');
					popup_date.removeClass('active');

					setTimeout(function() {
						var prev = 0;

						if(current_popup_image > 0) {
							prev = current_popup_image - 1;
						} else {
							prev = images.length - 1;
						}

						showItem(images[prev]);
						current_popup_image = prev;
					}, 350);
				}
			});

			popup_next.click(function(e) {
				e.preventDefault();

				if(!blank) {
					blank = true;
					popup_image_wrap.removeClass('active');
					popup_title.removeClass('active');
					popup_cat.removeClass('active');
					popup_author.removeClass('active');
					popup_date.removeClass('active');

					setTimeout(function() {
						var next = 0;

						if(current_popup_image < images.length - 1) {
							next = current_popup_image + 1;
						} else {
							next = 0;
						}

						showItem(images[next]);
						current_popup_image = next;
					}, 350);
				}
			});

			function showItem(img) {
				img = jQuery(img);
				popup_image_wrap.html('<a href="' + img.attr('href') + '"><img src="' + img.attr('data-img') + '" /></a>');
				popup_title.html(img.attr('title'));
				popup_cat.html('<span>' + img.attr('data-cat-text') + '</span>' + img.attr('data-cat').replace(/;/g, '<br />'));
				popup_author.html('<span>' + img.attr('data-author-text') + '</span>' + img.attr('data-author'));
				popup_date.html('<span>' + img.attr('data-date-text') + '</span>' + img.attr('data-date'));

				var image = popup_image_wrap.find('img');

				var timer = setInterval(function() {
					if(image[0].complete) {
						clearInterval(timer);
						setTimeout(function() { popup_image_wrap.addClass('active'); }, 100);
						setTimeout(function() { popup_title.addClass('active'); }, 200);
						setTimeout(function() { popup_cat.addClass('active'); }, 300);
						setTimeout(function() { popup_author.addClass('active'); }, 400);
						setTimeout(function() { 
							popup_date.addClass('active'); 
							blank = false;
						}, 500);
					}
				}, 300);
			}

			images.each(function(i, img) {
				img = jQuery(img);
				img.click(function(e) {
					if(jQuery(window).width() > 600) {
						e.preventDefault();

						current_popup_image = i;
						popup.addClass('activated');
						showItem(img);

						setTimeout(function() {
							popup.addClass('active');
						}, 50);
					}
				});
			});
		}
	}
};

jQuery(document).ready(function() {
	setTimeout(function() {
		jQuery(document).find('.gk-nsp-portfolio').each(function(i, module) {
			module = jQuery(module);

			if(!module.hasClass('active')) {
				module.addClass('active');
			}

			var mod = new GKNSPPortfolio(module);
		});
	}, 1000);
});

// EOF
// GK NSP Video Gallery
jQuery(window).load(function() {
	setTimeout(function() {
		jQuery(document).find('.gk-nsp-videogallery').each(function(i, module) {
			module = jQuery(module);

			if(!module.hasClass('active')) {
				module.addClass('active');
			}
			
			var mod = new GKNSPVideoGallery();
			mod.init(module);
		});
	}, 1000);
});

// module initializer
var GKNSPVideoGallery = function(module) {
	var API = {
		// basic variables
		bigarea: null,
		smallarea: null,
		interval: null,
		current: 0,
		currentPage: 0,
		blank: false,
		
		init: function(wrapper) {
			//
			$this = this;
			this.bigarea = jQuery(wrapper.find('.gk-big-block'));
			this.smallarea = jQuery(wrapper.find('.gk-small-block'));
			this.interval = wrapper.attr('data-autoanimint');
			// add video popup event
			this.bigarea.click(function(e) {
				if(e.target.tagName.toLowerCase() == 'figure') {
					var target_img = jQuery(e.target).find('img');
					var url = target_img.attr('data-url');
					if(url == '' || !jQuery(e.target).find('.gk-image-wrap').hasClass('thickbox')) {
						window.location.href = $this.bigarea.find('h3 a').attr('href');
					} else {
						jQuery(e.target).find('a').trigger('click');
					}
				}
			});
			// add news resizer event
			this.smallarea.find('.gk-item').each(function(i, item) {
				item = jQuery(item);
				item.find('.gk-image-wrap').click(function(e) {
					e.preventDefault();
				});
				item.click(function(e) {
					if(e) e.preventDefault();
					// replace current big slide with the new one
					$this.bigarea.removeClass('active');
					$this.blank = true;
					$this.bigarea.animate({
						'opacity': 0
					}, 250, function() {
						item.clone().appendTo($this.bigarea);
						$this.bigarea.find('figure').first().remove();
						$this.bigarea.find('figure .gk-image-wrap').attr('href', $this.bigarea.find('figure .gk-image-wrap img').attr('data-url'));
						if($this.bigarea.hasClass('popup')) {
							$this.bigarea.find('figure .gk-image-wrap').addClass('thickbox');	
						}
						$this.current = item.attr('data-num');
						setTimeout(function() {
							$this.bigarea.animate({
								'opacity': 1
							}, 250);
							$this.bigarea.addClass('active');
						}, 100);
					});
				});
			});
			// autoanimation
			setTimeout(function() {
				$this.next();
			}, this.interval);
		},
		
		next: function() {
			if(!this.blank) {
				// check amount of pages and elements
				var pagesAmount = this.smallarea.find('.gk-items-page').length;
				var itemsAmount = this.smallarea.find('.gk-item').length;
				var perPage = Math.ceil(itemsAmount / pagesAmount);
				//
				if(this.current > itemsAmount - 2) {
					this.current = 0;
					
					if(this.currentPage != Math.floor(this.current / perPage)) {
						var toHide = jQuery(this.smallarea.find('.gk-items-page')[this.currentPage]);
						var toShow = jQuery(this.smallarea.find('.gk-items-page')[0]);
						
						toHide.animate({ 
							'opacity': 0
						}, 250, function() {
							toHide.removeClass('active');
							$this.currentPage = 0;
							
							toShow.css('opacity', 0);
							toShow.addClass('active');
				
							toShow.animate({
								'opacity': 1
							}, 250);
						});
					}
	
					jQuery(this.smallarea.find('.gk-item').first()).trigger('click');
				} else {
					this.current = this.current * 1 + 1;
					
					if(this.currentPage != Math.floor(this.current / perPage)) {
						var toHide = jQuery(this.smallarea.find('.gk-items-page')[this.currentPage]);
						this.currentPage = Math.floor(this.current / perPage);
						var toShow = jQuery(this.smallarea.find('.gk-items-page')[this.currentPage]);
						
						toHide.animate({ 
							'opacity': 0
						}, 250, function() {
							toHide.removeClass('active');
							toShow.css('opacity', 0);
							toShow.addClass('active');
				
							toShow.animate({
								'opacity': 1
							}, 250);
						});
					}
					
					jQuery(this.smallarea.find('.gk-item')[this.current]).trigger('click');
				}
			} else {
				this.blank = false;
			}
			// autoanimation
			setTimeout(function() {
				$this.next();
			}, this.interval);
		}
	};
	
	return API;
};

// EOF

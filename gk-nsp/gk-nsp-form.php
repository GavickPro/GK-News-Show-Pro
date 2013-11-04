<?php

/*

Copyright 2013-2013 GavickPro (info@gavick.com)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if ( !defined( 'WPINC' ) ) {
    die;
}

class GK_NSP_Widget_Form {
	// class fields
	var $nsp;
	var $instance;
	/**
	 *
	 * Outputs the HTML code of the widget in the back-end
	 *
	 * @param array instance of the widget settings
	 * @return void - HTML output
	 *
	 **/
	function get_form($nsp, $instance) {
		$this->nsp = $nsp;
		$this->instance = $instance;
		// get the JSON data
		$json_data = $this->get_json_data();
		// get the option values as variables
		extract($nsp->config);

	?>	
		<div class="gk-nsp-col gk-nsp-ui">
			<div class="gk-loader"></div>
			<h3 class="gk-toggler active"><?php _e('Basic settings', 'gk-nsp'); ?></h3>
			<div class="gk-toggle">
				<div>
					<p><?php $this->input_text('title', $title, __( 'Title:', 'gk-nsp' ), '', ''); ?></p>

					<p><?php $this->input_text('widget_css_suffix', $widget_css_suffix, __( 'CSS suffix:', 'gk-nsp' ), '', ''); ?></p>
				
					<p>
						<label for="<?php echo esc_attr( $nsp->get_field_id( 'data_source_type' ) ); ?>"><?php _e( 'Data source:', 'gk-nsp' ); ?></label>

						<select 
							class="gk-data-source-type" 
							id="<?php echo esc_attr( $nsp->get_field_id('data_source_type')); ?>" 
							name="<?php echo esc_attr( $nsp->get_field_name('data_source_type')); ?>"
						>
							<?php foreach($json_data['data_source_type'] as $data_source_option) : ?>
							<option value="<?php echo $data_source_option[0]; ?>"<?php selected($data_source_type, $data_source_option[0]); ?>><?php echo $data_source_option[1]; ?></option>
							<?php endforeach; ?>
						</select>
						
						<textarea class="gk-data-source" id="<?php echo esc_attr( $nsp->get_field_id('data_source')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('data_source')); ?>" data-depends="<?php echo $json_data['data_source']; ?>"><?php echo esc_attr($data_source); ?></textarea>
					</p>
				
					<p>
						<?php $this->input_select('orderby', $orderby, __('Order by:', 'gk-nsp'), array('ID' => __('ID', 'gk-nsp'), 'date' => __('Date', 'gk-nsp'), 'title' => __('Title', 'gk-nsp'), 'modified' => __('Modified', 'gk-nsp'), 'rand' => __('Random', 'gk-nsp')), '', ' data-depends="'.$json_data['orderby'].'"'); ?>
						<?php $this->input_select('order', $order, '', array('ASC' => __('ASC', 'gk-nsp'), 'DESC' => __('DESC', 'gk-nsp')), '', ' data-depends="'.$json_data['order'].'"'); ?>
					</p>
				
					<p><?php $this->input_text('offset', $offset, __( 'Offset:', 'gk-nsp' ), '', 'short', ' data-depends="'. $json_data['offset'] .'"'); ?></p>

					<?php if(is_multisite()) : ?>
						<p><?php $this->input_text('data_source_blog', $data_source_blog, __( 'Blog ID (leave blank for current blog): ', 'gk-nsp' ), '', 'short'); ?></p>
					<?php endif; ?>
				</div>
			</div>
			

			<h3 class="gk-toggler"><?php _e('Widget layout', 'gk-nsp'); ?></h3>
			<div class="gk-toggle">
				<div>
					<p>
						<big><span><?php _e('Articles block', 'gk-nsp'); ?></span></big>
						<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_wrapper' ) ); ?>"><?php _e('Article wrapper:', 'gk-nsp'); ?></label>
						
						<select id="<?php echo esc_attr( $nsp->get_field_id('article_wrapper')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('article_wrapper')); ?>" class="gk-article-wrapper-selector">
							<?php 
								$json_cache = get_option('widget_gk_nsp_json_cache');
								$format_files = scandir($nsp->aw_path); 
								foreach($format_files as $file) :
									if($file != '.' && $file != '..' && is_dir($nsp->aw_path . GK_DS . $file)) :
							?>
								<option value="<?php echo $file; ?>"<?php selected($article_wrapper, $file); ?> data-support="<?php echo is_array($json_cache[$file]->support) ? join(',', $json_cache[$file]->support) : $json_cache[$file]->support; ?>"><?php echo $file; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</p>

					<p class="gk-article-wrapper-hide">
						<span></span>
						<?php $this->input_text('article_pages', $article_pages, __( 'Pages: ', 'gk-nsp' ), '', 'short'); ?>
						<?php $this->input_text('article_cols', $article_cols, __( 'Columns: ', 'gk-nsp' ), '', 'short'); ?>
						<?php $this->input_text('article_rows', $article_rows, __( 'Rows: ', 'gk-nsp' ), '', 'short'); ?>
					</p>
					
					<p class="gk-article-wrapper-hide">
						<big><span><?php _e('Links block', 'gk-nsp'); ?></span></big>
						<?php $this->input_text('links_pages', $links_pages, __( 'Pages: ', 'gk-nsp' ), '', 'short'); ?>
						<?php $this->input_text('links_rows', $links_rows, __( 'Rows: ', 'gk-nsp' ), '', 'short'); ?>
					</p>
					
					<p class="gk-article-wrapper-hide">
						<label class="long-text" for="<?php echo esc_attr( $nsp->get_field_id( 'article_pagination' ) ); ?>"><?php _e( 'Article pagination:', 'gk-nsp' ); ?></label>
						<?php $this->input_select('article_pagination', $article_pagination, '', array('pagination' => __('Pagination', 'gk-nsp'), 'arrows' => __('Arrows', 'gk-nsp'), 'pagination_with_arrows' => __('Both', 'gk-nsp'))); ?>
					</p>
					
					<p class="gk-article-wrapper-hide">
						<label class="long-text" for="<?php echo esc_attr( $nsp->get_field_id( 'links_pagination' ) ); ?>"><?php _e( 'Links pagination:', 'gk-nsp' ); ?></label>
						<?php $this->input_select('links_pagination', $links_pagination, '', array('pagination' => __('Pagination', 'gk-nsp'), 'arrows' => __('Arrows', 'gk-nsp'), 'pagination_with_arrows' => __('Both', 'gk-nsp'))); ?>
					</p>
				</div>
			</div>

			<?php $this->article_wrapper_options(); ?>
			
			<h3 class="gk-toggler gk-article-wrapper-hide"><?php _e('Autoanimation settings', 'gk-nsp'); ?></h3>
			<div class="gk-toggle gk-article-wrapper-hide">
				<div>
					<p>
						<label class="long-text" for="<?php echo esc_attr( $nsp->get_field_id( 'autoanim' ) ); ?>"><?php _e( 'Auto-animation:', 'gk-nsp' ); ?></label>
						<?php $this->input_switch('autoanim', $autoanim, ''); ?>		
					</p>
					
					<p><?php $this->input_text('autoanim_interval', $autoanim_interval, __( 'Interval: ', 'gk-nsp' ), '', 'medium-right', '', 'text', ' (ms)'); ?></p>
					
					<p>
						<label class="long-text" for="<?php echo esc_attr( $nsp->get_field_id( 'autoanim_hover' ) ); ?>"><?php _e( 'Auto-animation stops on hover:', 'gk-nsp' ); ?></label>
						<?php $this->input_switch('autoanim_hover', $autoanim_hover, ''); ?>		
					</p>
				</div>
			</div>
		
			<h3 class="gk-toggler"><?php _e('Article layout', 'gk-nsp'); ?></h3>	
			<div class="gk-toggle">
				<div>
				<p>
					<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_format' ) ); ?>"><?php _e('Article format:', 'gk-nsp'); ?></label>
					
					<select id="<?php echo esc_attr( $nsp->get_field_id('article_format')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('article_format')); ?>">
						<option value="none"<?php selected($article_format, 'none'); ?>><?php _e('None', 'gk-nsp'); ?></option>
						<?php 
							$format_files = scandir($nsp->af_path); 
							foreach($format_files as $file) :
								if($file != '.' && $file != '..' && substr($file, -7) == '.format') :
						?>
							<option value="<?php echo $file; ?>"<?php selected($article_format, $file); ?>><?php echo $file; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</p>

				<p><?php $this->input_text('article_block_padding', $article_block_padding, __( 'Padding: ', 'gk-nsp' ), '', ''); ?></p>
							
				<p class="gk-article-element">
					<?php $this->input_switch('article_title_state', $article_title_state, __('Title', 'gk-nsp')); ?>
					<?php $this->input_text('article_title_len', $article_title_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>				
					<?php $this->input_select('article_title_len_type', $article_title_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>

					<span class="gk-right">
						<?php $this->input_select('article_title_order', $article_title_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-title-order gk-order'); ?>
					</span>
				</p>
							
				<p class="gk-article-element">
					<?php $this->input_switch('article_text_state', $article_text_state, __('Text', 'gk-nsp')); ?>
					<?php $this->input_text('article_text_len', $article_text_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>
					<?php $this->input_select('article_text_len_type', $article_text_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>

					<span class="gk-right">
						<?php $this->input_select('article_text_order', $article_text_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-text-order gk-order'); ?>
					</span>
				</p>	
				
				<p class="gk-article-element">
					<?php $this->input_switch('article_image_state', $article_image_state, __('Image', 'gk-nsp')); ?>
					
					<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_image_w' ) ); ?>"><?php _e( 'size:', 'gk-nsp' ); ?></label>
					
					<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_image_w' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_image_w' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_w ); ?>" class="short" />
					&times;
					<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_image_h' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_image_h' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_h ); ?>" class="short" />
					
					<span class="gk-right">
						<?php $this->input_select('article_image_order', $article_image_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-image-order gk-order'); ?>
					</span>
				</p>	
				
				<div class="gk-indent">	
					<p><?php $this->input_switch('article_image_popup', $article_image_popup, __('Image popup:', 'gk-nsp'), __('This option works only with the WordPress and WooCommerce data sources', 'gk-nsp')); ?></p>

					<p><?php $this->input_text('image_block_padding', $image_block_padding, __( 'Margin: ', 'gk-nsp' ), '', 'long'); ?></p>
					
					<p><?php $this->input_select('article_image_pos', $article_image_pos, __( 'Position:', 'gk-nsp' ), array('top' => __('Top', 'gk-nsp'), 'left' => __('Left', 'gk-nsp'))); ?></p>	
				</div>
							
				<p class="gk-article-element">
					<?php $this->input_switch('article_info_state', $article_info_state, __('Info block', 'gk-nsp')); ?>
					
					<span class="gk-right">
						<?php $this->input_select('article_info_order', $article_info_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-info-order gk-order'); ?>
					</span>
				</p>
				
				<div class="gk-indent">
					<p>
						<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_info_format' ) ); ?>"><?php _e( 'Format:', 'gk-nsp' ); ?></label>
						
						<textarea class="gk-format-textarea" id="<?php echo esc_attr( $nsp->get_field_id( 'article_info_format' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_info_format' ) ); ?>" type="text"><?php echo esc_attr( $article_info_format ); ?></textarea>

						<small>
							<?php _e('You can use in the Format option following tags:', 'gk-nsp'); ?>
							<br />{DATE}, {CATEGORY}, {AUTHOR}, {COMMENTS}, {PRICE}, {REVIEWS}
						</small>
					</p>
					
					<p>	
						<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_info_date_format' ) ); ?>"><?php _e( 'Date format:', 'gk-nsp' ); ?></label>
						
						<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_info_date_format' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_info_date_format' ) ); ?>" type="text" value="<?php echo esc_attr( $article_info_date_format ); ?>" class="medium" />
					</p>
				</div>
							
				<p class="gk-article-element">
					<?php $this->input_switch('article_readmore_state', $article_readmore_state, __('Read more', 'gk-nsp')); ?>
					
					<span class="gk-right">
						<?php $this->input_select('article_readmore_order', $article_readmore_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-readmore-order gk-order'); ?>
					</span>
				</p>
				</div>
			</div>
			
			<h3 class="gk-toggler gk-article-wrapper-hide"><?php _e('Link format', 'gk-nsp'); ?></h3>
			<div class="gk-toggle gk-article-wrapper-hide">		
				<div>
					<p>
						<?php $this->input_switch('links_title_state', $links_title_state, __('Title', 'gk-nsp')); ?>
						<?php $this->input_text('links_title_len', $links_title_len, __( 'length: ', 'gk-nsp' ), '', 'short-right', ' min="0" ', 'number'); ?>
						<?php $this->input_select('links_title_len_type', $links_title_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>
					</p>
					
					<p>				
						<?php $this->input_switch('links_text_state', $links_text_state, __('Text', 'gk-nsp')); ?>
						<?php $this->input_text('links_text_len', $links_text_len, __( 'length: ', 'gk-nsp' ), '', 'short-right', ' min="0" ', 'number'); ?>
						<?php $this->input_select('links_text_len_type', $links_text_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>
					</p>
				</div>
			</div>
			
			<h3 class="gk-toggler"><?php _e('Cache settings', 'gk-nsp'); ?></h3>
			<div class="gk-toggle">
				<div>
					<p><?php $this->input_text('cache_time', $cache_time, __( 'Cache time: ', 'gk-nsp' ), '', 'medium-right', ' min="0" max="9999999999"', 'number', __('min', 'gk-nsp')); ?></p>
				</div>
			</div>
		</div>
		
		<script>
		setTimeout(function() {
			jQuery('.gk-nsp-ui').each(function(i, el) {
				el = jQuery(el);
				var id = el.parent().parent().find('.widget-id').val();
				
				if(id.indexOf('gk_nsp-__i__') === -1) {
					var selected = jQuery("div[id$='"+id+"']");
					if(!selected.hasClass('activated')) {
						selected.addClass('activated');
						
						setTimeout(function() {
							selected.find('.gk-loader').remove();
						}, 350);
						
						selected.find('.widget-control-save').click(function() {
							selected.removeClass('activated');
						});
						// init the specific instance
						var nsp = GK_NSP_UI();
						nsp.init(selected);
					}
				}
			});
		}, 1500);
		</script>
		
	<?php
	}
	// Was: 607
	function input_text($name, $value, $label, $tip = '', $classes = '', $other = '', $type = 'text', $after = '') {
		?>
		<label 
			for="<?php echo esc_attr( $this->nsp->get_field_id( $name ) ); ?>" 
			title="<?php echo $tip; ?>"
		>
			<?php echo $label; ?>
		</label>
		
		<input 
			id="<?php echo esc_attr( $this->nsp->get_field_id( $name ) ); ?>" 
			name="<?php echo esc_attr( $this->nsp->get_field_name( $name ) ); ?>" 
			class="<?php echo $classes; ?>" 
			type="<?php echo $type; ?>" 
			value="<?php echo esc_attr( $value ); ?>" 
			<?php echo $other; ?> 
		/> <?php echo $after; ?>
		<?php
	}

	function input_select($name, $value, $label, $items, $tip = '', $classes = '', $other = '', $after = '') {
		?>
		<?php if($label != '') : ?>
		<label 
			for="<?php echo esc_attr( $this->nsp->get_field_id( $name ) ); ?>" 
			title="<?php echo $tip; ?>"
		><?php echo $label; ?></label>
		<?php endif; ?>
					
		<select 
			id="<?php echo esc_attr( $this->nsp->get_field_id('article_readmore_order')); ?>" 
			name="<?php echo esc_attr( $this->nsp->get_field_name($name)); ?>" 
			class="<?php echo $classes; ?>"
			<?php echo $other; ?> 
		>
			<?php if(is_array($items) || is_object($items)) : ?>
				<?php foreach($items as $option_value => $option_name) : ?>	
				<option value="<?php echo $option_value; ?>"<?php selected($value, $option_value); ?>><?php echo $option_name; ?></option>
				<?php endforeach; ?>
			<?php else : ?>
				<?php for($i = 1; $i <= $items; $i++) : ?>	
				<option value="<?php echo $i; ?>"<?php selected($value, $i); ?>><?php echo $i; ?></option>
				<?php endfor; ?>
			<?php endif; ?>
		</select> <?php echo $after; ?>
		<?php
	}

	function input_switch($name, $value, $label, $tip = '', $classes = '', $other = '', $after = '') {
		$this->input_select($name, $value, $label, array('on' => __('On', 'gk-nsp'), 'off' => __('Off', 'gk-nsp')), $tip, $classes, $other, $after);
	}

	function article_wrapper_options() {
		$json_cache = get_option('widget_gk_nsp_json_cache');
		
		if(is_array($json_cache) || is_object($json_cache)) {
			foreach($json_cache as $article_wrapper) {
				if($article_wrapper->tab_name != FALSE) :
				?>
				<h3 class="gk-toggler" data-aw="<?php echo $article_wrapper->name; ?>"><?php echo $article_wrapper->tab_name; ?></h3>
				<div class="gk-toggle" data-aw="<?php echo $article_wrapper->name; ?>">
					<div>
						<?php foreach($article_wrapper->params as $param) : ?>
						<p>
							<?php 
								if($param->type == 'text') {
									$this->input_text(
										$article_wrapper->name . '_' . $param->name, 
										isset($this->nsp->config[$article_wrapper->name . '_' . $param->name]) ? $this->nsp->config[$article_wrapper->name . '_' . $param->name] : $param->default, 
										isset($param->label) ? $param->label : '', 
										isset($param->tooltip) ? $param->tooltip : '', 
										isset($param->classes) ? $param->classes : '', 
										'', 
										'text', 
										isset($param->after) ? $param->after : ''
									);	
								} elseif($param->type == 'select') {
									$this->input_select(
										$article_wrapper->name . '_' . $param->name, 
										isset($this->nsp->config[$article_wrapper->name . '_' . $param->name]) ? $this->nsp->config[$article_wrapper->name . '_' . $param->name] : $param->default, 
										isset($param->label) ? $param->label : '', 
										isset($param->options) ? $param->options : 0,
										isset($param->tooltip) ? $param->tooltip : '', 
										isset($param->classes) ? $param->classes : '', 
										'', 
										isset($param->after) ? $param->after : ''
									);	
								} elseif($param->type == 'switch') {
									$this->input_switch(
										$article_wrapper->name . '_' . $param->name, 
										isset($this->nsp->config[$article_wrapper->name . '_' . $param->name]) ? $this->nsp->config[$article_wrapper->name . '_' . $param->name] : $param->default, 
										isset($param->label) ? $param->label : '', 
										isset($param->tooltip) ? $param->tooltip : '', 
										isset($param->classes) ? $param->classes : '', 
										'', 
										isset($param->after) ? $param->after : ''
									);	
							 	} 
							 ?>
						</p>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
				endif;
			}
		}	
	}

	/**
	 *
	 * Reads the JSON configuration files for each Data Source
	 *
	 * @return array - results as an associative array
	 *
	 **/
	function get_json_data() {
		// set the default language
		$default_language = 'en_US';
		$language = get_locale() != '' ? get_locale() : $default_language;
		// find all data sources
		$data_source_path =$this->nsp->ds_path;
		$dirs = scandir($data_source_path);
		$results = array();
		// iterate through founded files
		foreach($dirs as $dir) {
			// filter founded files
			if($dir != '.' && $dir != '..' && is_dir($data_source_path . GK_DS . $dir)) {
				// find all json data files
				$config_path = $data_source_path . GK_DS . $dir . GK_DS . 'config-' . $language . '.json';
				$config_path_default = $data_source_path . GK_DS . $dir . GK_DS . 'config-' . $default_language . '.json';
				// check the files
				if(is_file($config_path)) {
					$results[$dir] = json_decode(file_get_contents($config_path));
				} else if(is_file($config_path_default)) {
					$results[$dir] = json_decode(file_get_contents($config_path_default));
				}
			}
		}
		// variables for storing the results
		$json_data = array(
							"data_source_type" => array(),
							"orderby"          => '',
							"order"            => '',
							"offset"           => '',
							"data_source"      => ''
						  );
		$json_data_helper = array();
		// parse the values in the founded files
		foreach($results as $dir => $json) {
			foreach($json->data_source_types as $data_source) {
				if(substr($data_source->name, 0, 3) == 'wp-') {
					array_push($json_data_helper, array($data_source->name, $data_source->label));
				} else {
					array_push($json_data['data_source_type'], array($data_source->name, $data_source->label));	
				}
				
				$json_data['orderby']     .= in_array('orderby', $data_source->fields) ? $data_source->name . ','     : ''; 
				$json_data['order']       .= in_array('order', $data_source->fields) ? $data_source->name . ','       : ''; 
				$json_data['offset']      .= in_array('offset', $data_source->fields) ? $data_source->name . ','      : ''; 
				$json_data['data_source'] .= in_array('data_source', $data_source->fields) ? $data_source->name . ',' : ''; 
			}
		}
		// change the ordering of data source options
		for($i = count($json_data_helper) - 1; $i >= 0; $i--) {
			array_unshift($json_data['data_source_type'], $json_data_helper[$i]);
		}
		//
		$json_data['orderby']     = $json_data['orderby'] != ''     ? substr($json_data['orderby'], 0, -1)     : $json_data['orderby'];
		$json_data['order']       = $json_data['order'] != ''       ? substr($json_data['order'], 0, -1)       : $json_data['order'];
		$json_data['offset']      = $json_data['offset'] != ''      ? substr($json_data['offset'], 0, -1)      : $json_data['offset'];
		$json_data['data_source'] = $json_data['data_source'] != '' ? substr($json_data['data_source'], 0, -1) : $json_data['data_source'];

		return $json_data;
	}
}
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
					<p><?php $this->input_text('title', $title, __( 'Title:', 'gk-nsp' ), '', 'long'); ?></p>

					<p><?php $this->input_text('widget_css_suffix', $widget_css_suffix, __( 'CSS suffix:', 'gk-nsp' ), '', 'long'); ?></p>
				
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
					</p>
					
					<p>	
						<textarea class="gk-data-source" id="<?php echo esc_attr( $nsp->get_field_id('data_source')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('data_source')); ?>" data-depends="<?php echo $json_data['data_source']; ?>"><?php echo esc_attr($data_source); ?></textarea>
					</p>

					<div class="gk-filelist">
						<select class="gk-json-filelist" id="<?php echo esc_attr( $nsp->get_field_id('json_filelist')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('json_filelist')); ?>" data-depends="<?php echo $json_data['json_filelist']; ?>">
							<option value=""><?php _e('Please select a JSON file', 'gk-nsp'); ?></option>
							<?php 
							
							$upload_dir =  wp_upload_dir();
							$json_files=glob($upload_dir['basedir'] . '/gk_nsp_external_data/*.json');

							foreach ($json_files as $file) { 
								$filename = str_replace($upload_dir['basedir'] . '/gk_nsp_external_data/', '', $file); 
							?>
							<option value="<?php echo $filename; ?>" <?php selected($filename, $json_filelist, true); ?>><?php echo $filename; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="gk-filelist">
						<select class="gk-xml-filelist" id="<?php echo esc_attr( $nsp->get_field_id('xml_filelist')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('xml_filelist')); ?>" data-depends="<?php echo $json_data['xml_filelist']; ?>">
							<option value=""><?php _e('Please select a XML file', 'gk-nsp'); ?></option>
							<?php 
							
							$upload_dir =  wp_upload_dir();
							$xml_files=glob($upload_dir['basedir'] . '/gk_nsp_external_data/*.xml');

							foreach ($xml_files as $file) { 
								$filename = str_replace($upload_dir['basedir'] . '/gk_nsp_external_data/', '', $file); 
							?>
							<option value="<?php echo $filename; ?>" <?php selected($filename, $xml_filelist, true); ?>><?php echo $filename; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="categorydiv">
						<div class="tabs-panel">	
							<ul class="gk-wp-category-list" id="<?php echo esc_attr( $nsp->get_field_id('wp_category_list')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('wp_category_list')); ?>" data-depends="<?php echo $json_data['wp_category_list']; ?>">
								<?php
									wp_category_checklist( 0, 0, $wp_category_list, false, new GK_NSP_Walker_Category_Checklist($nsp->get_field_name('wp_category_list')), true);
								?>
							</ul>
						</div>
					</div>

					<div class="categorydiv">
						<div class="tabs-panel">	
							<ul class="gk-woocommerce-category-list" id="<?php echo esc_attr( $nsp->get_field_id('woocommerce_category_list')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('woocommerce_category_list')); ?>" data-depends="<?php echo $json_data['woocommerce_category_list']; ?>">
								<?php
									wp_terms_checklist( 0, array(	
										'selected_cats' => $woocommerce_category_list, 
										'popular_cats' => false, 
										'walker' => new GK_NSP_Walker_Category_Checklist($nsp->get_field_name('woocommerce_category_list')), 
										'taxonomy' => 'product_cat',
										'checked_ontop' => true
									));
								?>
							</ul>
						</div>
					</div>

					<div class="categorydiv">
						<div class="tabs-panel">	
							<ul class="gk-post-types-list" id="<?php echo esc_attr( $nsp->get_field_id('post_types_list')); ?>" name="<?php echo esc_attr( $nsp->get_field_name('post_types_list')); ?>" data-depends="<?php echo $json_data['post_types_list']; ?>">
								<?php
									$post_types = get_post_types(array(
															'public' => true,
															'_builtin' => false
														), 'names');

									if(count($post_types)) {
										foreach($post_types as $key => $value) {
											?>
											<li><label class="selectit"><input value="<?php echo $value; ?>" type="checkbox" name="<?php echo $nsp->get_field_name('post_types_list'); ?>[]" <?php checked( in_array( $value, $post_types_list ), true, true); ?> /> <?php echo $key; ?></label></li>
											<?php
										}
									} else {
										_e('There is no custom post types', 'gk-nsp');
									}
									
								?>
							</ul>
						</div>
					</div>

					<p><?php $this->input_checkbox('one_per_category', $one_per_category, __('One per category', 'gk-nsp'), '', 'gk-one-per-category', ' data-depends="'.$json_data['one_per_category'].'"'); ?></p>
				
					<p>
						<?php $this->input_select('orderby', $orderby, __('Order by:', 'gk-nsp'), array('ID' => __('ID', 'gk-nsp'), 'date' => __('Date', 'gk-nsp'), 'title' => __('Title', 'gk-nsp'), 'modified' => __('Modified', 'gk-nsp'), 'rand' => __('Random', 'gk-nsp')), '', 'gk-order-by', ' data-depends="'.$json_data['orderby'].'"'); ?>
						<?php $this->input_select('order', $order, '', array('ASC' => __('ASC', 'gk-nsp'), 'DESC' => __('DESC', 'gk-nsp')), '', 'gk-order', ' data-depends="'.$json_data['order'].'"'); ?>
					</p>
				
					<p><?php $this->input_text('offset', $offset, __( 'Offset:', 'gk-nsp' ), '', 'short gk-offset', ' data-depends="'. $json_data['offset'] .'"'); ?></p>

					<?php if(is_multisite()) : ?>
						<p><?php $this->input_text('data_source_blog', $data_source_blog, __( 'Blog ID (leave blank for current blog): ', 'gk-nsp' ), '', 'short'); ?></p>
					<?php endif; ?>

					<p><?php $this->input_checkbox('use_css', $use_css, __('Use default CSS', 'gk-nsp')); ?></p>
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
					<p><?php $this->input_checkbox('autoanim', $autoanim, __('Auto-animation', 'gk-nsp')); ?></p>	
					
					<p><?php $this->input_text('autoanim_interval', $autoanim_interval, __( 'Interval: ', 'gk-nsp' ), '', 'medium-right', '', 'text', ' (ms)'); ?></p>
					
					<p><?php $this->input_checkbox('autoanim_hover', $autoanim_hover, __('Auto-animation stops on hover', 'gk-nsp')); ?></p>
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
					
					<div class="gk-article-elements">		
						<div class="gk-article-element" data-element-name="title" data-sort-pos="<?php echo $article_title_order; ?>">
							<?php $this->input_checkbox_before('article_title_state', $article_title_state, __('Show title', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<?php $this->input_text('article_title_len', $article_title_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>		
								<?php $this->input_select('article_title_len_type', $article_title_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>

								<span class="gk-right">
									<?php $this->input_select('article_title_order', $article_title_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-title-order'); ?>
								</span>
							</div>
						</div>
									
						<div class="gk-article-element" data-element-name="text" data-sort-pos="<?php echo $article_text_order; ?>">
							<?php $this->input_checkbox_before('article_text_state', $article_text_state, __('Show text', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<?php $this->input_text('article_text_len', $article_text_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>
								<?php $this->input_select('article_text_len_type', $article_text_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>

								<span class="gk-right">
									<?php $this->input_select('article_text_order', $article_text_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-text-order'); ?>
								</span>
							</div>
						</div>	
						
						<div class="gk-article-element" data-element-name="image" data-sort-pos="<?php echo $article_image_order; ?>">
							<?php $this->input_checkbox_before('article_image_state', $article_image_state, __('Show image', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_image_w' ) ); ?>"><?php _e( 'size:', 'gk-nsp' ); ?></label>
								
								<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_image_w' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_image_w' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_w ); ?>" class="short" />
								&times;
								<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_image_h' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_image_h' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_h ); ?>" class="short" />
								
								
							</div>

							<span class="gk-right">
								<?php $this->input_select('article_image_order', $article_image_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-image-order'); ?>
							</span>

							<div class="gk-indent">	
								<p>
									<label 
			                        	for="<?php echo esc_attr( $this->nsp->get_field_id( 'article_default_image' )); ?>"
			                        	title=""
			                        >
			                                <?php _e('Default image', 'gk-nsp'); ?>
			                        </label>
			                        <input 
			                                id="<?php echo esc_attr( $this->nsp->get_field_id( 'article_default_image' )); ?>" 
			                                type="text" 
			                                size="24" 
			                                name="<?php echo esc_attr( $this->nsp->get_field_name( 'article_default_image' )); ?>" 
			                                value="<?php echo $article_default_image; ?>" 
			                                 class="gk-media-input"                                 
			                        />
			                        <input id="<?php echo esc_attr( $this->nsp->get_field_id( 'article_default_image' )); ?>_button" class="gk-media button button-primary" type="button" value="<?php _e('Upload Image', 'gk-nsp'); ?>" />
                    			</p>
								<p><?php $this->input_select('article_image_filter', $article_image_filter, __('Image filter:', 'gk-nsp'), array('none' => __('None', 'gk-nsp'), 'greyscale' => __('Greyscale', 'gk-nsp'), 'sepia' => __('Sepia', 'gk-nsp'))); ?></p>
								
								<p><?php $this->input_checkbox('article_image_popup', $article_image_popup, __('Image popup', 'gk-nsp'), __('This option works only with the WordPress and WooCommerce data sources', 'gk-nsp')); ?></p>


								<p><?php $this->input_text('image_block_padding', $image_block_padding, __( 'Margin: ', 'gk-nsp' ), '', 'long'); ?></p>
								
								<p><?php $this->input_select('article_image_pos', $article_image_pos, __( 'Position:', 'gk-nsp' ), array('top' => __('Top', 'gk-nsp'), 'left' => __('Left', 'gk-nsp'))); ?></p>	
							</div>
						</div>	
						
						
									
						<div class="gk-article-element" data-element-name="info" data-sort-pos="<?php echo $article_info_order; ?>">
							<?php $this->input_checkbox_before('article_info_state', $article_info_state, __('Show info block', 'gk-nsp')); ?>
							
							<span class="gk-right">
								<?php $this->input_select('article_info_order', $article_info_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-info-order'); ?>
							</span>
						
							<div class="gk-indent">
								<p>
									<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_info_format' ) ); ?>"><?php _e( 'Format:', 'gk-nsp' ); ?></label>
									
									<textarea class="gk-format-textarea" id="<?php echo esc_attr( $nsp->get_field_id( 'article_info_format' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_info_format' ) ); ?>" type="text"><?php echo esc_attr( $article_info_format ); ?></textarea>

									<small>
										<?php _e('You can use in the Format option following tags:', 'gk-nsp'); ?>
										<br />{DATE}, {CATEGORY}, {AUTHOR}, {COMMENTS}, {COMMENT_COUNT}, {STARS}, {PRICE}, {REVIEWS}, {CART}
									</small>
								</p>
								
								<p>	
									<label for="<?php echo esc_attr( $nsp->get_field_id( 'article_info_date_format' ) ); ?>"><?php _e( 'Date format:', 'gk-nsp' ); ?></label>
									
									<input id="<?php echo esc_attr( $nsp->get_field_id( 'article_info_date_format' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'article_info_date_format' ) ); ?>" type="text" value="<?php echo esc_attr( $article_info_date_format ); ?>" class="medium" />
								</p>
							</div>
						</div>
									
						<div class="gk-article-element" data-element-name="readmore" data-sort-pos="<?php echo $article_readmore_order; ?>">
							<?php $this->input_checkbox_before('article_readmore_state', $article_readmore_state, __('Show read more button', 'gk-nsp')); ?>
							
							<span class="gk-right">
								<?php $this->input_select('article_readmore_order', $article_readmore_order, __('order:', 'gk-nsp'), 5, '', 'gk-article-readmore-order'); ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			
			<h3 class="gk-toggler gk-article-wrapper-hide"><?php _e('Link format', 'gk-nsp'); ?></h3>
			<div class="gk-toggle gk-article-wrapper-hide">		
				<div>
					<div class="gk-link-elements">		
						<div class="gk-link-element" data-element-name="title">
							<?php $this->input_checkbox_before('links_title_state', $links_title_state, __('Show title', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<?php $this->input_text('links_title_len', $links_title_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>		
								<?php $this->input_select('links_title_len_type', $links_title_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>
							</div>
						</div>
									
						<div class="gk-link-element" data-element-name="text">
							<?php $this->input_checkbox_before('links_text_state', $links_text_state, __('Show text', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<?php $this->input_text('links_text_len', $links_text_len, __( 'length: ', 'gk-nsp' ), '', 'short'); ?>
								<?php $this->input_select('links_text_len_type', $links_text_len_type, '', array('chars' => __('Chars', 'gk-nsp'), 'words' => __('Words', 'gk-nsp'))); ?>
							</div>
						</div>	
						
						<div class="gk-link-element" data-element-name="image">
							<?php $this->input_checkbox_before('links_image_state', $links_image_state, __('Show image', 'gk-nsp')); ?>
							
							<div class="gk-additional">
								<label for="<?php echo esc_attr( $nsp->get_field_id( 'links_image_w' ) ); ?>"><?php _e( 'size:', 'gk-nsp' ); ?></label>
								
								<input id="<?php echo esc_attr( $nsp->get_field_id( 'links_image_w' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'links_image_w' ) ); ?>" type="text" value="<?php echo esc_attr( $links_image_w ); ?>" class="short" />
								&times;
								<input id="<?php echo esc_attr( $nsp->get_field_id( 'links_image_h' ) ); ?>" name="<?php echo esc_attr( $nsp->get_field_name( 'links_image_h' ) ); ?>" type="text" value="<?php echo esc_attr( $links_image_h ); ?>" class="short" />
							</div>

							<div class="gk-indent">	
								<p>
									<label 
			                        	for="<?php echo esc_attr( $this->nsp->get_field_id( 'links_default_image' )); ?>"
			                        	title=""
			                        >
			                                <?php _e('Default image', 'gk-nsp'); ?>
			                        </label>
			                        <input 
			                                id="<?php echo esc_attr( $this->nsp->get_field_id( 'links_default_image' )); ?>" 
			                                type="text" 
			                                size="24" 
			                                name="<?php echo esc_attr( $this->nsp->get_field_name( 'links_default_image' )); ?>" 
			                                value="<?php echo $links_default_image; ?>" 
			                                 class="gk-media-input"                                 
			                        />
			                        <input id="<?php echo esc_attr( $this->nsp->get_field_id( 'links_default_image' )); ?>_button" class="gk-media button button-primary" type="button" value="<?php _e('Upload Image', 'gk-nsp'); ?>" />
                    			</p>
								<p><?php $this->input_select('links_image_filter', $links_image_filter, __('Image filter:', 'gk-nsp'), array('none' => __('None', 'gk-nsp'), 'greyscale' => __('Greyscale', 'gk-nsp'), 'sepia' => __('Sepia', 'gk-nsp'))); ?></p>
								
								<p><?php $this->input_checkbox('links_image_popup', $links_image_popup, __('Image popup', 'gk-nsp'), __('This option works only with the WordPress and WooCommerce data sources', 'gk-nsp')); ?></p>


								<p><?php $this->input_text('links_image_block_padding', $links_image_block_padding, __( 'Margin: ', 'gk-nsp' ), '', 'long'); ?></p>
							</div>
						</div>
						
						<div class="gk-link-element" data-element-name="link">
							<?php $this->input_checkbox_before('links_readmorelink_state', $links_readmorelink_state, __('Show readmore link', 'gk-nsp')); ?>
						</div>

						<div class="gk-link-element" data-element-name="readmore">
							<?php $this->input_checkbox_before('links_readmore_state', $links_readmore_state, __('Show read more button under links', 'gk-nsp')); ?>
							
							<div class="gk-indent">	
								<p><?php $this->input_text('links_readmore_text', $links_readmore_text, __( 'Link text: ', 'gk-nsp' ), '', 'long'); ?></p>

								<p><?php $this->input_text('links_readmore_url', $links_readmore_url, __( 'Link URL: ', 'gk-nsp' ), '', 'long'); ?></p>

								<p><?php $this->input_checkbox('links_readmore_title_state', $links_readmore_title_state, __('Use this link also in the widget title', 'gk-nsp')); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<h3 class="gk-toggler"><?php _e('Advanced settings', 'gk-nsp'); ?></h3>
			<div class="gk-toggle">
				<div>
					<p><?php $this->input_text('cache_time', $cache_time, __( 'Cache time: ', 'gk-nsp' ), '', 'medium-right', ' min="0" max="9999999999"', 'number', __('min', 'gk-nsp')); ?></p>

					<p><?php $this->input_checkbox('parse_shortcodes', $parse_shortcodes, __('Parse shortcodes in the article text', 'gk-nsp')); ?></p>

					<p><?php $this->input_checkbox('fontawesome_state', $fontawesome_state, __('Load Font Awesome', 'gk-nsp')); ?></p>
				</div>
			</div>
		</div>
		
		<script>
		setTimeout(function() {
			jQuery('.gk-nsp-ui').each(function(i, el) {
				el = jQuery(el);
				// check if the module is not displayed in the popup
				if(!el.parent().hasClass('ui-dialog-content')) {
					var id = el.parent().parent().find('.widget-id').val();

					if(id.indexOf('gk_nsp-__i__') === -1) {
						var selected = (jQuery(document.body).hasClass('wp-customizer')) ? el.parent() : jQuery("div[id$='"+id+"']");
						
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
				} else {
					var selected = el.parent();

					function loadResources( url, callback ) {
						// add scripts
						var script = document.createElement( "script" )
						script.type = "text/javascript";
						script.setAttribute('id', 'gk-nsp-dialog-js');
						script.onload = function() { callback(); };
						script.src = url + '.js';
						document.getElementsByTagName( "head" )[0].appendChild( script );
						// add stylesheet
						var link = document.createElement( "link" )
						link.type = "text/css";
						link.setAttribute('id', 'gk-nsp-dialog-css');
						link.href = url + '.css';
						document.getElementsByTagName( "head" )[0].appendChild( link );
					}
					// load it if the script wasn't loaded or just init the widget UI
					if(!jQuery('#gk-nsp-dialog-js').length) {
						loadResources('<?php echo plugins_url('gk-nsp-admin', __FILE__); ?>', function() {
							var nsp = GK_NSP_UI();
							nsp.init(selected);
							selected.addClass('activated');
							
							setTimeout(function() {
								selected.find('.gk-loader').remove();
							}, 350);
						});
					} else {
						var nsp = GK_NSP_UI();
						nsp.init(selected);
						selected.addClass('activated');
							
						setTimeout(function() {
							selected.find('.gk-loader').remove();
						}, 350);
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
			id="<?php echo esc_attr( $this->nsp->get_field_id( $name )); ?>" 
			name="<?php echo esc_attr( $this->nsp->get_field_name( $name )); ?>" 
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

	function input_checkbox($name, $value, $label, $tip = '', $classes = '', $other = '') {
		?>
		<label
			for="<?php echo esc_attr( $this->nsp->get_field_id( $name ) ); ?>" 
			title="<?php echo $tip; ?>"
		>
		<?php echo $label; ?>
		</label>
		<input 
			type="checkbox"
			id="<?php echo esc_attr( $this->nsp->get_field_id( $name )); ?>" 
			name="<?php echo esc_attr( $this->nsp->get_field_name( $name )); ?>"
			value="on" 
			class="<?php echo $classes; ?>"
			<?php echo $other; ?>
			<?php if($value == 'on') : ?>
			checked="checked"
			<?php endif; ?>
		/>
		<?php 
	}

	function input_checkbox_before($name, $value, $label, $tip = '', $classes = '', $other = '') {
		?>
		<input 
			type="checkbox"
			id="<?php echo esc_attr( $this->nsp->get_field_id( $name )); ?>" 
			name="<?php echo esc_attr( $this->nsp->get_field_name( $name )); ?>"
			value="on" 
			class="<?php echo $classes; ?>"
			<?php echo $other; ?>
			<?php if($value == 'on') : ?>
			checked="checked"
			<?php endif; ?>
		/>
		<label
			for="<?php echo esc_attr( $this->nsp->get_field_id( $name ) ); ?>" 
			title="<?php echo $tip; ?>"
		>
		<?php echo $label; ?>
		</label>
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
		$data_source_path = $this->nsp->ds_path;
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
							"data_source_type"          => array(),
							"orderby"                   => '',
							"order"                     => '',
							"offset"                    => '',
							"data_source"               => '',
							"one_per_category"          => '',
							"json_filelist"				=> '',
							"xml_filelist"				=> '',
							"wp_category_list"          => '',
							"woocommerce_category_list" => '',
							"post_types_list"           => ''
						  );
		$fields = array(
						'orderby', 
						'order', 
						'offset', 
						'data_source', 
						'one_per_category',
						'json_filelist',
						'xml_filelist',
						'wp_category_list',
						'woocommerce_category_list',
						'post_types_list'
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
				
				foreach($fields as $field) {
					$json_data[$field] .= in_array($field, $data_source->fields) ? $data_source->name . ',' : '';	
				} 
			}
		}
		// change the ordering of data source options
		for($i = count($json_data_helper) - 1; $i >= 0; $i--) {
			array_unshift($json_data['data_source_type'], $json_data_helper[$i]);
		}

		foreach($fields as $field) {
			$json_data[$field] = $json_data[$field] != '' ? substr($json_data[$field], 0, -1) : $json_data[$field];	
		}
		
		return $json_data;
	}
}

// EOF
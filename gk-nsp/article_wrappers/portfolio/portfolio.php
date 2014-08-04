<?php

require_once(dirname(__FILE__) . GK_DS . 'helper.php');

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);

// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$portfolio_cols = $json_cache['portfolio']->params[0]->default;
if(isset($this->config['portfolio_cols'])) { $portfolio_cols = $this->config['portfolio_cols']; }
$portfolio_rows = $json_cache['portfolio']->params[1]->default;
if(isset($this->config['portfolio_rows'])) { $portfolio_rows = $this->config['portfolio_rows']; }
$portfolio_category_filtering = $json_cache['portfolio']->params[2]->default;
if(isset($this->config['portfolio_category_filtering'])) { $portfolio_category_filtering = $this->config['portfolio_category_filtering']; }
$portfolio_popup = $json_cache['portfolio']->params[3]->default;
if(isset($this->config['portfolio_overlay_popup'])) { $portfolio_popup = $this->config['portfolio_overlay_popup']; }
$portfolio_thumbnail_w = $json_cache['portfolio']->params[4]->default;
if(isset($this->config['portfolio_thumbnail_w'])) { $portfolio_thumbnail_w = $this->config['portfolio_thumbnail_w']; }
$portfolio_thumbnail_h = $json_cache['portfolio']->params[5]->default;
if(isset($this->config['portfolio_thumbnail_h'])) { $portfolio_thumbnail_h = $this->config['portfolio_thumbnail_h']; }
// override the links image dimensions
$this->config['links_image_w'] = $portfolio_thumbnail_w;
$this->config['links_image_h'] = $portfolio_thumbnail_h;

// generate the articles
$num_of_arts = $portfolio_cols * $portfolio_rows;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 

if($num_of_arts > 0) {
	// generate the widget wrapper
	echo '<div 
			class="'.$this->wdgt_class.' gk-nsp-portfolio" 
			data-cols="'.$portfolio_cols.'" 
			data-rows="'.$portfolio_rows.'" 
			data-popup="'.$portfolio_popup.'"
	>';

	$used_categories = array();

	if($portfolio_category_filtering == 'on') {
		echo '<ul class="gk-portfolio-categories">';
			echo '<li class="active">'.__('All', 'gk-nsp').'</li>';
	
			for($i = 0; $i < $num_of_arts; $i++) {
				$categories = GK_NSP_Article_Wrapper_portfolio::article_category($i, $this->generator, $results);
	
				if(count($categories) > 0) {
					foreach($categories as $category) {
						if(!in_array($category['name'], $used_categories)) {
							echo '<li>' . $category['name'] . '</li>';
							array_push($used_categories, $category['name']);
						}
					}
				}
			}
		echo '</ul>';
	}

	echo '<div class="gk-images-wrapper gk-images-cols'.$portfolio_cols.'">';

	for($i = 0; $i < $num_of_arts; $i++) {
		//
		$title = GK_NSP_Article_Wrapper_portfolio::article_title($i, $this->generator, $results);
		$url = GK_NSP_Article_Wrapper_portfolio::article_url($i, $this->generator);
		$thumbnail = GK_NSP_Article_Wrapper_portfolio::article_thumbnail($i, $this->generator);
		$full_image = GK_NSP_Article_Wrapper_portfolio::article_full_image($i, $this->generator);
		$date = GK_NSP_Article_Wrapper_portfolio::article_date($i, $this->generator, $results);
		$author = GK_NSP_Article_Wrapper_portfolio::article_author($i, $this->generator, $results);
		$categories = GK_NSP_Article_Wrapper_portfolio::article_category($i, $this->generator, $results);
		$category_names = '';
		$category_urls = '';
		
		foreach($categories as $category) {
			if($category['name'] != '') {
				$category_names .= $category['name'] . ';';
				$category_urls .= $category['url'] . ';';
			}
		}
		

		echo '<a 
				href="'.$url.'" 
				title="'.$title.'" 
				class="gk-image gk-nsp-art gk-nsp-col'.$portfolio_cols.' active"
				data-cat="'.$category_names.'"
				data-cat-url="'.$category_urls.'"
				data-cat-text="'.__('Category:', 'gk-nsp').'"
				data-date="'.$date.'"
				data-date-text="'.__('Date:', 'gk-nsp').'"
				data-author="'.$author.'"
				data-author-text="'.__('Author:', 'gk-nsp').'"
				data-img="'.$full_image.'"
			>';
		echo '<img src="'.$thumbnail.'" alt="'.$title.'" />';
		echo '</a>';
	}
	echo '</div>';
	echo '</div>';
} else {
	echo '<strong>Error:</strong> No articles to display';
}

// EOF
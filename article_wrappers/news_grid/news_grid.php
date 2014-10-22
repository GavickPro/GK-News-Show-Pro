<?php

require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'news_grid';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);


// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$news_grid_cols = $json_cache['news_grid']->params[0]->default;
if(isset($this->config['news_grid_cols'])) { $news_grid_cols = $this->config['news_grid_cols']; }
$news_grid_rows = $json_cache['news_grid']->params[1]->default;
if(isset($this->config['news_grid_rows'])) { $news_grid_rows = $this->config['news_grid_rows']; }
$news_grid_title_length = $json_cache['news_grid']->params[2]->default;
if(isset($this->config['news_grid_title_length'])) { $news_grid_title_length = $this->config['news_grid_title_length']; }
$news_grid_text_length = $json_cache['news_grid']->params[3]->default;
if(isset($this->config['news_grid_text_length'])) { $news_grid_text_length = $this->config['news_grid_text_length']; }
$news_grid_date_format = $json_cache['news_grid']->params[4]->default;
if(isset($this->config['news_grid_date_format'])) { $news_grid_date_format = $this->config['news_grid_date_format']; }
$news_grid_link_url = $json_cache['news_grid']->params[5]->default;
if(isset($this->config['news_grid_link_url'])) { $news_grid_link_url = $this->config['news_grid_link_url']; }
$news_grid_link_label = $json_cache['news_grid']->params[6]->default;
if(isset($this->config['news_grid_link_label'])) { $news_grid_link_label = $this->config['news_grid_link_label']; }

// generate the articles
$num_of_arts =  $news_grid_cols * $news_grid_rows;

// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-nsp-news_grid" 
		data-cols="'.$news_grid_cols.'" 
		data-rows="'.$news_grid_rows.'"
>';

// wrap articles
for($i = 0; $i < $num_of_arts; $i++) {
	if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {		
		// calculate the inverse class
		$inverse_class = '';
		$rows = $news_grid_cols * 2;
		//
		if($i % $rows >= $news_grid_cols) {
			$inverse_class = ' class="inverse"';
		}

		$url = GK_NSP_Article_Wrapper_news_grid::url($i, $this->generator);
		$image = GK_NSP_Article_Wrapper_news_grid::image($i, $this->generator);
		$title = GK_NSP_Article_Wrapper_news_grid::title($i, $results);
		$text = GK_NSP_Article_Wrapper_news_grid::text($i, $this->generator);

		// output the HTML code
		echo '<figure'.$inverse_class.'>';
		if($image != '') {
			echo '<a href="'.$url.'" title="'.esc_attr($title).'">';
			echo '<img src="'.esc_attr($image).'" alt="'.esc_attr($title).'" />';
			echo '</a>';
		}
		echo '<figcaption>';
		echo '<div>';
		// Title
		if($news_grid_title_length > 0) {
			$cut_title = GK_NSP_Article_Wrapper_news_grid::cut_text($title, $news_grid_title_length);

			echo '<h3>';
			echo '<a href="'.$url.'" title="'.esc_attr($title).'">';
			echo $cut_title;
			echo '</a>';
			echo '</h3>';
		}
		// Date
		if($news_grid_date_format != '') {
			$date = GK_NSP_Article_Wrapper_news_grid::date($i, $news_grid_date_format, $results);
			$w3c_date = GK_NSP_Article_Wrapper_news_grid::w3c_date($i, $results); 
			echo '<time datetime="'.$w3c_date.'">'.$date.'</time>';
		}

		// Separator under the title/date
		if(
			$news_grid_title_length > 0 || 
			$news_grid_date_format != ''
		) {
			echo '<hr class="separator" />';
		}

		// Text
		if($news_grid_text_length > 0) {
			$cut_text = GK_NSP_Article_Wrapper_news_grid::cut_text($text, $news_grid_text_length);

			echo '<p>' . $cut_text . '</p>';
		}
		echo '</div>';
		echo '</figcaption>';
		echo '</figure>';
	}
}

// Link at the end
if($news_grid_link_label != '') {
	echo '<a href="' . $news_grid_link_url . '">' . $news_grid_link_label . '</a>';
}

echo '</div>';

// EOF
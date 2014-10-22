<?php

$base_path = $this->aw_path . GK_DS . 'news_gallery';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);


// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$news_gallery_amount = $json_cache['news_gallery']->params[0]->default;

if(isset($this->config['news_gallery_amount'])) {
	$news_gallery_amount = $this->config['news_gallery_amount'];
}

$news_gallery_pages = $json_cache['news_gallery']->params[1]->default;

if(isset($this->config['news_gallery_pages'])) {
	$news_gallery_pages = $this->config['news_gallery_pages'];
}

$news_gallery_autoanimation = $json_cache['news_gallery']->params[2]->default;

if(isset($this->config['news_gallery_autoanimation'])) {
	$news_gallery_autoanimation = $this->config['news_gallery_autoanimation'];
}

$news_gallery_animation_interval = $json_cache['news_gallery']->params[3]->default;

if(isset($this->config['news_gallery_animation_interval'])) {
	$news_gallery_animation_interval = $this->config['news_gallery_animation_interval'];
}


// generate the articles
$num_of_arts = $news_gallery_pages * $news_gallery_cols;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 
$num_of_art_pages = $news_gallery_pages;

if($num_of_arts >= count($results)) {
	$num_of_art_pages = ceil(count($results) / $news_gallery_cols);
}

// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-news-gallery" 
		data-cols="'.$news_gallery_cols.'" 
		data-pages="'.$news_gallery_pages.'" 
		data-autoanim="'.$news_gallery_autoanimation.'" 
		data-autoanimint="'.$news_gallery_animation_interval.'"
>';
// wrap articles
echo '<div class="gk-images-wrapper gk-images-cols'.$news_gallery_cols.'">';
//
for($i = 0; $i < $num_of_arts; $i++) {
	if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
		echo GK_NSP_Article_Wrapper_news_gallery::article_output($i, $this->generator, $news_gallery_cols);
	}
}
// closing the image wrapper
echo '</div>';
// pagination
if($num_of_art_pages > 1) {
	do_action('gk_nsp_news_gallery_before_nav');
	echo '<ul class="gk-pagination">';

	for($i = 1; $i <= $num_of_art_pages; $i++) {
		echo '<li'.($i == 1 ? ' class="active"' : '').'>'.$i.'</li>';
	}

	echo '</ul>';
	do_action('gk_nsp_news_gallery_after_nav');
}
// closing the widget wrapper
echo '</div>';

// EOF
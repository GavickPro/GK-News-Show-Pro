<?php
require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'news_slider';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);


// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');

$news_slider_articles = $json_cache['news_slider']->params[0]->default;
if(isset($this->config['news_slider_articles'])) {
	$news_slider_articles = $this->config['news_slider_articles'];
}

$news_slider_show_title = $json_cache['news_slider']->params[1]->default;
if(isset($this->config['news_slider_show_title'])) {
	$news_slider_show_title = $this->config['news_slider_show_title'];
}

$news_slider_show_text = $json_cache['news_slider']->params[2]->default;
if(isset($this->config['news_slider_show_text'])) {
	$news_slider_show_text= $this->config['news_slider_show_text'];
}

$news_slider_date_format = $json_cache['news_slider']->params[3]->default;
if(isset($this->config['news_slider_date_format'])) {
	$news_slider_date_format = $this->config['news_slider_date_format'];
}

$news_slider_animation = $json_cache['news_slider']->params[4]->default;
if(isset($this->config['news_slider_nimation_interval'])) {
	$news_slider_autoanimation = $this->config['news_slider_nimation_interval'];
}

$news_slider_small_img_margin = $json_cache['news_slider']->params[5]->default;
if(isset($this->config['news_slider_small_img_margin'])) {
	$news_slider_small_img_margin = $this->config['news_slider_small_img_margin'];
}

$news_slider_widget_label = $json_cache['news_slider']->params[6]->default;
if(isset($this->config['news_slider_widget_label'])) {
	$news_slider_widget_label = $this->config['news_slider_widget_label'];
}

$news_slider_cat_label = $json_cache['news_slider']->params[7]->default;
if(isset($this->config['news_slider_cat_label'])) {
	$news_slider_cat_label = $this->config['news_slider_cat_label'];
}

$news_slider_cat_url = $json_cache['news_slider']->params[8]->default;
if(isset($this->config['news_slider_cat_url'])) { 
	$news_slider_cat_url = $this->config['news_slider_cat_url']; 
}

if($news_slider_articles < 5) {
	echo 'This widget needs at least 5 articles to display.';
	return;
}
// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-news-slider" 
		data-autoanim="'.$news_slider_animation_interval.'"
		style="min-height: '.intval($this->config['article_image_h'] + 200.0).'px;"
>';

if($news_slider_widget_label != '') {
	echo '<h2>' . $news_slider_widget_label . '</h2>';
}


// render images
for($i = 0; $i < $news_slider_articles; $i++) {			
	$element_classname = '';
	$url = GK_NSP_Article_Wrapper_news_slider::url($i, $this->generator);
	$image = GK_NSP_Article_Wrapper_news_slider::image($i, $this->generator);
	$title = GK_NSP_Article_Wrapper_news_slider::title($i, $results);
	$text = GK_NSP_Article_Wrapper_news_slider::text($i, $this->generator);
	
	switch($i) {
		
		case 0:
			$element_classname = ' class="gk-prev-2"';
			break;
		case 1: 
			$element_classname = ' class="gk-prev-1"';
			break;
		case 2:
			$element_classname = ' class="gk-active"';
			break;
		case 3:
			$element_classname = ' class="gk-next-1"';
			break;
		case 4:
			$element_classname = ' class="gk-next-2"';
			break;
		case 5:
			$element_classname = ' class="gk-to-hide"';
			break;
		case 6:
			$element_classname = ' class="gk-to-show"';
			break;
		default:
			$element_classname = ' class="gk-hide"';
			break;
	}
	
	$element_sr = '';
	
	if($i == 0) {
		$element_sr = ' data-sr="enter right and move 200px and wait .6s"';
	}
	
	if($i == 1) {
		$element_sr = ' data-sr="enter right and move 100px and wait .4s"';
	}
	
	if($i == 2) {
		$element_sr = ' data-sr="enter bottom and move 100px"';
	}
	
	if($i == 3) {
		$element_sr = ' data-sr="enter left and move 100px and wait .4s"';
	}
	
	if($i == 4) {
		$element_sr = ' data-sr="enter left and move 200px and wait .6s"';
	}

	// output the HTML code
	echo '<figure'.$element_classname.$element_sr.' data-cat="'.$news_slider_cat_url.'">';

	if($image != '') {
		echo '<a href="'.$url.'" title="'.$title.'" class="gk-image-wrap">';
		echo '<img src="'.$image.'" style="margin: '.$news_slider_small_img_margin.';" alt="'.$title.'" />';
		echo '</a>';
	}

	echo '<figcaption>';
	
	if($news_slider_date_format != '') {
		$date = GK_NSP_Article_Wrapper_news_slider::date($i, $news_slider_date_format, $results);
		echo '<small>' . $date . '</small>';
	}

	if($news_slider_show_title == 'on') {
		echo '<h3>';
		echo '<a href="'.$url.'" title="'.strip_tags($title).'">';
		echo $title;
		echo '</a>';
		echo '</h3>';
	}

	if($news_slider_show_text == 'on') {
		echo '<p>' . $text . '</p>';
	}

	echo '</figcaption>';
	echo '</figure>';
}

$to_hide_link = '';
		
if($news_slider_cat_label == '') {
	$to_hide_link = ' style="display: none;"';
}

echo '<a href="#" class="gk-data-category-link"'.$to_hide_link.'>'.$news_slider_cat_label.'</a>';

// closing the widget wrapper
echo '</div>';

// EOF
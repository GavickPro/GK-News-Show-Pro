<?php
require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'technews_reviews';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);

// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');

$technews_reviews_articles = $json_cache['technews_reviews']->params[0]->default;
if(isset($this->config['technews_reviews_articles'])) {
	$technews_reviews_articles = $this->config['technews_reviews_articles'];
}

$technews_reviews_widget_intro = $json_cache['technews_reviews']->params[1]->default;
if(isset($this->config['technews_reviews_widget_intro'])) {
	$technews_reviews_widget_intro = $this->config['technews_reviews_widget_intro'];
}

$technews_reviews_button_label = $json_cache['technews_reviews']->params[2]->default;
if(isset($this->config['technews_reviews_button_label'])) {
	$technews_reviews_button_label = $this->config['technews_reviews_button_label'];
}

$technews_reviews_button_url = $json_cache['technews_reviews']->params[3]->default;
if(isset($this->config['technews_reviews_button_url'])) { 
	$technews_reviews_button_url = $this->config['technews_reviews_button_url']; 
}

$technews_reviews_animation_autoanim = $json_cache['technews_reviews']->params[4]->default;
if(isset($this->config['technews_reviews_animation_autoanim'])) {
	$technews_reviews_animation_autoanim = $this->config['technews_reviews_animation_autoanim'];
}

$technews_reviews_animation_interval = $json_cache['technews_reviews']->params[5]->default;
if(isset($this->config['technews_reviews_animation_interval'])) {
	$technews_reviews_animation_interval = $this->config['technews_reviews_animation_interval'];
}

if($technews_reviews_articles < 1) {
	echo 'This widget needs at least 1 article to display.';
	return;
}
// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-technews-reviews gk-clearfix" 
		data-autoanim="'.$technews_reviews_animation_autoanim.'"
		data-interval="'.$technews_reviews_animation_interval.'"
>';

// render sidebar
echo '<div class="gk-sidebar-reviews">';

	if($technews_reviews_widget_intro != '') {
		echo '<div class="gk-sidebar-intro">' . $technews_reviews_widget_intro . '</div>';
	}

	if($technews_reviews_button_url != '') {
		echo '<a href="'.$technews_reviews_button_url.'" class="btn button-gray">'.$technews_reviews_button_label.'</a>';
	}
		
	echo '<ul class="gk-sidebar-list">';
	
	for($i = 0; $i < $technews_reviews_articles; $i++) {
		$title = GK_NSP_Article_Wrapper_technews_reviews::title($i, $results);			
		echo '<li'.(($i == 0) ? ' class="gk-active"' : '').'>';
		echo '<span>'.strip_tags($title).'</span>';
		echo '</li>';	
	}
	
	echo '</ul>';
		
echo '</div><!-- .gk-sidebar-reviews -->';

// render slides
echo '<div class="gk-content-reviews">';
for($j = 0; $j < $technews_reviews_articles; $j++) {			
	$url = GK_NSP_Article_Wrapper_technews_reviews::url($j, $this->generator);
	$image = GK_NSP_Article_Wrapper_technews_reviews::image($j, $this->generator);
	$title = GK_NSP_Article_Wrapper_technews_reviews::title($j, $results);
	$text = GK_NSP_Article_Wrapper_technews_reviews::text($j, $this->generator);
	$author = GK_NSP_Article_Wrapper_technews_reviews::author($j, $this->generator, $results);
	$rating = GK_NSP_Article_Wrapper_technews_reviews::rating($j, $results);

	// output the HTML code
	echo '<div class="gk-content-review'.(($j == 0) ? ' gk-active' : '').'">';
			echo '<a href="'.$url.'" class="gk-content-img">';
			echo '<img src="'.$image.'" alt="'.$title.'">';
			echo '</a>';
			echo '<div class="gk-content-title">';
			echo $rating;
			echo '<h3>';
			echo '<a href="'.$url.'" class="inverse">';
			echo $title;
			echo '</a>';
			echo '</h3>';
			echo '<small>'. __('Review by ','gk-nsp') . $author.'</small>';
			echo '</div>';
	echo '</div>';

}

	echo '</div>';

// closing the widget wrapper
echo '</div>';

// EOF
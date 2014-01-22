<?php

require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'title_overlay';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);

// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$videogallery_popup_switcher = $json_cache['videogallery']->params[0]->default;

if(isset($this->config['videogallery_popup_switcher'])) {
	$videogallery_popup_switcher = $this->config['videogallery_popup_switcher'];
}

$videogallery_popup_w = $json_cache['videogallery']->params[1]->default;

if(isset($this->config['videogallery_popup_w'])) {
	$videogallery_popup_w = $this->config['videogallery_popup_w'];
}

$videogallery_popup_h = $json_cache['videogallery']->params[2]->default;

if(isset($this->config['videogallery_popup_h'])) {
	$videogallery_popup_h = $this->config['videogallery_popup_h'];
}

$videogallery_pages = $json_cache['videogallery']->params[3]->default;

if(isset($this->config['videogallery_pages'])) {
	$videogallery_pages = $this->config['videogallery_pages'];
}

$videogallery_cols = $json_cache['videogallery']->params[4]->default;

if(isset($this->config['videogallery_cols'])) {
	$videogallery_cols = $this->config['videogallery_cols'];
}

// generate the articles
$num_of_arts = $videogallery_pages * $videogallery_cols;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 
$num_of_art_pages = $videogallery_pages;

if($num_of_arts >= count($results)) {
	$num_of_art_pages = ceil(count($results) / $videogallery_cols);
}

if($num_of_arts > 0) {
	// generate the widget wrapper
	echo '<div 
			class="'.$this->wdgt_class.' gk-nsp-videogallery" 
			data-cols="'.$videogallery_cols.'" 
			data-pages="'.$videogallery_pages.'"  
			data-autoanimint="'.$videogallery_animation_interval.'"
	>';

	for($i = 0; $i < $num_of_arts; $i++) {
		if($i == 0) {
			echo '<div class="gk-big-block active'.(($videogallery_popup_switcher == 'on') ? ' popup' : '').'">';
			echo '<img class="gk-is-helper-image" src="data:image/png;base64,'.GK_NSP_Article_Wrapper_videogallery::generateBlankImage($this->config['article_image_w'], $this->config['article_image_h']).'" alt="" />';
			echo '<figure class="gk-item' .(GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) != '' ? ' video' : ''). '">';
			echo '<a href="'. ((GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) != '') ? GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) . '?TB_iframe=true&amp;width='.$videogallery_popup_w.'&amp;height='.$videogallery_popup_h : '').'" class="gk-image-wrap'.(($videogallery_popup_switcher == 'on') ? ' thickbox' : '').'"><img src="'.GK_NSP_Article_Wrapper_videogallery::get_image($i, $this->generator).'" data-url="'. ((GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) != '') ? GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) . '?TB_iframe=true&amp;width='.$videogallery_popup_w.'&amp;height='.$videogallery_popup_h : '').'" alt="" /></a>';
			echo '<figcaption>';
			echo '<strong>'.GK_NSP_Article_Wrapper_videogallery::get_category($i, $results).'</strong>';
			echo GK_NSP_Article_Wrapper_videogallery::article_header($i, $this->generator);
			echo '<p>'.GK_NSP_Article_Wrapper_videogallery::get_text($i, $this->generator).'</p>';
			echo '</figcaption>';
			echo '</figure>';
		
			echo '</div><div class="gk-small-block">';
		}
		
		if($i == 0) {
			echo '<div class="gk-items-page active">';
		}
		echo '<figure class="gk-item' .(GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) != '' ? ' video' : ''). '" data-num="'.$i.'">';
			echo '<a href="" class="gk-image-wrap"><img src="'.GK_NSP_Article_Wrapper_videogallery::get_image($i, $this->generator).'" alt="" data-url="'. ((GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) != '') ? GK_NSP_Article_Wrapper_videogallery::get_video($i, $this->generator, $results) . '?TB_iframe=true&amp;width='.$videogallery_popup_w.'&amp;height='.$videogallery_popup_h : '').'" /></a>';
			echo '<figcaption>';
			echo '<strong>'.GK_NSP_Article_Wrapper_videogallery::get_category($i, $results).'</strong>';
			echo GK_NSP_Article_Wrapper_videogallery::article_header($i, $this->generator);
			echo '<p>'.GK_NSP_Article_Wrapper_videogallery::get_text($i, $this->generator).'</p>';
			echo '<small>'.GK_NSP_Article_Wrapper_videogallery::get_comments($i, $results).'</small>';
			echo '</figcaption>';
		echo '</figure>';
		
		if(($i > 0 && ($i+1) % $videogallery_cols == 0 && $i != $num_of_arts -1) || ($videogallery_cols == 1 && $i != $num_of_arts -1 )){
			echo '</div>';
			echo '<div class="gk-items-page">';
		} elseif($i == $num_of_arts - 1) {
			echo '</div>';
		}
	}
	echo '</div>';
	echo '</div>';
} else {
	echo '<strong>Error:</strong> No articles to display';
}

// EOF
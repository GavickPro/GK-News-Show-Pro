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
$video_list_popup_switcher = $json_cache['video_list']->params[0]->default;

if(isset($this->config['video_list_popup_switcher'])) {
	$video_list_popup_switcher = $this->config['video_list_popup_switcher'];
}

$video_list_popup_w = $json_cache['video_list']->params[1]->default;

if(isset($this->config['video_list_popup_w'])) {
	$video_list_popup_w = $this->config['video_list_popup_w'];
}

$video_list_popup_h = $json_cache['video_list']->params[2]->default;

if(isset($this->config['video_list_popup_h'])) {
	$video_list_popup_h = $this->config['video_list_popup_h'];
}

$video_list_pages = $json_cache['video_list']->params[3]->default;

if(isset($this->config['video_list_pages'])) {
	$video_list_pages = $this->config['video_list_pages'];
}

$video_list_cols = $json_cache['video_list']->params[4]->default;

if(isset($this->config['video_list_cols'])) {
	$video_list_cols = $this->config['video_list_cols'];
}

$video_list_title_len = $json_cache['video_list']->params[5]->default;

if(isset($this->config['video_list_title_len'])) {
	$video_list_title_len = $this->config['video_list_title_len'];
}

// generate the articles
$num_of_arts = $video_list_pages * $video_list_cols;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 
$num_of_art_pages = $video_list_pages;

if($num_of_arts >= count($results)) {
	$num_of_art_pages = ceil(count($results) / $video_list_cols);
}

if($num_of_arts > 0) {
	// generate the widget wrapper
	echo '<div 
			class="'.$this->wdgt_class.' gk-nsp-video_list" 
			data-cols="'.$video_list_cols.'" 
			data-pages="'.$video_list_pages.'" 
	>';
	echo '<div>';

	for($i = 0; $i < $num_of_arts; $i++) {
		if($i == 0) {
			echo '<div class="gk-nsp-items-page active" data-cols="'.$video_list_cols.'">';
		}
		//
		$title = GK_NSP_Article_Wrapper_video_list::article_title($i, $this->generator, $results, $video_list_title_len);
		$video = GK_NSP_Article_Wrapper_video_list::article_video($i, $this->generator, $results);
		$url = GK_NSP_Article_Wrapper_video_list::article_url($i, $this->generator);
		$image = GK_NSP_Article_Wrapper_video_list::article_image($i, $this->generator);
		$date = GK_NSP_Article_Wrapper_video_list::article_date($i, $this->generator, $results);
		//
		echo '<figure class="gk-nsp-item'.($video != '' ? ' video' : '').($i < $video_list_cols ? ' active' : '').'">';
			echo '<a href="'.(($video != '') ? $video . '?TB_iframe=true&amp;width='.$video_list_popup_w.'&amp;height='.$video_list_popup_h : $url).'" class="gk-nsp-image-wrap'.(($video_list_popup_switcher == 'on' && $video != '') ? ' thickbox' : '').'"><img src="'.$image.'" data-url="'. (($video != '') ? $video . '?TB_iframe=true&amp;width='.$video_list_popup_w.'&amp;height='.$video_list_popup_h : '').'" alt="" /></a>';

			echo '<figcaption>';
				echo '<h3><a href="'.$url.'" title="'.esc_attr($title).'">'.$title.'</a></h3>';
				echo '<strong>' . $date . '</strong>';
			echo '</figcaption>';
		echo '</figure>';

		if(
			(
				$i > 0 && 
				($i+1) % $video_list_cols == 0 && 
				$i != $num_of_arts - 1
			) || 
			(
				$video_list_cols == 1 && 
				$i != $num_of_arts - 1
			)
		) {
			echo '</div>';
			echo '<div class="gk-nsp-items-page" data-cols="'.$video_list_cols.'">';
		} elseif($i == $num_of_arts - 1) {
			echo '</div>';
		}
	}
	echo '</div>';
		
	// pagination
	if($num_of_arts > $video_list_cols) {
		echo '<div class="gk-nsp-bottom-nav">';
		echo '<a href="#" class="gk-nsp-bottom-nav-prev">&laquo;</a>';

		echo '<ul class="gk-nsp-bottom-nav-pagination">';
		for($i = 0; $i < ceil($num_of_arts / $video_list_cols); $i++) {
			echo '<li'.($i == 0 ? ' class="active"': '').'>'.($i+1).'</li>';
		}
		echo '</ul>';

		echo '<a href="#" class="gk-nsp-bottom-nav-next">&raquo;</a>';
		echo '</div>';
	}
	
	echo '</div>';
} else {
	echo '<strong>Error:</strong> No articles to display';
}

// EOF
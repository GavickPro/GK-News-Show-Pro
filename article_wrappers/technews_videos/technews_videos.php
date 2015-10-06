<?php
require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'technews_videos';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);

// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');

$technews_videos_popup_switcher = $json_cache['technews_videos']->params[0]->default;
if(isset($this->config['technews_videos_popup_switcher'])) {
	$technews_videos_popup_switcher = $this->config['technews_videos_popup_switcher'];
}

$technews_videos_popup_w = $json_cache['technews_videos']->params[1]->default;
if(isset($this->config['technews_videos_popup_w'])) {
	$technews_videos_popup_w = $this->config['technews_videos_popup_w'];
}

$technews_videos_popup_h = $json_cache['technews_videos']->params[2]->default;
if(isset($this->config['technews_videos_popup_h'])) {
	$technews_videos_popup_h = $this->config['technews_videos_popup_h'];
}

$technews_videos_pages = $json_cache['technews_videos']->params[3]->default;
if(isset($this->config['technews_videos_pages'])) {
	$technews_videos_pages = $this->config['technews_videos_pages'];
}

$technews_videos_cols = $json_cache['technews_videos']->params[4]->default;
if(isset($this->config['technews_videos_cols'])) {
	$technews_videos_cols = $this->config['technews_videos_cols'];
}

$technews_videos_title_len = $json_cache['technews_videos']->params[5]->default;
if(isset($this->config['technews_videos_title_len'])) {
	$technews_videos_title_len = $this->config['technews_videos_title_len'];
}

$technews_videos_button_label = $json_cache['technews_videos']->params[6]->default;
if(isset($this->config['technews_videos_button_label'])) {
	$technews_videos_button_label = $this->config['technews_videos_button_label'];
}

$technews_videos_button_url = $json_cache['technews_videos']->params[7]->default;
if(isset($this->config['technews_videos_button_url'])) { 
	$technews_videos_button_url = $this->config['technews_videos_button_url']; 
}

// generate the articles
$num_of_arts = $technews_videos_pages * $technews_videos_cols;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 
$num_of_art_pages = $technews_videos_pages;

if($num_of_arts >= count($results)) {
	$num_of_art_pages = ceil(count($results) / $technews_videos_cols);
}

if($num_of_arts > 0) {
	// generate the widget wrapper
	echo '<div 
			class="'.$this->wdgt_class.' gk-nsp-technews_videos" 
			data-cols="'.$technews_videos_cols.'" 
			data-pages="'.$technews_videos_pages.'" 
	>';
	echo '<div>';

	for($i = 0; $i < $num_of_arts; $i++) {
		if($i == 0) {
			echo '<div class="gk-nsp-items-page active" data-cols="'.$technews_videos_cols.'">';
		}
		//
		$title = GK_NSP_Article_Wrapper_technews_videos::article_title($i, $this->generator, $results, $technews_videos_title_len);
		$video = GK_NSP_Article_Wrapper_technews_videos::article_video($i, $this->generator, $results);
		$url = GK_NSP_Article_Wrapper_technews_videos::article_url($i, $this->generator);
		$image = GK_NSP_Article_Wrapper_technews_videos::article_image($i, $this->generator);
		//$date = GK_NSP_Article_Wrapper_technews_videos::article_date($i, $this->generator, $results);
		//
		echo '<figure class="gk-nsp-item'.($video != '' ? ' video' : '').($i < $technews_videos_cols ? ' active' : '').'">';
			echo '<span class="gk-image-wrap"><a href="'.(($video != '') ? $video . '?TB_iframe=true&amp;width='.$technews_videos_popup_w.'&amp;height='.$technews_videos_popup_h : $url).'" class="gk-nsp-image-wrap'.(($technews_videos_popup_switcher == 1 && $video != '') ? ' thickbox' : '').'"><img src="'.$image.'" data-url="'. (($video != '') ? $video . '?TB_iframe=true&amp;width='.$technews_videos_popup_w.'&amp;height='.$technews_videos_popup_h : '').'" alt="" /></a></span>';

			echo '<figcaption>';
				echo '<h3><a href="'.$url.'" title="'.esc_attr($title).'">'.$title.'</a></h3>';
			echo '</figcaption>';
		echo '</figure>';

		if(
			(
				$i > 0 && 
				($i+1) % $technews_videos_cols == 0 && 
				$i != $num_of_arts - 1
			) || 
			(
				$technews_videos_cols == 1 && 
				$i != $num_of_arts - 1
			)
		) {
			echo '</div>';
			echo '<div class="gk-nsp-items-page" data-cols="'.$technews_videos_cols.'">';
		} elseif($i == $num_of_arts - 1) {
			echo '</div>';
		}
	}

	if($technews_videos_button_url != '') {
		echo '<a href="'.$technews_videos_button_url.'" class="btn button-gray">'.$technews_videos_button_label.'</a>';
	}

	echo '</div>';

	// pagination
	if($num_of_arts > $technews_videos_cols) {
		echo '<div class="gk-nsp-bottom-nav">';
		echo '<a href="#" class="gk-nsp-bottom-nav-prev">&laquo;</a>';

		echo '<ul class="gk-nsp-bottom-nav-pagination">';
		for($i = 0; $i < ceil($num_of_arts / $technews_videos_cols); $i++) {
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
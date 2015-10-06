<?php
require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'technews_rating';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);

// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');

$technews_rating_articles = $json_cache['technews_rating']->params[0]->default;
if(isset($this->config['technews_rating_articles'])) {
	$technews_rating_articles = $this->config['technews_rating_articles'];
}

if($technews_rating_articles < 1) {
	echo 'This widget needs at least 1 article to display.';
	return;
}
// generate the widget wrapper
echo '<div class="'.$this->wdgt_class.' gk-technews-rating">';

// render articles
for($j = 0; $j < $technews_rating_articles; $j++) {			
	$url = GK_NSP_Article_Wrapper_technews_rating::url($j, $this->generator);
	$title = GK_NSP_Article_Wrapper_technews_rating::title($j, $results);
	$author = GK_NSP_Article_Wrapper_technews_rating::author($j, $this->generator, $results);
	$rating = GK_NSP_Article_Wrapper_technews_rating::rating($j, $results);

	// output the HTML code
	echo '<div class="gk-item">';
	echo '	<div class="gk-item-rating">'.$rating.'</div>';
	echo '  <div class="gk-item-content">';
	echo '	  <h3 class="gk-item-title"><a href="'.$url.'">'.$title.'</a></h3>';
	echo '    <span class="gk-item-author">' . __('By ','gk-nsp') . $author. '</span>';
	echo '  </div>';
	echo '</div>';
			
}

// closing the widget wrapper
echo '</div>';

// EOF
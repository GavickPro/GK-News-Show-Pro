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
$title_overlay_padding = $json_cache['title_overlay']->params[0]->default;

if(isset($this->config['title_overlay_padding'])) {
	$title_overlay_padding = $this->config['title_overlay_padding'];
}

$title_overlay_width = $json_cache['title_overlay']->params[1]->default;

if(isset($this->config['title_overlay_width'])) {
	$title_overlay_width = $this->config['title_overlay_width'];
}

$title_overlay_pos = $json_cache['title_overlay']->params[2]->default;

if(isset($this->config['title_overlay_pos'])) {
	$title_overlay_pos = $this->config['title_overlay_pos'];
}

$title_overlay_color = $json_cache['title_overlay']->params[3]->default;

if(isset($this->config['title_overlay_color'])) {
	$title_overlay_color = $this->config['title_overlay_color'];
}

// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-title-overlay" 
		data-textpos="'.$title_overlay_pos.'" 
		data-textcolor="'.$title_overlay_color.'" 
>';
// wrap articles
do_action('gk_nsp_title_overlay_before_img');
echo '<figure>';
// there is only one article so we won't need the for loop
if(isset($results[0]) || (is_array($results[0]) && isset($results[0][0]))) {
	echo GK_NSP_Article_Wrapper_title_overlay::article_output($this->generator, $title_overlay_padding, $title_overlay_width);
}
// closing the image wrapper
echo '</figure>';
do_action('gk_nsp_title_overlay_after_img');
// closing the widget wrapper
echo '</div>';

// EOF
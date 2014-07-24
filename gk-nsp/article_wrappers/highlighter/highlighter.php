<?php

require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'highlights';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);


// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$highlighter_articles = $json_cache['highlighter']->params[0]->default;
if(isset($this->config['highlighter_articles'])) $highlights_articles = $this->config['highlighter_articles'];

$highlighter_text_len = $json_cache['highlighter']->params[1]->default;
if(isset($this->config['highlighter_text_len'])) $highlighter_text_len = $this->config['highlighter_text_len'];

$highlighter_intro_text = $json_cache['highlighter']->params[2]->default;
if(isset($this->config['highlighter_intro_text'])) $highlighter_intro_text = $this->config['highlighter_intro_text'];

$highlighter_animation_interval = $json_cache['highlighter']->params[3]->default;
if(isset($this->config['highlighter_animation_interval'])) $highlighter_animation_interval = $this->config['highlighter_animation_interval'];

$highlighter_animation_speed = $json_cache['highlighter']->params[4]->default;
if(isset($this->config['highlighter_animation_speed'])) $highlighter_animation_speed = $this->config['highlighter_animation_speed'];

// generate the articles
$num_of_arts =  $highlighter_articles;

// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-nsp-highlighter" 
		data-speed="'.$highlighter_animation_speed.'" 
		data-interval="'.$highlighter_animation_interval.'"
>';
// intro text
if($highlighter_intro_text != '') { 
	echo '<strong>'.$highlighter_intro_text.'</strong>';
}

// interface
if(count($results) > 1) {
   echo '<div class="gk-nsp-highlighter-ui"><a href="#"></a><a href="#"></a></div>';
}

// wrap articles
echo '<div class="gk-nsp-highligher-items-wrap">';
echo '<ol class="gk-nsp-highlighter">';

for($i = 0; $i < $num_of_arts; $i++) {
	if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
		echo GK_NSP_Article_Wrapper_highlighter::article_output($i, $this->generator, $this->config);
	}
}

echo '</ol>';
echo '</div>';
echo '</div>';

// EOF

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
$highlights_more = $json_cache['highlights']->params[0]->default;

if(isset($this->config['highlights_more'])) {
	$highlights_more = $this->config['highlights_more'];
}

$highlights_more_text = $json_cache['highlights']->params[1]->default;

if(isset($this->config['highlights_more_text'])) {
	$highlights_more_text = $this->config['highlights_more_text'];
}

// generate the articles
$num_of_arts =  $highlights_articles;


// generate the widget wrapper
echo '<ol class="gk-nsp-highlights">';
// wrap articles
for($i = 0; $i < $num_of_arts; $i++) {
	if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
		echo GK_NSP_Article_Wrapper_highlights::article_output($i, $this->generator);
	}
}
// closing the widget wrapper
echo '</ol>';
echo '<a href="' . $highlights_more . '">'.$highlights_more_text.'</a>';

// EOF
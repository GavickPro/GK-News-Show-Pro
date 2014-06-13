<?php

require_once(dirname(__FILE__) . GK_DS . 'helper.php');
$base_path = $this->aw_path . GK_DS . 'events';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);


// get the options
$json_cache = get_option('widget_gk_nsp_json_cache');
$events_columns = $json_cache['events']->params[0]->default;
if(isset($this->config['events_columns'])) $highlights_articles = $this->config['events_columns'];

$events_rows = $json_cache['events']->params[1]->default;
if(isset($this->config['events_rows'])) $events_text_len = $this->config['events_rows'];

// generate the articles
$num_of_arts =  $events_columns * $events_rows;

// generate the widget wrapper
echo '<div 
		class="'.$this->wdgt_class.' gk-nsp-events" 
		data-cols="'.$events_columns.'" 
		data-rows="'.$events_rows.'"
>';

// wrap articles
for($i = 0; $i < $num_of_arts; $i++) {
	if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
		echo '<div>';
		echo GK_NSP_Article_Wrapper_events::event_dateblock($i, $this->generator, $results);
			echo '<div>';
			echo GK_NSP_Article_Wrapper_events::article_output($i, $this->generator, $this->config);
			echo GK_NSP_Article_Wrapper_events::event_date($i, $this->generator, $results);
			echo '</div>';
		echo '</div>';
	}
}

echo '</div>';

// EOF
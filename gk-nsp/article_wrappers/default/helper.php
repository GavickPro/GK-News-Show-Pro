<?php

if(!class_exists('GK_NSP_Article_Wrapper_default')) {
	class GK_NSP_Article_Wrapper_default {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return ($config['article_pages'] * $config['article_cols'] * $config['article_rows']) + ($config['links_pages'] * $config['links_rows']);
		}
		
		/*
	     * Method used to generate the article output
		 */
		static function article_output($i, $generator, $config, $results, $data_source_class, $af_path) {
			//
			$art_output = '';
			// if article format is disabled
			if($config['article_format'] == 'none') {
				$art_title    = $config['article_title_state'] == 'on'    ? $generator->art_title($i)    : '';
				$art_text     = $config['article_text_state'] == 'on'     ? $generator->art_text($i)     : '';
				$art_image    = $config['article_image_state'] == 'on'    ? $generator->art_image($i)    : '';
				$art_info     = $config['article_info_state'] == 'on'     ? $generator->art_info($i)     : '';
				$art_readmore = $config['article_readmore_state'] == 'on' ? $generator->art_readmore($i) : '';
				//
				for($j = 1; $j <= 5; $j++) {
					// open the content wrap if necessary
					if(
						$config['article_image_pos'] == 'left' && 
						$config['article_image_order'] == 1 && 
						$j == 2
					) {
						$art_output .= '<div class="gk-nsp-content-wrap">';
					}
					// generate the article elements
					if($config['article_title_order'] == $j)    $art_output .= $art_title;
					if($config['article_text_order'] == $j)     $art_output .= $art_text;
					if($config['article_image_order'] == $j)    $art_output .= $art_image;
					if($config['article_info_order'] == $j)     $art_output .= $art_info;
					if($config['article_readmore_order'] == $j) $art_output .= $art_readmore;
				}
				// close the content wrap
				if(
					$config['article_image_pos'] == 'left' && 
					$config['article_image_order'] == 1
				) {
					$art_output .= '</div>';
				}
			} else {
				$temp_output = file_get_contents($af_path . GK_DS . $config['article_format']);

				if(is_array($results[0]) && isset($results[0][$i])) {
					// PHP >= 5.3.* version
					// $replacements = $data_source_class::get_article_format_mapping($results[0][$i], $config);
					// PHP 5.2.* version
					$replacements = call_user_func(array($data_source_class, 'get_article_format_mapping'), $results[0][$i], $config, $generator, $i);
				} else {
					// PHP >= 5.3.* version
					// $replacements = $data_source_class::get_article_format_mapping($results[$i], $config);
					// PHP 5.2.* version
					$replacements = call_user_func(array($data_source_class, 'get_article_format_mapping'), $results[$i], $config, $generator, $i);
				}

				foreach($replacements as $toreplace => $replacement) {
					$temp_output = str_replace($toreplace, $replacement, $temp_output);
				}

				$art_output = $temp_output;
			}

			return $art_output;
		}
	}
}

// EOF
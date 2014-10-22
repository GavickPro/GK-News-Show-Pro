<?php

if(!class_exists('GK_NSP_Article_Wrapper_highlighter')) {
	class GK_NSP_Article_Wrapper_highlighter {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['highlighter_articles'];
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_output($i, $generator, $config) {
			//
			$art_output = '';
			$art_title  = $generator->art_title($i, true);
			$art_text   = $generator->art_text($i, true);
			$art_url    = $generator->art_readmore($i, true);
			$art_final_text = $art_title . ': ' . $art_text;
			$art_final_text = function_exists('mb_substr') ? mb_substr($art_final_text, 0, $config['highlighter_text_len']) : substr($art_final_text, 0, $config['highlighter_text_len']);
			//
			$art_output .= '<li'.($i == 0 ? ' class="active"' : '').'>';
			$art_output .= '<a href="'.$art_url.'" title="'.$art_title.'"> ' . $art_final_text .'</a>';			
			$art_output .= '</li>';
			//
			return $art_output;
		}
	}
}

// EOF
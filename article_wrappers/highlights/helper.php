<?php

if(!class_exists('GK_NSP_Article_Wrapper_highlights')) {
	class GK_NSP_Article_Wrapper_highlights {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['highlights_articles'];
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_output($i, $generator) {
			//
			$art_output = '';
			$art_title  = $generator->art_title($i, true);
			$art_info   = $generator->art_info($i, true);
			$art_url    = $generator->art_readmore($i, true);

			//
			$art_output .= '<li>';
			$art_output .= '<h3>';
			$art_output .= '<a href="'.$art_url.'" title="'.$art_title.'"> ' . $art_title .'</a>';			
			$art_output .= '</h3>';
			$art_output .= $art_info;
			$art_output .= '</li>';
			//
			return $art_output;
		}
	}
}

// EOF
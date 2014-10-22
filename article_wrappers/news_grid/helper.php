<?php

if(!class_exists('GK_NSP_Article_Wrapper_news_grid')) {
	class GK_NSP_Article_Wrapper_news_grid {
		/*
	     * Method used to generate the number of necessary articles
		 */

		static function number_of_articles($config) {
			// total amount of the posts

			if(!isset($config['news_grid_cols'])) {
				$config['news_grid_cols'] = 2;
			}

			if(!isset($config['news_grid_rows'])) {
				$config['news_grid_rows'] = 2;
			}

			return $config['news_grid_cols'] * $config['news_grid_rows'];
		}

		static function url($i, $generator) {
			return $generator->art_readmore($i, true);
		}
		
		static function image($i, $generator) {
			return $generator->art_image($i, true);
		}

		static function title($i, $results) {
			return $results[$i]->post_title;
		}

		static function text($i, $generator) {
			return $generator->art_text($i, true);
		}

		static function date($i, $format, $results) {
			return get_the_time($format, $results[$i]->ID);
		}

		static function w3c_date($i, $results) {
			return get_the_time('c', $results[$i]->ID);
		}

		static function cut_text($text, $limit) {
			$text = strip_tags($text);

			if(function_exists('mb_substr') && mb_strlen($text) > $limit) {
				return mb_substr($text, 0, $limit) . '&hellip;';
			}

			if(strlen($text) > $limit) {
				return substr($text, 0, $limit) . '&hellip;';	
			}

			return $text;
		}
	}
}

// EOF
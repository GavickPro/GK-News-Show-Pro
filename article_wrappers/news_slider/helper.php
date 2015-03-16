<?php

if(!class_exists('GK_NSP_Article_Wrapper_news_slider')) {
	class GK_NSP_Article_Wrapper_news_slider {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['news_slider_articles'];
		}

		static function image_height($config) {
			// total amount of the posts
			return $config['article_image_h'];
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
		
	}
}

// EOF
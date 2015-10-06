<?php

if(!class_exists('GK_NSP_Article_Wrapper_technews_reviews')) {
	class GK_NSP_Article_Wrapper_technews_reviews {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['technews_reviews_articles'];
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

		static function author($i, $generator, $results) {
			return get_the_author_meta('display_name', $results[$i]->post_author);
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

		// rating generator
		static function rating($i, $results) {		
			$input = $results[$i]->post_content;
			
			$rating = array();
			$matches = array();
			$output = '';

			preg_match('/\[gkreview(.*?)\]/', $input, $matches);
			preg_match('/fields="(.*?)\"/', $matches[0], $fields);
			preg_match('/max="(.*?)\"/', $matches[0], $max);
			preg_match('/decimal="(.*?)\"/', $matches[0], $decimal);

			if(!empty($max)){
				$max = intval(trim($max[1]));
			} else {
				$max = 5;
			}

			if(!empty($decimal)){
				$decimal = intval(trim($decimal[1]));
			} else {
				$decimal = 0;
			}

			// Get rating data
			$rating_sum = 0;
			$rating = explode(',', $fields[1]);

			for($i = 0; $i < count($rating); $i++) {
				$rate = explode('=', $rating[$i]);
				$rating_sum += floatval(trim($rate[1]));
			}

			$rating_sum = round($rating_sum / count($rating), $decimal);

			// Result
			$output = '<span class="gk-review-sum-value" data-final="'.($rating_sum / $max).'"><span>'.$rating_sum.'</span></span>';
			return $output;
		}
		
	}
}

// EOF
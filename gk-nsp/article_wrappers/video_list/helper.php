<?php

if(!class_exists('GK_NSP_Article_Wrapper_video_list')) {
	class GK_NSP_Article_Wrapper_video_list {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['video_list_pages'] * $config['video_list_cols'];
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_url($i, $generator) {
			$art_url = $generator->art_readmore($i, true);
			return $art_url;
		}
		
		static function article_title($i, $generator, $results, $len) {
			$art_title = $results[$i]->post_title;
			
			$art_title = GK_NSP_Widget_Helpers::cut_text('article_title', $art_title, 'chars', $len);
			
			return $art_title;
		}
		
		static function article_image($i, $generator) {
			$art_image = '';
			$art_image  = $generator->art_image($i, true);
			return $art_image;
		}
		
		static function article_date($i, $generator, $results) {
			return get_the_time('M j, Y', $results[$i]->ID);
		}
		
		static function article_video($i, $generator, $results) {
			$art_ID =  $results[$i]->ID;
			$art_url = $generator->art_readmore($i, true);
			$video_url = get_post_meta($art_ID, "_gavern-featured-video", true);
			
			$pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';
   			preg_match($pattern, $video_url, $vid_matches);
   			
   			if(count($vid_matches) > 1) {
   				return str_replace('&', '&amp;', $vid_matches[1]);
   			} else {
   				return '';
   			}
		}
	}
}

// EOF
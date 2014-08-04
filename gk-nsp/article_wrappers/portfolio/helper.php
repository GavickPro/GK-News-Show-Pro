<?php

if(!class_exists('GK_NSP_Article_Wrapper_portfolio')) {
	class GK_NSP_Article_Wrapper_portfolio {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['portfolio_cols'] * $config['portfolio_rows'];
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_url($i, $generator) {
			$art_url = $generator->art_readmore($i, true);
			return $art_url;
		}
		
		static function article_title($i, $generator, $results) {
			$art_title = $results[$i]->post_title;
			
			return esc_attr($art_title);
		}
		
		static function article_thumbnail($i, $generator) {
			$art_image = '';
			$art_image  = $generator->art_image($i, true, 'links');
			return esc_attr($art_image);
		}

		static function article_full_image($i, $generator) {
			$art_image = '';
			$art_image  = $generator->art_image($i, true);
			return esc_attr($art_image);
		}
		
		static function article_date($i, $generator, $results) {
			return get_the_time('M j, Y', $results[$i]->ID);
		}

		static function article_category($i, $generator, $results) {
			$result = array('name' => '', 'url' => '');
			$categories = get_the_category($results[$i]->ID);

	 		if(count($categories) > 0) {
	 			$i = 0;
	 			foreach($categories as $cat) { 			
	 				$result[$i]['url'] = get_category_link( $cat->term_id );
	 				$result[$i]['name'] = $cat->name;
	 				$i++;
	 			}
	 		}

	 		return $result;
		}

		static function article_author($i, $generator, $results) {
			return get_the_author_meta('display_name', $results[$i]->post_author);
		}
	}
}

// EOF
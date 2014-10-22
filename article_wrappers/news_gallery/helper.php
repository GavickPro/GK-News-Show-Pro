<?php

if(!class_exists('GK_NSP_Article_Wrapper_news_gallery')) {
	class GK_NSP_Article_Wrapper_news_gallery {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['news_gallery_pages'] * $config['news_gallery_cols'];
		}
		
		/*
	     * Method used to generate the article output
		 */
		static function article_output($i, $generator, $cols) {
			//
			$art_output = '';
			$art_title  = $generator->art_title($i, true);
			$art_image  = $generator->art_image($i, true);
			$art_url    = $generator->art_readmore($i, true);
			//
			if($art_image != '') {
				$art_output .= '<a href="'.$art_url.'" title="'.$art_title.'" class="gk-image show'.(($i+1 <= $cols) ? ' active' : '').'">';
				$art_output .= '<img src="'.$art_image.'" alt="'.$art_title.'" />';
				$art_output .= '<div class="gk-img-overlay">' . apply_filters('gk_nsp_news_gallery_overlay_content', '<span>&raquo;</span>') . '</div>';
				$art_output .= '</a>';
			}
			//
			return $art_output;
		}
	}
}

// EOF
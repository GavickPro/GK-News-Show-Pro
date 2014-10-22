<?php

if(!class_exists('GK_NSP_Article_Wrapper_title_overlay')) {
	class GK_NSP_Article_Wrapper_title_overlay {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return 1;
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_output($generator, $padding, $width) {
			//
			$art_output = '';
			$art_title  = $generator->art_title(0, true);
			$art_image  = $generator->art_image(0, true);
			$art_url    = $generator->art_readmore(0, true);
			//
			if($art_image != '') {
				$art_output .= '<img src="'.$art_image.'" alt="'.$art_title.'" />';
				$art_output .= '<div class="gk-img-overlay">'.apply_filters('gk_nsp_title_overlay_content', '<span>&raquo;</span>'). '</div>';
				$art_output .= '<figcaption style="padding: '.$padding.'; width: '.$width.'%;">';
				$art_output .= apply_filters('gk_nsp_title_overlay_header', '<h3><a href="'.$art_url.'" title="'.$art_title.'">'.$art_title.'</a></h3>');
				$art_output .= '</figcaption>';
			}
			//
			return $art_output;
		}
	}
}

// EOF
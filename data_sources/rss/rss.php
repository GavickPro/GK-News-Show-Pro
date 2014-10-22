<?php

/*

Copyright 2013-2013 GavickPro (info@gavick.com)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if ( !defined( 'WPINC' ) ) {
    die;
}

class GK_NSP_Data_Source_rss {
	static function get_results($nsp) {
		extract($nsp->config);

		// results array
		$results = array();
		$amount_of_posts = ($article_pages * $article_cols * $article_rows) + ($links_pages * $links_rows);
		//
		if($data_source_type == 'rss') {
			$upload_dir = wp_upload_dir();
			// get the RSS data
			$rss = fetch_feed($data_source);
			// validate it
			if ( ! is_wp_error( $rss ) ) {
			    $maxitems = $rss->get_item_quantity($amount_of_posts); 
			    $results = $rss->get_items(0, $maxitems);
			}
		}

		return $results;
	}

	static function get_article_format_mapping($item, $config, $generator, $i) {
		// base item data
		$art_URL = $item->get_permalink();
		$art_title = GK_NSP_Widget_Helpers::cut_text('article_title', $item->get_title(), $config['article_title_len_type'], $config['article_title_len']);
		$art_text = GK_NSP_Widget_Helpers::cut_text('article_text', $item->get_content(), $config['article_text_len_type'], $config['article_text_len']);
	 	// parsing shortcodes
	 	if($config['parse_shortcodes'] == 'on') {
 			$art_text = do_shortcode($art_text);
 		} else {
 			$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
 		}
	 	// images
	 	$art_image = '';
	 	// try to receive the image from the feed data
		if ($enclosure = $this->parent->wdgt_results[$i]->get_enclosure()) {
			$art_image = $enclosure->get_thumbnail();

			if($art_image == '') {
				$art_image = $enclosure->get_link();				
			}
		}
		// if the image in the feed data doesn't exist - try to get it from the content text
		if($art_image == '') {
			$art_text = $this->parent->wdgt_results[$i]->get_content();	
			// find the first <img> tag
			if(preg_match('/\<img.*src=.*?\>/', $art_text)){
				$img_start_pos = strpos($art_text, 'src="');
				
				if($img_start_pos) {
					$img_end_pos = strpos($art_text, '"', $img_start_pos + 5);
				}	

				if($img_start_pos > 0) {
					$art_image = substr($art_text, ($img_start_pos + 5), ($img_end_pos - ($img_start_pos + 5)));
				}
			}
		}
		// category
		$art_category = $item->get_category()->get_term();
	 	// date
	 	$art_date = $item->get_date($config['article_info_date_format']);
	 	// put the results to an array:
		return array(
						"{URL}" => $art_URL,
						"{TITLE}" => $art_title,
						"{TITLE_ESC}" => esc_attr($art_title),
						"{TEXT}" => $art_text,
						"{CATEGORY}" => $art_category,
						"{IMAGE}" => $art_image,
						"{IMAGE_MARGIN}" => $config['image_block_padding'],
						"{DATE}" => $art_date,
						"{DATE_W3C}" => $item->get_date('c')
					);
	}
}

// EOF
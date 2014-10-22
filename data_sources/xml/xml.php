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

class GK_NSP_Data_Source_xml {
	static function get_results($nsp) {
		extract($nsp->config);

		// results array
		$results = array();
		//
		if($data_source_type == 'xml-file') {
			$upload_dir = wp_upload_dir();
			// ToDo: Add validation
			$file_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'gk_nsp_external_data' . DIRECTORY_SEPARATOR . $xml_filelist;
			if(is_file($file_path)) {
				$file_content = file_get_contents($file_path);
				//
				$xml = new SimpleXMLElement($file_content);
				//
				if(count($xml->item) > 0) {
					//
					$item = array();
					//
					foreach ($xml->item as $element) {
						//
						foreach($element as $key => $value) {
							$item[$key] = (string) $value;
						}
						//
						array_push($results, $item);
					}
				}
			}
		}
		return $results;
	}

	static function get_article_format_mapping($item, $config, $generator, $i) {
		// base item data
		$art_URL = $item['URL'];
		$art_title = GK_NSP_Widget_Helpers::cut_text('article_title', $item['title'], $config['article_title_len_type'], $config['article_title_len']);
		$art_text = GK_NSP_Widget_Helpers::cut_text('article_text', $item['text'], $config['article_text_len_type'], $config['article_text_len']);
	 	// parsing shortcodes
	 	if($config['parse_shortcodes'] == 'on') {
 			$art_text = do_shortcode($art_text);
 		} else {
 			$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
 		}
	 	// images
	 	$art_image = $item['image'];
		// categories
	 	$art_categories = $item['category'];
	 	// author data
	 	$art_author_name = $item['author_name'];
	 	$art_author_URL = $item['author_URL'];
	 	// date
	 	$art_date = date($config['article_info_date_format'], strtotime($item['date']));
	 	// comments
		$art_comment_count = $item['comment_count'];
	 	$art_comment = $item['comment_link'];
	 	// put the results to an array:
		return array(
						"{URL}" => $art_URL,
						"{TITLE}" => $art_title,
						"{TITLE_ESC}" => esc_attr($art_title),
						"{TEXT}" => $art_text,
						"{IMAGE}" => $art_image,
						"{IMAGE_MARGIN}" => $config['image_block_padding'],
						"{CATEGORIES}" => $art_categories,
						"{AUTHOR_NAME}" => $art_author_name,
						"{AUTHOR_URL}" => $art_author_URL,
						"{DATE}" => $art_date,
						"{RATING}" => $generator->art_rating($item),
						"{DATE_W3C}" => date(DATE_W3C, strtotime($item['date'])),
						"{COMMENT_COUNT}" => $art_comment_count,
						"{COMMENTS}" => $art_comment
					);
	}
}

// EOF
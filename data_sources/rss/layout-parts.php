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

class GK_NSP_Layout_Parts_rss {

	private $parent;

	function __construct($nsp) {
		$this->parent = $nsp;
	}
	/**
	 *
	 * Functions used to generate the article elements
	 *
	 **/
	 
	 function art_title($i, $only_value = false) {
	 	$art_title = $this->parent->wdgt_results[$i]->get_title();
	 	$art_url = $this->parent->wdgt_results[$i]->get_permalink();
	 	
	 	$art_title_short = GK_NSP_Widget_Helpers::cut_text('article_title', $art_title, $this->parent->config['article_title_len_type'], $this->parent->config['article_title_len']);
	 	
	 	if($only_value) {
	 		return apply_filters('gk_nsp_art_raw_title', $art_title_short);
	 	}

	 	$output = '<h3 class="gk-nsp-header"><a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'">'.$art_title_short.'</a></h3>';
	 	
	 	return apply_filters('gk_nsp_art_title', $output);
	 }
	 
	 function art_text($i, $only_value = false) {
	 	$art_text = $this->parent->wdgt_results[$i]->get_content();
	 	
	 	$art_text = GK_NSP_Widget_Helpers::cut_text('article_text', $art_text, $this->parent->config['article_text_len_type'], $this->parent->config['article_text_len']);
	 	// parsing shortcodes
	 	if($this->parent->config['parse_shortcodes'] == 'on') {
 			$art_text = do_shortcode($art_text);
 		} else {
 			$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
 		}
	 	
	 	if($only_value) {
	 		return apply_filters('gk_nsp_art_raw_text', $art_text);
	 	}

	 	$output = '<p class="gk-nsp-text">'.$art_text.'</p>';
	 	
	 	return apply_filters('gk_nsp_art_text', $output);
	 }
	 
	 function art_image($i, $only_value = false, $type = 'article') {
 	 	$art_title = $this->parent->wdgt_results[$i]->get_title();
	 	$art_url = $this->parent->wdgt_results[$i]->get_permalink();
	 	$art_image = '';
	 	// try to receive the image from the feed data
		if ($enclosure = $this->parent->wdgt_results[$i]->get_enclosure()) {
			$art_image = $enclosure->get_thumbnail();

			if($art_image == '') {
				$art_image = $enclosure->get_link();				
			}
		}
		
		// check for the default image
		if($art_image == '' && $this->parent->config[$type . '_default_image'] != '') {
	 		$art_image = $this->parent->config[$type . '_default_image'];
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
		// check if the image exists
	 	if($art_image != '') {
	 		$style = '';
		 	
	 		if($only_value) {
	 			return apply_filters('gk_nsp_art_raw_image', $art_image);
	 		}

		 	if($this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'] != '' && $this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'] != '0') {
	 			$style = ' style="margin: '.$this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'].';"';
	 		}
		 		
		 	// if the popup is enabled
 			$link_additional_classes = '';
 			$link_rel = '';
 			if($this->parent->config[$type . '_image_popup'] == 'on') {
 				$art_url = $art_image;
 				$link_additional_classes = ' thickbox';
 				$link_rel = ' rel="gallery-gk-nsp-' . $this->parent->id . '"';
 			}

 			if(
 				$type == 'links' ||
 				(
 					$this->parent->config['article_image_pos'] == 'left' && 
 					$this->parent->config['article_image_order'] == 1
 				)
 			) {
 				$output = '<div class="gk-nsp-image-wrap"><a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'" class="gk-image-link'.$link_additional_classes.'"'.$style.$link_rel.'><img src="'.$new_path.'" alt="" class="gk-nsp-image" width="'.$this->parent->config[$type . '_image_w'].'" height="'.$this->parent->config[$type . '_image_h'].'" '.(($type == 'links') ? ' style="min-width: '.$this->parent->config[$type . '_image_w'].'px;"' : '').' /></a></div>';

 				return apply_filters('gk_nsp_art_image', $output);
 			} else {
				$output = '<a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'" class="gk-responsive gk-image-link'.$link_additional_classes.'"'.$style.$link_rel.'><img src="'.$art_image.'" alt="" class="gk-nsp-image gk-responsive" width="'.$this->parent->config['article_image_w'].'" height="'.$this->parent->config['article_image_h'].'" /></a>';

 				return apply_filters('gk_nsp_art_image', $output);
 			}
	 	} else {
	 		return '';
	 	} 
	 }
	 
	 function art_info($i) {
	 	$item = $this->parent->wdgt_results[$i];
	 	// replacements for the possible tags
	 	$date = '';
	 	$category = '';
 		// check if there is a category in format
	 	if(stripos($this->parent->config['article_info_format'], '{CATEGORY}') !== FALSE) {
	 		$category = '<span class="gk-nsp-category">' . ($item->get_category()->get_term()) . '</span>';
	 	}
	 	// check if there is a date in format
	 	if(stripos($this->parent->config['article_info_format'], '{DATE}') !== FALSE) {
	 		$date = '<span class="gk-nsp-date">' . $item->get_date($this->parent->config['article_info_date_format']) . '</span>';
	 	}
	 	// replace them all!
	 	$output = str_replace(
	 		array('{DATE}', '{CATEGORY}'),
	 		array($date, $category),
	 		$this->parent->config['article_info_format']
	 	);

	 	return apply_filters('gk_nsp_art_info', '<p class="gk-nsp-info">' . $output . '</p>');
	 }
	 
	 function art_readmore($i, $only_value = false) {
	 	$art_url = $this->parent->wdgt_results[$i]->get_permalink();
	 	
	 	if($only_value) {
	 		return apply_filters('gk_nsp_art_raw_readmore', $art_url);
	 	}

	 	$output = '<a href="'.$art_url.'" class="readon btn" title="'.__('Read more', 'gk-nsp').'">'.__('Read more', 'gk-nsp').'</a>';
	 	
	 	return apply_filters('gk_nsp_art_readmore', $output);
	 }
	 
	 /**
	  *
	  * Functions used to generate the links elements
	  *
	  **/
	  
	function link_title($i) {
		$art_title = $this->parent->wdgt_results[$i]->get_title();
		$art_url = $this->parent->wdgt_results[$i]->get_permalink();
		
		$art_title_short = GK_NSP_Widget_Helpers::cut_text('links_title', $art_title, $this->parent->config['links_title_len_type'], $this->parent->config['links_title_len']);
		
		$output = '<h4 class="gk-nsp-link-header"><a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'">'.$art_title_short.'</a></h4>';
		
		return apply_filters('gk_nsp_link_title', $output);
	}

	function link_text($i) {
		$art_text = $this->parent->wdgt_results[$i]->get_content();
		
		$art_text = GK_NSP_Widget_Helpers::cut_text('links_text', $art_text, $this->parent->config['links_text_len_type'], $this->parent->config['links_text_len']);
		$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
		
		$output = '<p class="gk-nsp-link-text">'.$art_text.'</p>';
		
		return apply_filters('gk_nsp_link_text', $output);
	}


	function link_image($i, $only_value = false) {
		return $this->art_image($i, $only_value, 'links');
	}

	function link_readmore() {
		return '<a class="gk-nsp-links-readon" href="'. $this->parent->config['links_readmore_url'] .'">'. $this->parent->config['links_readmore_text'] .'</a>';
	}
	
	function link_readmorelink($i) {
		$art_url = $this->parent->wdgt_results[$i]->get_permalink();
				
		return '<a class="gk-nsp-link-readmore" href="'. $art_url .'">'. __('Read more', 'gk-nsp') .'</a>';
	}
}

// EOF
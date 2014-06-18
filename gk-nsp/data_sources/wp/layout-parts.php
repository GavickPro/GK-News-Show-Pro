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

class GK_NSP_Layout_Parts_wp {

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
	 	$art_title = '';
	 	$art_ID = '';
	 	$art_url = '';
	 	
	 	$art_title = $this->parent->wdgt_results[$i]->post_title;
	 	$art_ID = $this->parent->wdgt_results[$i]->ID;
	 	
	 	$art_url = get_permalink($art_ID);
	 	$art_title_short = GK_NSP_Widget_Helpers::cut_text('article_title', $art_title, $this->parent->config['article_title_len_type'], $this->parent->config['article_title_len']);

	 	if($only_value) {
	 		return apply_filters('gk_nsp_art_raw_title', $art_title_short);
	 	}
	 	
	 	$output = '<h3 class="gk-nsp-header"><a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'">'.$art_title_short.'</a></h3>';
	 	
	 	return apply_filters('gk_nsp_art_title', $output);
	 }
	 
	 function art_text($i, $only_value = false) {
	 	$art_text = '';

	 	$art_text = $this->parent->wdgt_results[$i]->post_content;
	 	
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
	 	$art_ID = '';

 	 	$art_ID = $this->parent->wdgt_results[$i]->ID;
 	 	$art_title = $this->parent->wdgt_results[$i]->post_title;
	 	$art_url = get_permalink($art_ID);
	 	
	 	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'single-post-thumbnail' );
	 	$image_path = $image[0];
	 	
	 	// check for the default image
	 	if($image_path == '' && $this->parent->config[$type . '_default_image'] != '') {
	 		$image_path = $this->parent->config[$type . '_default_image'];
	 	}

	 	$image_popup_url = $image_path;
	 	$upload_dir = wp_upload_dir();

	 	if(is_multisite() && stripos($upload_dir['baseurl'], '/sites/') !== FALSE) {
	 		$upload_dir_baseurl = substr($upload_dir['baseurl'], 0, stripos($upload_dir['baseurl'], '/sites/'));
	 		$upload_dir_basedir = substr($upload_dir['basedir'], 0, stripos($upload_dir['basedir'], '/sites/'));
	 	} else {
	 		$upload_dir_baseurl = $upload_dir['baseurl'];
	 		$upload_dir_basedir = $upload_dir['basedir'];
	 	}
	 
	 	$image_path = str_replace($upload_dir['baseurl'] . '/', '', $image_path);

	 	$img_override = FALSE;
	 	$img_editor = wp_get_image_editor( 
	 		$upload_dir['basedir'] . '/' . $image_path, 
	 		array( 'methods' => array( 'gk_nsp_sepia', 'gk_nsp_greyscale' ) ) 
	 	);
	 	
	 	if(!is_wp_error($img_editor)) {
	 		$img_override = $img_editor->generate_filename( $this->parent->id . '_' . $type, $upload_dir_basedir . '/' . 'gk_nsp_cache' . '/overrides' );
	 	}

 		if($image_path != '') {		
	 		if(!is_wp_error($img_editor)) {
		 		if($img_override !== FALSE && file_exists($img_override)) {
	 				$new_path = basename($img_override);
	 				$cache_uri = $upload_dir_baseurl . '/gk_nsp_cache/overrides/';
	 				$new_path = $cache_uri . $new_path;
	 			} else {
	 				$img_editor->resize($this->parent->config[$type . '_image_w'], $this->parent->config[$type . '_image_h'], true);
			 		$multisite_suffix = '';
			 		
			 		if(is_multisite()) {
			 			$multisite_suffix = '_blog-' . get_current_blog_id();
			 		}
			 		
			 		$img_filename = $img_editor->generate_filename( $this->parent->id . $multisite_suffix . '_' . $type, $upload_dir_basedir . '/' . 'gk_nsp_cache');
			 		
			 		if($this->parent->config[$type . '_image_filter'] == 'greyscale') {
			 			$img_editor->gk_nsp_greyscale();	
			 		}

			 		if($this->parent->config[$type . '_image_filter'] == 'sepia') {
			 			$img_editor->gk_nsp_sepia();	
			 		}
			 		
			 		$img_editor->save($img_filename);
			 		
			 		$new_path = basename($img_filename);  
			 		$cache_uri = $upload_dir_baseurl . '/gk_nsp_cache/';	

			 		if(is_string($new_path)) {
			 			$new_path = $cache_uri . $new_path;
			 		} else {
	 					return __('An error occured during creating the thumbnail.', 'gk-nsp');
	 				}	
	 			}
	
		 		if($only_value) {
		 			return apply_filters('gk_nsp_art_raw_image', $new_path);
		 		}

	 			$style = '';
	 			
	 			if($this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'] != '' && $this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'] != '0') {
	 				$style = ' style="margin: '.$this->parent->config[($type == 'links' ? 'links_' : '') . 'image_block_padding'].';"';
	 			}

	 			// if the popup is enabled
	 			$link_additional_classes = '';
	 			$link_rel = '';
	 			if($this->parent->config[$type . '_image_popup'] == 'on') {
	 				$art_url = $image_popup_url;
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
					$output = '<a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'" class="gk-responsive gk-image-link'.$link_additional_classes.'"'.$style.$link_rel.'><img src="'.$new_path.'" alt="" class="gk-nsp-image gk-responsive" width="'.$this->parent->config['article_image_w'].'" height="'.$this->parent->config['article_image_h'].'" /></a>';

	 				return apply_filters('gk_nsp_art_image', $output);
	 			}
 			} else {
 				return __('An error occured during creating the thumbnail.', 'gk-nsp');
 			}
	 	} else {
	 		return '';
	 	} 
	 }
	 
	 function art_info($i) {
	 	// replacements for the possible tags
	 	$category = '';
	 	$author = '';
	 	$date = '';
	 	$comments = '';
	 	$price = '';
	 	$stars = '';
	 	//
	 	$art_ID = $this->parent->wdgt_results[$i]->ID;
	 	$comment_count = $this->parent->wdgt_results[$i]->comment_count;
	 	$author_ID = $this->parent->wdgt_results[$i]->post_author;
		// check if there is a category in format
	 	if(stripos($this->parent->config['article_info_format'], '{CATEGORY}') !== FALSE) {
	 		$categories = get_the_category($art_ID);

	 		if(count($categories) > 0) {
	 			foreach($categories as $cat) { 			
	 				$category .= ' <a href="'.get_category_link( $cat->term_id ).'" class="gk-nsp-category">'.$cat->name.'</a> ';
	 			}
	 		}
 		}
	 	// check if there is a author in format
	 	if(stripos($this->parent->config['article_info_format'], '{AUTHOR}') !== FALSE) {	 			 		
	 		$username = get_the_author_meta('display_name', $author_ID);
	 		$author = '<a href="'.get_author_posts_url($author_ID).'" class="gk-nsp-author">'.$username.'</a>';
	 	}
	 	// check if there is a date in format
	 	if(stripos($this->parent->config['article_info_format'], '{DATE}') !== FALSE) {
	 		$date = '<span class="gk-nsp-date">' . get_the_time($this->parent->config['article_info_date_format'], $art_ID) . '</span>';
	 	}
	 	// check if there are the stars in format
	 	if(stripos($this->parent->config['article_info_format'], '{STARS}') !== FALSE) {
	 		$stars = $this->art_rating($art_ID);
	 	}
	 	// check if there is a comments in format
	 	if(stripos($this->parent->config['article_info_format'], '{COMMENTS}') !== FALSE) {
	 		$comment_phrase = '';

	 		if($comment_count == 0) {
	 			$comment_phrase = __('No comments', 'gk-nsp');
	 		} else if($comment_count >= 1) {
	 			$comment_phrase = __('Comments ', 'gk-nsp') . '(' . $comment_count . ')';
	 		}

	 		$comments = '<a href="'.get_permalink($art_ID).'#comments">'.$comment_phrase.'</a>';
	 	}
	 	// check if there is a comments_count in format
	 	if(stripos($this->parent->config['article_info_format'], '{COMMENT_COUNT}') !== FALSE) {
	 		$comment_count = '<a href="'.get_permalink($art_ID).'#comments" class="gk-nsp-comment-count">' .$comment_count. '</a>';
	 	}
	 	// replace them all!
	 	$output = str_replace(
	 		array('{CATEGORY}', '{AUTHOR}', '{DATE}', '{COMMENTS}', '{COMMENT_COUNT}', '{STARS}'),
	 		array($category, $author, $date, $comments, $comment_count, $stars),
	 		$this->parent->config['article_info_format']
	 	);

	 	return apply_filters('gk_nsp_art_info', '<p class="gk-nsp-info">' . $output . '</p>');
	 }
	 
	 function art_rating($art_ID) {
	 	$stars = '';
	 	$rating = get_post_custom_values('gk-nsp-rate', $art_ID);
 		if(isset($rating[0]) && trim($rating[0]) != '') {
 			$rating = explode('/', $rating[0]);
 			if(count($rating) == 2) {
 				if(
 					is_numeric(trim($rating[0])) && 
 					is_numeric(trim($rating[1])) && 
 					trim($rating[0]) * 1 <= trim($rating[1]) * 1 &&
 					trim($rating[0]) * 1 >= 0 && trim($rating[1]) > 0
 				) {
 					$rate = trim($rating[0]) * 1;
 					$total = trim($rating[1]) * 1;

 					$stars = '<span class="gk-nsp-stars">';
 					for($i = 0; $i < $total; $i++) {
						$stars .= $i < $rate ? '<span class="gk-nsp-star-1"></span>' : '<span class="gk-nsp-star-0"></span>';
					}
					$stars .= '</span>';
 				} else {
 					$stars = 'Wrong rating data received';
 				}
 			} else {
 				$stars = 'Wrong rating data received';
 			}
 		} else {
 			$stars = 'Not rated yet';
 		}

 		return $stars;
	 }

	 function art_readmore($i, $only_value = false) {
	 	$art_ID = '';
	 	$art_url = '';

	 	$art_ID = $this->parent->wdgt_results[$i]->ID;
	 	
	 	$art_url = get_permalink($art_ID);
	 	
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
		$art_title = '';
		$art_ID = '';
		$art_url = '';
		
	  	$art_title = $this->parent->wdgt_results[$i]->post_title;
	  	$art_ID = $this->parent->wdgt_results[$i]->ID;
		
		$art_url = get_permalink($art_ID);
		$art_title_short = GK_NSP_Widget_Helpers::cut_text('links_title', $art_title, $this->parent->config['links_title_len_type'], $this->parent->config['links_title_len']);
		
		$output = '<h4 class="gk-nsp-link-header"><a href="'.$art_url.'" title="'.esc_attr(strip_tags($art_title)).'">'.$art_title_short.'</a></h4>';
		
		return apply_filters('gk_nsp_link_title', $output);
	}

	function link_text($i) {
		$art_text = '';
		
	  	$art_text = $this->parent->wdgt_results[$i]->post_content;
		
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
		$art_ID = $this->parent->wdgt_results[$i]->ID;
		$art_url = get_permalink($art_ID);
		
		return '<a class="gk-nsp-link-readmore" href="'. $art_url .'">'. __('Read more', 'gk-nsp') .'</a>';
	}
}

// EOF
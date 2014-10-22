<?php

if(!class_exists('GK_NSP_Article_Wrapper_videogallery')) {
	class GK_NSP_Article_Wrapper_videogallery {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['videogallery_pages'] * $config['videogallery_cols'];
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_url($i, $generator) {
			$art_url = $generator->art_readmore($i, true);
			return $art_url;
		}
		
		static function article_header($i, $generator) {
			$art_title  = $generator->art_title($i, true); 
			$art_url    = $generator->art_readmore($i, true);
			$art_header = '<h3><a href="'.$art_url.'" title="'.$art_title.'">'.$art_title.'</a></h3>';
			
			return $art_header;
		}
		
		static function art_img($i, $generator) {
			$art_image = '';
			$art_image  = $generator->art_image($i, true);
			return $art_image;
		}
		
		static function get_video($i, $generator, $results) {
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
		
		static function get_image($i, $generator) {
			$art_image  = $generator->art_image($i, true);
			return $art_image;
		}
		
		static function get_text($i, $generator) {
			$art_text  = $generator->art_text($i, true);
			return $art_text;
		}
		
		static function get_category($i, $results) {
			$art_ID = $results[$i]->ID;
			$categories = get_the_category($art_ID);
			$category = '';

			if(count($categories) > 0) {
				foreach($categories as $cat) { 			
					$category = $cat->name;
				}
			}
			return $category;
 		}
 		
 		static function get_comments($i, $results) {
			$comments_count = $results[$i]->comment_count;
			return $comments_count;
 		}
 		
 		// function to generate blank transparent PNG images
       	static function generateBlankImage($width, $height){ 
            $image = imagecreatetruecolor($width, $height);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparent);
            // cache the output
            ob_start();
            imagepng($image);
            $img =  ob_get_contents();
            ob_end_clean();
            // return the string
            return base64_encode($img);
        }
	}
}

// EOF
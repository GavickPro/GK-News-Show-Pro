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

class GK_NSP_Data_Source_wp {
        static function get_results($nsp, $amount_of_posts) {
                extract($nsp->config);

                // resutls array
                $results = array();

                if($data_source_type == 'wp-latest') {
                        $results = get_posts(array(
                                'posts_per_page' => $amount_of_posts,
                                'offset' => $offset, 
                                'orderby' => $orderby,
                                'order' => $order
                        ));
                } else if($data_source_type == 'wp-sticky') {
                        if(count(get_option('sticky_posts'))) {
                                $results = get_posts(array(
                                        'posts_per_page' => $amount_of_posts,
                                        'offset' => $offset, 
                                        'orderby' => $orderby,
                                        'order' => $order,
                                        'post__in' => get_option('sticky_posts')
                                ));
                        }
                } else if($data_source_type == 'wp-category') {
                        $wp_cats = is_array($wp_category_list) ? $wp_category_list : explode(',', $wp_category_list);
                        
                        if($one_per_category == 'on') {
                                $post_ids = array(0);
                                
                                foreach($wp_cats as $cat_id ) {
                                    if ( $posts = get_posts(array('category' => $cat_id, 'showposts' => 1)) ) {
                                        $first = array_shift($posts);
                                        $post_ids[] = $first->ID;
                                    }
                                }

                                $results = get_posts(array('post__in' => $post_ids));
                        } else {                               
                                $results = get_posts(array(
                                        'category__in' => $wp_cats,
                                        'posts_per_page' => $amount_of_posts,
                                        'offset' => $offset, 
                                        'orderby' => $orderby,
                                        'order' => $order
                                ));
                        }
                } else if($data_source_type == 'wp-tag') {                        
                        $results = get_posts(array(
                                'tag' => $data_source,
                                'posts_per_page' => $amount_of_posts,
                                'offset' => $offset, 
                                'orderby' => $orderby,
                                'order' => $order
                        ));
                } else if($data_source_type == 'wp-post') {
                        $post_slugs = explode(',', $data_source);
                        foreach($post_slugs as $slug) {
                                $res = get_posts(array('name' => $slug));
                                if(is_array($res) && count($res)) {
                                    array_push($results, $res[0]);
                                }
                        }
                } else if($data_source_type == 'wp-custom') {
                        $results = get_posts(array(
                        	'post_type' => $post_types_list,
                        	'numberposts' => $amount_of_posts
                        ));
                }

                return $results;
        }

        static function get_article_format_mapping($item, $config, $generator, $i) {
                // base item data
                $art_ID = $item->ID;
                $art_URL = get_permalink($art_ID);
                $art_title = GK_NSP_Widget_Helpers::cut_text('article_title', $item->post_title, $config['article_title_len_type'], $config['article_title_len']);
                $art_text = GK_NSP_Widget_Helpers::cut_text('article_text', $item->post_content, $config['article_text_len_type'], $config['article_text_len']);
                // parsing shortcodes
                if($config['parse_shortcodes'] == 'on') {
                    $art_text = do_shortcode($art_text);
                } else {
                    $art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
                }
                // images
                $art_image_full = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'full' );
                $art_image_full = $art_image_full[0];
                $art_image_large = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'large' );
                $art_image_large = $art_image_large[0];
                $art_image_medium = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'medium' );
                $art_image_medium = $art_image_medium[0];
                $art_image_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'thumbnail' );
                $art_image_thumbnail = $art_image_thumbnail[0];
                // categories
                 $art_categories = '';
                 $categories = get_the_category($art_ID);

                 if(count($categories) > 0) {
                         foreach($categories as $cat) {                         
                                 $art_categories .= ' <a href="'.get_category_link( $cat->term_id ).'" class="gk-nsp-category">'.$cat->name.'</a> ';
                         }
                 }
                 // author data
                 $art_author_ID = $item->post_author;
                 $art_author_name = get_the_author_meta('display_name', $art_author_ID);
                 $art_author_URL = get_author_posts_url($art_author_ID);
                 // date
                 $art_date = get_the_time($config['article_info_date_format'], $art_ID);
                 // comments
                $comment_phrase = '';
                $art_comment_count = $item->comment_count;

                 if($art_comment_count == 0) {
                         $comment_phrase = __('No comments', 'gk-nsp');
                 } else if($art_comment_count >= 1) {
                         $comment_phrase = __('Comments ', 'gk-nsp') . '(' . $art_comment_count . ')';
                 }

                 $art_comment = '<a href="'.get_permalink($art_ID).'#comments">'.$comment_phrase.'</a>';
                 // put the results to an array:
                return array(
                                                "{ID}" => $art_ID,
                                                "{URL}" => $art_URL,
                                                "{TITLE}" => $art_title,
                                                "{TITLE_ESC}" => esc_attr($art_title),
                                                "{TEXT}" => $art_text,
                                                "{IMAGE}" => $generator->art_image($i, true),
                                                "{IMAGE_MARGIN}" => $config['image_block_padding'],
                                                "{IMAGE_FULL}" => $art_image_full,
                                                "{IMAGE_LARGE}" => $art_image_large,
                                                "{IMAGE_MEDIUM}" => $art_image_medium,
                                                "{IMAGE_THUMBNAIL}" => $art_image_thumbnail,
                                                "{CATEGORIES}" => $art_categories,
                                                "{AUTHOR_ID}" => $art_author_ID,
                                                "{AUTHOR_NAME}" => $art_author_name,
                                                "{AUTHOR_URL}" => $art_author_URL,
                                                "{DATE}" => $art_date,
                                                "{RATING}" => $generator->art_rating($art_ID),
                                                "{DATE_W3C}" => get_the_time('c', $art_ID),
                                                "{COMMENT_COUNT}" => $art_comment_count,
                                                "{COMMENTS}" => $art_comment,
                                                "{STARS}" => $generator->art_rating($art_ID)
                                        );
        }
}

// EOF
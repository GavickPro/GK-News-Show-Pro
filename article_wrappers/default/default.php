<?php

$base_path = $this->aw_path . GK_DS . 'default';

// get the generator class name
$generator_prefix = explode('-', $data_source_type);
$generator_class = 'GK_NSP_Layout_Parts_' . $generator_prefix[0];
require_once($this->ds_path . GK_DS . $generator_prefix[0] . GK_DS . 'layout-parts.php');
// article fragments generator
$this->generator = new $generator_class($this);
// generate the articles
$num_of_arts = $article_pages * $article_cols * $article_rows;
$num_of_arts = $num_of_arts > count($results) ? count($results) : $num_of_arts; 
$num_of_art_pages = $article_pages;

if($num_of_arts >= count($results)) {
	$num_of_art_pages = ceil(count($results) / ($article_cols * $article_rows));
}

// generate the widget wrapper
do_action('gk_nsp_before_wrapper');
echo '<div class="'.$this->wdgt_class.'" data-cols="'.$article_cols.'" data-rows="'.$article_rows.'" data-links="'.$links_rows.'" data-autoanim="'.$autoanim.'" data-autoanimint="'.$autoanim_interval.'" data-autoanimhover="'.$autoanim_hover.'">';
// wrap articles
echo '<div class="gk-nsp-arts">';
echo '<div class="gk-nsp-arts-scroll gk-nsp-pages-'.$num_of_art_pages.'">';
//
$i = 0;
//
for($p = 0; $p < $num_of_art_pages; $p++) {
	echo '<div class="gk-nsp-arts-page gk-nsp-cols-'.$num_of_art_pages.'">';

	for($r = 0; $r < $article_cols * $article_rows; $r++) {
		if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
			$art_output = GK_NSP_Article_Wrapper_default::article_output($i, $this->generator, $this->config, $results, $data_source_class, $this->af_path);
			// the final output
			$style = '';
			
			if($article_block_padding != '' && $article_block_padding != '0') {
				$style = ' style="padding: '.$article_block_padding.';"';
			}
			
			do_action('gk_nsp_before_article');
			echo '<div class="gk-nsp-art gk-nsp-cols-'.$article_cols.'" '.$style.'>' . $art_output . '</div>';
			do_action('gk_nsp_after_article');
		}
		//
		$i++;
	}
	echo '</div>';
}
//
echo '</div>';
//
if($num_of_art_pages > 1) {
	do_action('gk_nsp_before_articles_nav');
	echo '<div class="gk-nsp-arts-nav">';
	
	if($article_pagination != 'arrows') {
		echo '<ul class="gk-nsp-pagination">';
		
		for($i = 1; $i <= $num_of_art_pages; $i++) {
			echo '<li>' . $i . '</li>';
		}
		
		echo '</ul>';
	}
	
	if($article_pagination != 'pagination') {
		echo '<div class="gk-nsp-prev">'.apply_filters('gk_nsp_arts_nav_prev_text', '&laquo;').'</div>';
		echo '<div class="gk-nsp-next">'.apply_filters('gk_nsp_arts_nav_next_text', '&raquo;').'</div>';
	}
	
	echo '</div>';
	do_action('gk_nsp_after_articles_nav');
}
//
echo '</div>';
// generate the links
if($num_of_arts <= count($results)) {
	// calculate amount of links
	$amount_of_links = count($results) - $num_of_arts;
	$start = $num_of_arts;
	// generate the links
	if($amount_of_links > 0) {
		echo '<div class="gk-nsp-links">';
		echo '<div class="gk-nsp-links-scroll gk-nsp-pages-'.ceil($amount_of_links / $links_rows).'">';
		
		for($i = 0; $i < ceil($amount_of_links / $links_rows); $i++) {
			echo '<ul class="gk-nsp-list gk-nsp-cols-'.$links_pages.'">';
			for($j = 0; $j < $links_rows; $j++) {
				if($start < count($results)) {
					do_action('gk_nsp_before_link');
					echo '<li>';
					if($links_image_state == 'on') echo $this->generator->link_image($start);
					if($links_image_state == 'on') echo '<div class="gk-nsp-link-content-wrap">';					
					if($links_title_state == 'on') echo $this->generator->link_title($start);
					if($links_text_state  == 'on') echo $this->generator->link_text($start);
					if($links_readmorelink_state  == 'on') echo $this->generator->link_readmorelink($start);
					if($links_image_state == 'on') echo '</div>';	
					echo '</li>';
					do_action('gk_nsp_after_link');
				}
				
				$start++;
			}
			echo '</ul>';
		}
		//
		echo '</div>';
		//
		if($links_readmore_state == 'on') {
			echo $this->generator->link_readmore();
		}
		//
		echo '</div>';
	}
}
//
if($links_rows > 0 && ceil($amount_of_links / $links_rows) > 1) {
	do_action('gk_nsp_before_links_nav');
	echo '<div class="gk-nsp-links-nav">';
	
	if($links_pagination != 'arrows') {
		echo '<ul class="gk-nsp-pagination">';
		
		for($i = 1; $i <= ceil($amount_of_links / $links_rows); $i++) {
			echo '<li>' . $i . '</li>';
		}
		
		echo '</ul>';
	}
	
	if($links_pagination != 'pagination') {
		echo '<div class="gk-nsp-prev">'.apply_filters('gk_nsp_links_nav_prev_text', '&laquo;').'</div>';
		echo '<div class="gk-nsp-next">'.apply_filters('gk_nsp_links_nav_next_text', '&raquo;').'</div>';
	}
	
	echo '</div>';
	do_action('gk_nsp_after_links_nav');
}
// closing the widget wrapper
echo '</div>';
do_action('gk_nsp_after_wrapper');

// EOF
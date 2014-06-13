<?php

if(!class_exists('GK_NSP_Article_Wrapper_events')) {
	class GK_NSP_Article_Wrapper_events {
		/*
	     * Method used to generate the number of necessary articles
		 */
		static function number_of_articles($config) {
			// total amount of the posts
			return $config['events_cols'] * $config['events_rows'];
		}
		
		/*
		 * Method used to generate the date of event
		 */
		static function event_date($i, $generator, $results) {
			$date_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_date_start', true ));
			//
			return '<span>' . __( 'When: ','gk-nsp' ) .''. $date_start->format('d F Y') . ' @ ' .$date_start->format('g:i a'). '<span class="gk-events-list-progress"></span></span>';
			
		}
		
		/*
		 * Method used to generate the date event block
		 */
		static function event_dateblock($i, $generator, $results) {
			$date_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_date_start', true ));
			$event_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_counter_start', true ));
			//
			return '<time datetime="' . $date_start->format('c'). '" data-start="'. $event_start->format('c') .'">' . $date_start->format('D') . '<small>' . $date_start->format('M j') . '</small></time>';
		}

		/*
	     * Method used to generate the article output
		 */
		static function article_output($i, $generator, $config) {
			//
			$art_output = '';
			$art_title  = $generator->art_title($i, true);
			$art_url    = $generator->art_readmore($i, true);			
			$art_output .= '<h3><a href="' .$art_url. '" title="'.$art_title.'"> ' . $art_title .'</a></h3>';
			//
			return $art_output;
		}
	}
}

// EOF
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
			global $wp_locale;
			$date_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_date_start', true ));

			$datemonth = $wp_locale->get_month($date_start->format('m') );
			//
			return '<span>' . __( 'When: ','gk-nsp' ) .''. $date_start->format('j ') . $datemonth . $date_start->format(' Y '). ' @ ' .$date_start->format('g:i a'). '<span class="gk-events-list-progress"></span></span>';
			
		}
		
		/*
		 * Method used to generate the date event block
		 */
		static function event_dateblock($i, $generator, $results) {
			global $wp_locale;
			$date_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_date_start', true ));
			$event_start = new DateTime(get_post_meta( $results[$i]->ID, 'gkevent_counter_start', true ));
			
			$dateweekday = $wp_locale->get_weekday( $date_start->format('w') );
			$dateweekday_abbrev = $wp_locale->get_weekday_abbrev( $dateweekday );

			$datemonth = $wp_locale->get_month($date_start->format('m') );
			$datemonth_abbrev = $wp_locale->get_month_abbrev( $datemonth );
			//
			return '<time datetime="' . $date_start->format('c'). '" data-start="'. $event_start->format('c') .'">' . $dateweekday_abbrev . '<small>' . $datemonth_abbrev .' '. $date_start->format('j') . '</small></time>';
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
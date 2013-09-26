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

class GK_NSP_Widget_Helpers {
     /**
	  *
	  * Helper functions
	  *
	  **/
	 
	 static function cut_text($type, $text, $len_type, $len, $at_end = '&hellip;') {
	 	$text = strip_tags($text);
	 	$cutter = array();
	 	
	 	if($len_type == 'words' && $len > 0){
	 		$temp = explode(' ',$text);
	 		
	 		if(count($temp) > $len){
	 			for($i=0; $i<$len; $i++) $cutted[$i] = $temp[$i];
	 			$cutted = implode(' ', $cutted);
	 			$text = $cutted.$at_end;
	 		}
	 	} elseif($len_type == 'words' && $len == 0) {
	 		return '';
	 	} else {
	 		if(function_exists('mb_strlen')) {
	 			if(mb_strlen($text) > $len){
	 				$text = mb_substr($text, 0, $len) . $at_end;
	 			}
	 		} else {
	 			if(strlen($text) > $len){
	 				$text = substr($text, 0, $len) . $at_end;
	 			}
	 		}
	 	}
	 	// replace unnecessary entities at end of the cutted text
	 	$toReplace = array('&&', '&a&', '&am&', '&amp&', '&q&', '&qu&', '&quo&', '&quot&', '&ap&', '&apo&', '&apos&');
	 	$text = str_replace($toReplace, '&', $text);
	 	//
	 	return $text;
	 }
}
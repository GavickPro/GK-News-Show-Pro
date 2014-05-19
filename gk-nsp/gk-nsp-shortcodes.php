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

// Basic shortcode
function GK_NewsShowPro_Shortcode($params = array()) {
	ob_start();
	// create widget
	$instance = new GK_NewsShowPro_Widget();
	// basic widget params
	$args = array(
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	);
	// return the widget instance
	$instance->widget($args, $params);
	$content = ob_get_clean();

	return $content;
}

add_shortcode('gknsp', 'GK_NewsShowPro_Shortcode');

// EOF
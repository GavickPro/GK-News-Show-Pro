<?php

/*
Image filters for the WordPress Image Editor class
*/

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

// loading necessary Image Editor classes
require_once(ABSPATH . WPINC . '/class-wp-image-editor.php');
require_once(ABSPATH . WPINC . '/class-wp-image-editor-imagick.php');
require_once(ABSPATH . WPINC . '/class-wp-image-editor-gd.php');

/*  
Code responsible for adding extended versions of the Imagick and GD Image Editors
*/
function gk_nsp_add_image_filters_editors( $editors ) {
  array_unshift( $editors, 'GK_NSP_Imagick_Filters_Editor' );
  array_unshift( $editors, 'GK_NSP_GD_Filters_Editor' );

  return $editors;
}

add_filter( 'wp_image_editors', 'gk_nsp_add_image_filters_editors' );

/*
Imagick Extended Image Editor with sepia nad greyscale methods
*/
class GK_NSP_Imagick_Filters_Editor extends WP_Image_Editor_Imagick {
	public function gk_nsp_sepia($arg = 100) {
    	$this->image->sepiaToneImage($arg);
    	return true;
	}

	public function gk_nsp_greyscale() {
    	$this->image->modulateImage(100,0,100);
    	return true;
	}
}

/*
GD Extended Image Editor with sepia nad greyscale methods
*/
class GK_NSP_GD_Filters_Editor extends WP_Image_Editor_GD {
	public function gk_nsp_sepia() {
    	imagefilter($this->image, IMG_FILTER_GRAYSCALE);
    	imagefilter($this->image, IMG_FILTER_COLORIZE, 90, 60, 40); 
    	return true;
	}

	public function gk_nsp_greyscale() {
    	imagefilter($this->image, IMG_FILTER_GRAYSCALE);
    	return true;
	}
}

// EOF
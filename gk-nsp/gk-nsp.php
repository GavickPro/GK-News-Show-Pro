<?php

/*
Plugin Name: GK News Show Pro
Plugin URI: http://www.gavick.com/
Description: Advanced widget for the posts display
Version: beta
Author: GavickPro
Author URI: http://www.gavick.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
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

if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !defined( 'GK_DS' ) ) {
	define('GK_DS', DIRECTORY_SEPARATOR);
}

include(dirname(__FILE__) . '/gk-nsp-form.php');
include(dirname(__FILE__) . '/gk-nsp-form-walkers.php');
include(dirname(__FILE__) . '/gk-nsp-helpers.php');
include(dirname(__FILE__) . '/gk-nsp-image-filters.php');

/**
 * i18n
 */
load_plugin_textdomain( 'gk-nsp', false, dirname( dirname( plugin_basename( __FILE__) ) ).'/languages' );


/**
 * Register the GK News Show Pro Widget.
 *
 * Hooks into the widgets_init action.
 */
function gk_nsp_register() {
	register_widget( 'GK_NewsShowPro_Widget' );
}

add_action( 'widgets_init', 'gk_nsp_register' );

/**
 * install & uninstall
 */
register_activation_hook( __FILE__, array( 'GK_NewsShowPro_Widget', 'install' ) );
register_deactivation_hook( __FILE__, array( 'GK_NewsShowPro_Widget', 'uninstall' ) );

//
add_action('edit_post', array('GK_NewsShowPro_Widget', 'refresh_cache'));
add_action('post_updated', array('GK_NewsShowPro_Widget', 'refresh_slugs'), 10, 3);
add_action('delete_post', array('GK_NewsShowPro_Widget', 'refresh_cache'));
add_action('trashed_post', array('GK_NewsShowPro_Widget', 'refresh_cache'));
add_action('save_post', array('GK_NewsShowPro_Widget', 'refresh_cache'));

/**
 * The main widget class
 */
class GK_NewsShowPro_Widget extends WP_Widget {
	// variable used to store the object configuration
	public $config = array(
		'title' => '',
		'widget_css_suffix' => '',
		'use_css' => 'on',
		// data source
		'data_source_type' => 'latest',
		'data_source' => '',
		'wp_category_list' => array(),
		'woocommerce_category_list' => array(),
		'post_types_list' => array(),
		'orderby' => 'ID',
		'one_per_category' => 'off',
		'order' => 'DESC',
		'offset' => '0',
		'data_source_blog' => '',
		'article_wrapper' => 'default',
		// articles amount
		'article_pages' => '1',
		'article_cols' => '1',
		'article_rows' => '1',
		// links amount
		'links_pages' => '0',
		'links_rows' => '0',
		// paginations
		'article_pagination' => 'pagination',
		'links_pagination' => 'pagination',
		// article format
		'article_format' => 'none',
		// article title format
		'article_title_state' => 'on',
		'article_title_len' => '10',
		'article_title_len_type' => 'words',
		'article_title_order' => '1',
		// article text format
		'article_text_state' => 'on',
		'article_text_len' => '20',
		'article_text_len_type' => 'words',
		'article_text_order' => '2',
		// article text format
		'article_image_state' => 'on',
		'article_image_w' => '160',
		'article_image_h' => '120',
		'article_image_pos' => 'top',
		'article_image_order' => '3',
		'article_image_popup' => 'on', 
		'article_image_filter' => 'none',
		// article info format
		'article_info_state' => 'on',
		'article_info_format' => '{DATE} {CATEGORY} {AUTHOR} {COMMENTS}',
		'article_info_date_format' => 'd M Y',
		'article_info_order' => '4',
		// article readmore format
		'article_readmore_state' => 'on',
		'article_readmore_order'=> '5',
		// links title format
		'links_title_state' => 'on',
		'links_title_len' => '10',
		'links_title_len_type' => 'words',
		// links text format
		'links_text_state' => 'on',
		'links_text_len' => '20',
		'links_text_len_type' => 'words',
		// paddings
		'article_block_padding' => '20px 0',
		'image_block_padding' => '0',
		// cache time
		'cache_time' => '60',
		// Autoanimation
		'autoanim' => 'off',
		'autoanim_interval' => '5000',
		'autoanim_hover' => 'on'
	);
	// variable used to store the object query results
	public $wdgt_results;
	// variable used to store the widget CSS class
	public $wdgt_class;
	// variable used for the internal classes
	private $generator;
	// variables for storing pathes
	public $ds_path;
	public $aw_path;
	public $af_path;

	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function __construct() {
		// paths
		$this->ds_path = dirname(__FILE__) . GK_DS . 'data_sources';
		$this->aw_path = dirname(__FILE__) . GK_DS . 'article_wrappers';
		$this->af_path = dirname(__FILE__) . GK_DS . 'article_formats';
		//
		$this->WP_Widget(
			'gk_nsp', 
			__('News Show Pro by GavickPro', 'gk-nsp'), 
			array( 
				'classname' => 'gk_nsp', 
				'description' => __( 'Use this widget to show recent items with images and additional elements like title, date etc.', 'gk-nsp') 
			),
			array(
				'width' => 400, 
				'height' => 240
			)
		);
		//
		$this->alt_option_name = 'gk_nsp';
		//
		add_action('wp_enqueue_scripts', array($this, 'add_js'));
		add_action('wp_enqueue_scripts', array($this, 'add_css'));
		add_action('admin_enqueue_scripts', array($this, 'add_admin_js'));
		add_action('admin_enqueue_scripts', array($this, 'add_admin_css'));
	}

	/**
	 *
	 *
	 *
	 */
	static function install() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$upload_dir =  wp_upload_dir();

		$files = array(
			array(
				'base' 		=> $upload_dir['basedir'] . '/gk_nsp_cache',
				'file' 		=> 'index.html',
				'content' 	=> ''
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/gk_nsp_cache/overrides',
				'file' 		=> 'index.html',
				'content' 	=> ''
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/gk_nsp_external_data',
				'file' 		=> 'index.html',
				'content' 	=> ''
			)
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}

	/**
	 *
	 *
	 *
	 */
	static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$upload_dir =  wp_upload_dir();
		// remove the dir for the image cache
		if(is_dir($upload_dir['basedir'] . '/gk_nsp_cache')) {
			// error flag for the files remove
			$error_flag = false;
			// generate the list of files to remove
			$files = scandir($upload_dir['basedir'] . '/gk_nsp_cache');
			// remove all files in the directory
			foreach($files as $file) {
				if($file != '.' && $file != '..') {
					if(!unlink($upload_dir['basedir'] . GK_DS . 'gk_nsp_cache' . GK_DS . $file)) {
						$error_flag = true;
					}
				}
			}
			// remove the directory if all files was removed
			if(!$error_flag) {
				rmdir($upload_dir['basedir'] . '/gk_nsp_cache');	
			}
		}
		// remove the dir for the external data
		if(is_dir($upload_dir['basedir'] . '/gk_nsp_external_data')) {
			// error flag for the files remove
			$error_flag = false;
			// generate the list of files to remove
			$files = scandir($upload_dir['basedir'] . '/gk_nsp_external_data');
			// remove all files in the directory
			foreach($files as $file) {
				if($file != '.' && $file != '..') {
					if(!unlink($upload_dir['basedir'] . GK_DS . 'gk_nsp_external_data' . GK_DS . $file)) {
						$error_flag = true;
					}
				}
			}
			// remove the directory if all files was removed
			if(!$error_flag) {
				rmdir($upload_dir['basedir'] . '/gk_nsp_external_data');	
			}
		}
	}

	function add_js() {
		// read the widget settings
		$json_cache = get_option('widget_gk_nsp_json_cache');
		$instances = get_option('widget_gk_nsp');
		$loaded_files = array();
		$thickbox_loaded = false;
		// check if the instances are correct
		if(is_array($instances) || is_object($instances)) {
			// iterate through instances
			foreach($instances as $instance) {
				// check if the wrapper exist in the specific instance and isn't duplicated
				if($instance['article_wrapper'] != '' && !in_array($instance['article_wrapper'], $loaded_files)) {
					// check the type of wrapper
					if($instance['article_wrapper'] == 'default') {
						wp_register_script( 'gk-nsp-default', plugins_url('gk-nsp.js', __FILE__), array('jquery'), false, true);
						wp_enqueue_script('gk-nsp-default');
					} else {
						if(isset($json_cache[$instance['article_wrapper']]) && $json_cache[$instance['article_wrapper']]) {
							wp_register_script( 'gk-nsp-' . $instance['article_wrapper'], plugins_url('article_wrappers/'.$instance['article_wrapper'].'/'. $instance['article_wrapper'] .'.js', __FILE__), array('jquery'), false, true);
							wp_enqueue_script('gk-nsp-' . $instance['article_wrapper']);
						}
					}
					// push the wrapper to teh list - avoid duplicates
					array_push($loaded_files, $instance['article_wrapper']);
				}
				// load Thickbox script if popup is used
				if(!$thickbox_loaded && $instance['article_image_popup'] === 'on') {
					wp_enqueue_script('thickbox');
					$thickbox_loaded = true;
				}
			}
		}
	}

	function add_css() {
		// read the widget settings
		$json_cache = get_option('widget_gk_nsp_json_cache');
		$instances = get_option('widget_gk_nsp');
		$loaded_files = array();
		$thickbox_loaded = false;
		// check if the instances are correct
		if(is_array($instances) || is_object($instances)) {
			// iterate through instances
			foreach($instances as $instance) {
				// check if the wrapper exist in the specific instance and isn't duplicated
				if(	
					$instance['use_css'] == 'on' &&
					$instance['article_wrapper'] != '' && 
					!in_array($instance['article_wrapper'], $loaded_files)
				) {
					// check the type of wrapper
					if($instance['article_wrapper'] == 'default') {
						wp_register_style( 'gk-nsp', plugins_url('gk-nsp.css', __FILE__), array(), false, 'all');
						wp_enqueue_style('gk-nsp');
					} else {
						if(isset($json_cache[$instance['article_wrapper']]) && $json_cache[$instance['article_wrapper']]) {
							wp_register_style( 'gk-nsp-' . $instance['article_wrapper'], plugins_url('article_wrappers/'.$instance['article_wrapper'].'/'. $instance['article_wrapper'] .'.css', __FILE__), array(), false, 'all');
							wp_enqueue_style('gk-nsp-' . $instance['article_wrapper']);
						}
					}
					// push the wrapper to teh list - avoid duplicates
					array_push($loaded_files, $instance['article_wrapper']);
				}
				// load Thickbox stylesheet if popup is used
				if(!$thickbox_loaded && $instance['article_image_popup'] === 'on') {
					wp_enqueue_style('thickbox');
					$thickbox_loaded = true;
				}
			}
		}
	}

	function add_admin_js() {
		wp_register_script( 'gk-nsp', home_url() . '/wp-content/plugins/gk-nsp/gk-nsp-admin.js', array('jquery'), false, 'all');
		wp_enqueue_script('gk-nsp');
	}

	function add_admin_css() {
		wp_register_style( 'gk-nsp', home_url() . '/wp-content/plugins/gk-nsp/gk-nsp-admin.css', array(), false, 'all');
		wp_enqueue_style('gk-nsp');
	}

	/**
	 *
	 * Outputs the HTML code of this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void
	 *
	 **/
	function widget($args, $instance) {
		$cache = get_transient('widget_' . $this->id);
		// the part with the title and widget wrappers cannot be cached! 
		// in order to avoid problems with the calculating columns
		//
		extract($args, EXTR_SKIP);
		//
		foreach($instance as $key => $value) {
			if($key == 'title') {
				$this->config['title'] = apply_filters('widget_title', !isset($instance['title']) ? $this->config['title'] : $instance['title'], $instance, $this->id_base);
			} else {
				$this->config[$key] = !isset($instance[$key]) ? $this->config[$key] : $instance[$key];
			}
		}
		
		extract($this->config);

		echo $before_widget;
		
		if($title != '') {
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		
		if($cache && $cache_time > 0) {
			echo $cache;
			echo $after_widget;
			return;
		}
		// start cache buffering
		ob_start();
		// get the posts data
		// let's save the global $post variable
		global $post;
		$tmp_post = $post;
		
		// change the source blog
		if(is_multisite() && $data_source_blog != '' && is_numeric($data_source_blog)) {
			switch_to_blog($data_source_blog);
		}

		// generate the data source class name
		$data_source_prefix = explode('-', $data_source_type);
		require_once($this->ds_path . GK_DS . $data_source_prefix[0] . GK_DS . $data_source_prefix[0] . '.php');
		require_once($this->aw_path . GK_DS . $article_wrapper . GK_DS . 'helper.php');
		$data_source_class = 'GK_NSP_Data_Source_' . $data_source_prefix[0];
		$article_wrapper_helper_class = 'GK_NSP_Article_Wrapper_' . $article_wrapper;

		// get the results
		// PHP >= 5.3.* version
		// $results = $data_source_class::get_results($this, $article_wrapper_helper_class::number_of_articles($this->config));
		// PHP 5.2.* version
		$num_of_arts = call_user_func(array($article_wrapper_helper_class, 'number_of_articles'), $this->config);
		$results = call_user_func(array($data_source_class, "get_results"), $this, $num_of_arts);	

		// back to the current blog
		if(is_multisite() && $data_source_blog != '' && is_numeric($data_source_blog)) {
			restore_current_blog();
		}
		// restore the global $post variable
		$post = $tmp_post;
		// parse the data into a widget code
		// prepare widget classes
		$this->wdgt_class = 'gk-nsp';

		if(trim($widget_css_suffix) != '') {
			$this->wdgt_class .= ' ' . $this->config['widget_css_suffix'];
		}
		
		// check if the results exists
		if(count($results)) {
			//
			$tmp_post = $post;
			// change the source blog
			if(is_multisite() && $data_source_blog != '' && is_numeric($data_source_blog)) {
				switch_to_blog($data_source_blog);
			}
			
			$this->wdgt_results = $results;
			include($this->aw_path . GK_DS . $article_wrapper . GK_DS . $article_wrapper . '.php');

			// back to the current blog
			if(is_multisite() && $data_source_blog != '' && is_numeric($data_source_blog)) {
				restore_current_blog();
			}
			// restore the global $post variable
			$post = $tmp_post;
		} else {
			// ToDo: add something more interesting with graphic image
			echo '<p>' . __('Warning! There is no posts to display. Please check your widget settings', 'gk-nsp') . '</p>';
		}
		// save the cache results
		$cache_output = ob_get_flush();
		$cache_time = ($cache_time == '' || !is_numeric($cache_time)) ? 60 : (int) $cache_time;
		set_transient('widget_' . $this->id, $cache_output, $cache_time * 60);
		// 
		echo $after_widget;
	}

	/**
	 *
	 * Used in the back-end to render the widget form
	 *
	 * @param array instance of the widget settings
	 * @return void - the HTML output of the widget form
	 *
	 **/	

	function form($instance) {
		//
		// save the widget json cache
		//
		$default_language = 'en_US';
		$language = get_locale() != '' ? get_locale() : $default_language;
		$format_files = scandir($this->aw_path); 
		$base_path = $this->aw_path . GK_DS;
		$json_results = array();
		// iterate through files
		foreach($format_files as $file) {
			// filter the files
			if(
				$file != '.' && 
				$file != '..' && 
				is_dir($base_path . $file)
			) {
				$json_data = '';
				// read the json file
				if(is_file($base_path . $file . GK_DS . 'config-' . $language . '.json')) {
					$json_data = json_decode(file_get_contents($base_path . $file . GK_DS . 'config-' . $language . '.json'));
				} else {
					$json_data = json_decode(file_get_contents($base_path . $file . GK_DS . 'config-' . $default_language . '.json'));
				}
				// if the data are proper
				if(is_object($json_data) && isset($json_data->css)) {
					$json_results[$json_data->name] = $json_data;
				}
			}
		}
		// storage the results
		update_option('widget_gk_nsp_json_cache', $json_results);

		// get the proper values of options
		foreach($instance as $key => $value) {
			$this->config[$key] = !isset($instance[$key]) ? $this->config[$key] : $instance[$key];
		}

		// generate the form
		$form = new GK_NSP_Widget_Form();
		$form->get_form($this, $instance);
	}

	/**
	 *
	 * Used in the back-end to update the widget options
	 *
	 * @param array new instance of the widget settings
	 * @param array old instance of the widget settings
	 * @return updated instance of the widget settings
	 *
	 **/
	function update( $new_instance, $old_instance ) {
		//
		// save the widget settings
		//

		$instance = $old_instance;

		if(count($new_instance) > 0) {
			foreach($new_instance as $key => $option) {
				if(is_string($new_instance[$key])) {
					$instance[$key] = esc_attr(strip_tags($new_instance[$key]));	
				} else {
					if(is_array($new_instance[$key])) {
						foreach($new_instance[$key] as $id => $value) {
							$new_instance[$key][$id] = esc_attr(strip_tags($value));
						}

						$instance[$key] = $new_instance[$key];
					}
				}
			}
		}

		delete_transient('widget_' . $this->id);

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['gk_nsp'])) {
			delete_option( 'gk_nsp' );
		}
		// return teh instance
		return $instance;
	}

	/**
	 *
	 * Refreshes the widget cache data (for all instances)
	 *
	 * @return void
	 *
	 **/

	static function refresh_cache() {
		if(is_array(get_option('widget_gk_nsp'))) {
		    $ids = array_keys(get_option('widget_gk_nsp'));
		    for($i = 0; $i < count($ids); $i++) {
		        if(is_numeric($ids[$i])) {
		            delete_transient('widget_gk_nsp-' . $ids[$i]);
		        }
		    }
	    }
	}

	/**
	 *
	 * Refreshes the post slugs (for all instances)
	 *
	 * @return void
	 *
	 **/

	static function refresh_slugs($id, $post_after, $post_before) {
		$instances = get_option('widget_gk_nsp');
		// check if the instances are correct
		if(is_array($instances) || is_object($instances)) {
			// iterate through instances
			foreach($instances as $widget_id => $instance) {
				// check if the wrapper exist in the specific instance and isn't duplicated
				if(
					$instance['data_source_type'] == 'wp-post' && 
					in_array($post_before->post_name, explode(',', $instance['data_source']))
				) {
					$instance['data_source'] = str_replace($post_before->post_name, $post_after->post_name, $instance['data_source']);
					$instances[$widget_id]['data_source'] = $instance['data_source'];
				}
			}
		}

		update_option('widget_gk_nsp', $instances);
	}
}

// EOF
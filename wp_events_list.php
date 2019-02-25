<?php 
/**
 * Plugin Name: WP Events List
 * Description: Display an Event list in your site with an easy to use interface and custom post types
 * Version: 0.1.9
 * Author: Adrian Toro 
 * Domain Path: /languages
 * Text Domain: wp-events-list

**/

include( plugin_dir_path( __FILE__ ) . 'inc/class.posttypes.php');
include( plugin_dir_path( __FILE__ ) . 'inc/class.shortcodes.php');
include( plugin_dir_path( __FILE__ ) . 'inc/class.admin.php');

class WPeventsList {
		
	public function __construct() {
		
		
		// Scripts:
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		
		// Localize:
		add_action( 'plugins_loaded', array($this,'localize') );
		
		// WP Admin Functions:
		add_action( 'admin_enqueue_scripts', array('WPeventsList_Admin','enqueue_admin_scripts' ) );
		add_filter( 'manage_wpel_event_posts_columns', array('WPeventsList_Admin','add_event_columns') );
		add_action ('manage_wpel_event_posts_custom_column', array('WPeventsList_Admin','manage_event_columns'), 10, 2 );
		
		// Add meta box:
		add_action( 'add_meta_boxes', array('WPeventsList_Admin','custom_event_metaboxes' ) );
		add_action( 'save_post', array('WPeventsList_Admin','save_event' ) );
	
		
		add_action('edit_form_after_title', function() {
			global $post, $wp_meta_boxes;
			do_meta_boxes(get_current_screen(), 'below_title', $post);
			unset($wp_meta_boxes[get_post_type($post)]['below_title']);
		});
		
		
		//Post Types:
		add_action( 'init', array('WPeventsList_PostTypes','init_post_type'), 6 );
		
		//Shortcodes:
		add_shortcode( 'wpel_events', array('WPeventsList_Shortcodes', 'get_events') );
		add_shortcode( 'wpel_featured', array('WPeventsList_Shortcodes', 'get_featured') );
		
		// Alter Query:
		add_action( 'pre_get_posts', array($this,'sort_by_event_columns') );
		
		
	}
	
	public function enqueue_scripts(){
		
		
		wp_register_style( 'wpel.style', plugins_url('css/style.css', __FILE__ ), array(), '181007v01' );	
		wp_register_style( 'wpel.style.list', plugins_url('css/style.list.css', __FILE__ ), array(), '180425' );	
		
		
						 
	}
	
	public function localize(){
		load_plugin_textdomain( 'wp_events_list', false,  basename( dirname( __FILE__ ) ) . '/languages');
	}

	public function sort_by_event_columns( $query ){
		if ( is_admin() && $query->get('post_type') == 'wpel_event' ){
			$query->set( 'meta_key', 'event_date_start' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'DESC' );
		}
	}
}

$WPeventsList = new WPeventsList();

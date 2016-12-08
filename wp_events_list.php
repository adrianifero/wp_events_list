<?php 
/**
 * Plugin Name: WP Events List
 * Plugin URI: http://adriantoro.infoeplus.com
 * Description: Display an Event list in your site with an easy to use interface and custom post types
 * Version: 0.0.4
 * Author: Adrian Toro
 * Domain Path: /languages
 * Text Domain: wp-events-list

**/

/* Add Style and Scripts: */
if ( !function_Exists('wpel_add_admin_scripts_styles') ){
	add_action( 'admin_enqueue_scripts', 'wpel_add_admin_scripts_styles' );
	function wpel_add_admin_scripts_styles(){
		wp_enqueue_style( 'wpel_admin_style', plugin_dir_url( __FILE__ ) . 'css/wpel_admin.css' );
	}
}

if ( !function_exists('wpel_add_scripts_styles') ){
	add_action( 'wp_enqueue_scripts', 'wpel_add_scripts_styles' );
	function wpel_add_scripts_styles(){
		wp_enqueue_style( 'wpel_style-css', plugin_dir_url( __FILE__ ) . 'css/wpel_style.css' );
		
	}
}

/* Create Custom Post Type: */
if ( !function_exists('wpel_create_post_types' ) ) {

	add_action('init', 'wpel_create_post_types');
	function wpel_create_post_types() {
		$args = array(
		  	'public' 					=> true,
		  	'hierarchical' 				=> true,
		  	'label'  					=> 'Events',
			'rewrite' 					=> array('slug' => 'event'),
			  'capability_type' => 'event',
			  'map_meta_cap' => true,
			  'capabilities' => array(

				// meta caps (don't assign these to roles)
				'edit_post'              => 'edit_event',
				'read_post'              => 'read_event',
				'delete_post'            => 'delete_event',

				// primitive/meta caps
				'create_posts'           => 'create_events',

				// primitive caps used outside of map_meta_cap()
				'edit_posts'             => 'edit_events',
				'edit_others_posts'      => 'manage_events',
				'publish_posts'          => 'publish_events',
				'read_private_posts'     => 'read',

				// primitive caps used inside of map_meta_cap()
				'read'                   => 'read',
				'delete_posts'           => 'manage_events',
				'delete_private_posts'   => 'manage_events',
				'delete_published_posts' => 'manage_events',
				'delete_others_posts'    => 'manage_events',
				'edit_private_posts'     => 'edit_events',
				'edit_published_posts'   => 'edit_events'
			  ),
		  	'supports'					=> array ( 'title', 'editor', 'thumbnail', 'revisions' )
		);
		register_post_type( 'wpel_event', apply_filters( 'wpel_event_post_type_args', $args ) );
	}
}

if ( is_admin() ) {
     // We are in admin mode
     require_once( dirname(__file__).'/admin/wpel_admin.php' );
}

require_once( dirname(__file__).'/inc/wpel_shortcodes.php' );

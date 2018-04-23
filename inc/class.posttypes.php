<?php 

class WPeventsList_PostTypes { 

	public function init_post_type() {
		$args = array(
		  	'public' 					=> true,
		  	'hierarchical' 				=> true,
		  	'label'  					=> 'Events',
			'with_front' 				=> false,
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
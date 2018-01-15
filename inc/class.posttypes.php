<?php 

class WPeventsList_PostTypes { 

	public function init_post_type() {
		$args = array(
		  	'public' 					=> true,
		  	'hierarchical' 				=> true,
		  	'label'  					=> 'Events',
			'rewrite' 					=> array('slug' => 'event'),
		  	'supports'					=> array ( 'title', 'editor', 'thumbnail', 'revisions' )
		);
		register_post_type( 'wpel_event', apply_filters( 'wpel_event_post_type_args', $args ) );
	}
	
}
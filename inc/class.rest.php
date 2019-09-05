<?php

class RossvideoCustomers_REST {
    
    public function __construct(){
        
        add_filter( 'register_post_type_args', array( $this, 'wpel_event_rest' ), 10, 2 );

		add_action( 'rest_api_init', array( $this, 'create_api_posts_meta_field' ) );

        
    }
    
    public function wpel_event_rest( $args, $post_type ) {
 
		if ( 'wpel_event' === $post_type ) {
			$args['show_in_rest'] = true;

			// Optionally customize the rest_base or rest_controller_class
			$args['rest_base']             = 'events';
			$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
		}

		return $args;
	}
	
	public function wpel_event_rest_order_by($args, $query) {

		$args["orderby"] = "meta_value";
		$args["meta_key"] = "event_date_start";
		

		return $args;

	}
	
	
	public function create_api_posts_meta_field() {

		// register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
		register_rest_field( 'wpel_event', 'event_info', array(
			   'get_callback'    =>  array($this, 'get_post_meta_for_api'),
			   'schema'          => null,
			)
		);

	}

	public static function get_post_meta_for_api( $object ) {
		//get the id of the post object array
		$post_id = $object['id'];
		$post_meta = get_post_meta( $post_id );
		
		$event_info = new stdClass();
		
		$event_info->event_date_start = get_post_meta( $post_id, 'event_date_start', true );
		$event_info->event_date_end = get_post_meta( $post_id, 'event_date_end', true );
		$event_info->event_featured = get_post_meta( $post_id, 'event_featured', true );
		$event_info->event_place = get_post_meta( $post_id, 'event_place', true );
		$event_info->event_place_city = get_post_meta( $post_id, 'event_place_city', true );
		$event_info->event_place_state = get_post_meta( $post_id, 'event_place_state', true );
		$event_info->event_place_country = get_post_meta( $post_id, 'event_place_country', true );
		$event_info->event_link = get_post_meta( $post_id, 'event_link', true );
		
		//return the post meta
		return $event_info;
	}
	
}

$RossvideoCustomers_REST = new RossvideoCustomers_REST();

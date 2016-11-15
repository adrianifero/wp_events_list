<?php

/* Add Custom Columns to Events Post Type: */

if ( !function_exists('wpel_add_custom_columns')){
	add_filter('manage_wpel_event_posts_columns','wpel_add_custom_columns');
	function wpel_add_custom_columns($columns){
		$custom_columns = array (
							'wpel_event_date_start' => __('From'),
							'wpel_event_date_end' => __('To'),
							'date' => __('Published')
						);
		return array_merge ($columns, $custom_columns);
	}
}

if ( !function_exists('wpel_manage_custom_columns' ) ) {
	add_action ('manage_wpel_event_posts_custom_column', 'wpel_manage_custom_columns', 10, 2 );
	function wpel_manage_custom_columns( $column, $post_id ){
		
		switch ( $column ) {
			case 'wpel_event_date_start':
				$date_start = get_post_meta ( $post_id, 'event_date_start', true);
				echo $date_start;
				break;
			case 'wpel_event_date_end':
				$date_end = get_post_meta ( $post_id, 'event_date_end', true);
				echo $date_end;
				break;
			case 'date':
				
		}
		
	}
}

if ( !function_exists('wpel_sort_by_custom_columns')){
	add_action( 'pre_get_posts', 'wpel_sort_by_custom_columns');
	function wpel_sort_by_custom_columns( $query ){
		if ( $query->get('post_type') == 'wpel_event' ){
			$query->set( 'meta_key', 'event_date_start' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'DESC' );
		}
	}
}


add_action( 'add_meta_boxes_wpel_event', 'wpel_adding_custom_meta_boxes_wpel_event' );
function wpel_adding_custom_meta_boxes_wpel_event( $post ) {
    add_meta_box( 
        'events-extras',
        __( 'Event Info' ),
        'wpel_events_meta_box_render',
        'wpel_event',
        'advanced',
        'default'
    );
}

function wpel_events_meta_box_render( $post ) {
	$event_date_start ='';
	$event_date_end ='';
	$event_place ='';
	
	$values = get_post_custom( $post->ID );
	if ( isset($values['event_date_start']) ) {
		$event_date_start = $values['event_date_start'][0];
		$event_date_end = $values['event_date_end'][0];
		$event_place = $values['event_place'][0];
	}
	
	wp_nonce_field( plugin_basename( __FILE__ ), 'event_text_box_content_nonce' );
	
	echo '<div id="event_date_start" class="event_box">';	 
	echo '<h2>Start Date</h2>';
	echo '<input type="hidden" id="event_date_start" name="event_date_start" style="width:100%; max-width:600px;" value="'.esc_attr($event_date_start).'" placeholder="Date" />';	
	echo '<div id="event_date_start_picker"></div>';
	echo '<script> '.
		 'jQuery(function() { '. 
	 		'jQuery( "#event_date_start_picker" ).datepicker({ '.
				'dateFormat: "yy-mm-dd", '.
				'onSelect: function(dateText, inst) { '.
					'jQuery("input[name=\'event_date_start\']").val(dateText); '.
					'jQuery("input[name=\'event_date_end\']").val(dateText); '.
					'jQuery("#event_date_end_picker").datepicker( "setDate", dateText ); '.
					'jQuery("#event_date_end_picker").datepicker( "option", "minDate", dateText ); '.
				'},  '.
			'}).datepicker( "setDate", "'.$event_date_start.'" ); '.
		 '}); '.
         '</script>'; 
	echo '</div>';
	
	echo '<div id="event_date_end" class="event_box">';	 
	echo '<h2>End Date</h2>';
	echo '<input type="hidden" id="event_date_end" name="event_date_end" style="width:100%; max-width:600px;" value="'.esc_attr($event_date_end).'" placeholder="Date" />';	
	echo '<div id="event_date_end_picker"></div>';
	echo '<script>jQuery(function() { '. 
	 		'jQuery( "#event_date_end_picker" ).datepicker({ '.
				'dateFormat: "yy-mm-dd", '.
				'minDate: "'.$event_date_start.'", '.
				'onSelect: function(dateText, inst) { '.
					'jQuery("input[name=\'event_date_end\']").val(dateText); },  '.
				'}).datepicker( "setDate", "'.$event_date_end.'" ); '.
			'}); '.
           '</script>';	   
	echo '</div>';
	
	echo '<div id="event_details" class="event_box" style="width:200px;">';	 
	echo '<h2>Place</h2>';
	echo '<input type="text" id="event_place" name="event_place" style="width:100%; max-width:600px;" value="'.esc_attr($event_place).'" placeholder="City, Country" />';	
	echo '</div>';
	
		 
}
	
add_action( 'save_post', 'wpel_event_text_box_save' );
function wpel_event_text_box_save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;

    if ( !$_POST || !wp_verify_nonce( $_POST['event_text_box_content_nonce'], plugin_basename( __FILE__ ) ) )
    return;

    if ( 'wpel_event' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }
    $event_date_start = $_POST['event_date_start'];
    update_post_meta( $post_id, 'event_date_start', $event_date_start);
    $event_date_end = $_POST['event_date_end'];
    update_post_meta( $post_id, 'event_date_end', $event_date_end);
	
    $event_place = $_POST['event_place'];
    update_post_meta( $post_id, 'event_place', $event_place);
	
}

<?php



class WPeventsList_Admin { 
	
	public function add_event_columns($columns){
		$custom_columns = array (
							'wpel_event_date_start' => __('From'),
							'wpel_event_date_end' => __('To'),
							'date' => __('Published')
						);
		return array_merge ($columns, $custom_columns);
	}

	public function manage_event_columns( $column, $post_id ){

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

	public function custom_event_metaboxes( $post ) {
		add_meta_box( 
			'events-extras',
			__( 'Event Info' ),
			'meta_box_render',
			'wpel_event',
			'advanced',
			'default'
		);
	}

	public function meta_box_render( $post ) {
		$event_date_start ='';
		$event_date_end ='';
		$event_place ='';
		$event_place_city ='';
		$event_place_state ='';
		$event_place_country ='';

		$values = get_post_custom( $post->ID );
		if ( isset($values['event_date_start']) ) {
			$event_date_start = $values['event_date_start'][0];
		}
		if ( isset($values['event_date_end']) ) {
			$event_date_end = $values['event_date_end'][0];
		}
		if ( isset($values['event_place']) ) {
			$event_place = $values['event_place'][0];
		}
		if ( isset($values['event_place_city']) ) {
			$event_place_city = $values['event_place_city'][0];
		}
		if ( isset($values['event_place_state']) ) {
			$event_place_state = $values['event_place_state'][0];
		}
		if ( isset($values['event_place_country']) ) {
			$event_place_country = $values['event_place_country'][0];
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

		echo '<div id="event_details" class="event_box" style="width:200px;">'
			;	 
		echo '<h2>Place</h2>';

		echo '<input type="text" id="event_place" name="event_place" style="width:100%; max-width:600px;" value="'.esc_attr($event_place).'" placeholder="Address" />';	

		echo '<input type="text" id="event_place_city" name="event_place_city" style="width:100%; max-width:600px;" value="'.esc_attr($event_place_city).'" placeholder="City" />';	

		echo '<input type="text" id="event_place_state" name="event_place_state" style="width:100%; max-width:600px;" value="'.esc_attr($event_place_state).'" placeholder="State" />';	

		echo '<input type="text" id="event_place_country" name="event_place_country" style="width:100%; max-width:600px;" value="'.esc_attr($event_place_country).'" placeholder="Country" />';	
		echo '</div>';


	}

	public function save_event( $post_id ) {

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

		$event_place_city = $_POST['event_place_city'];
		update_post_meta( $post_id, 'event_place_city', $event_place_city);

		$event_place_state = $_POST['event_place_state'];
		update_post_meta( $post_id, 'event_place_state', $event_place_state);

		$event_place_country = $_POST['event_place_country'];
		update_post_meta( $post_id, 'event_place_country', $event_place_country);

	}


}

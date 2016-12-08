<?php
/* Events shortcode */
add_shortcode( 'wpel_events', 'wpel_events_shortcode' );
function wpel_events_shortcode(){
	global $user_location;
	
	$args = array( 
		'post_type' => 'wpel_event', 
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'meta_query' => array(
							array(
								'key' => 'event_date_start',
								'value' => date('Y-m-d'),
								'compare' => '>=',
							)
						) 
	); 
	$events_query = new WP_Query ( $args );

	if ( $events_query->have_posts() ): 
	
		$event_list = '<table class="wpel_event_list" width="600" border="0" cellspacing="2">';
		while ( $events_query->have_posts() ) : $events_query->the_post();

			$event_starts = get_post_meta( get_the_ID(), 'event_date_start' , true );
			$event_ends = get_post_meta( get_the_ID(), 'event_date_end' , true );
			$month = date("M",strtotime($event_starts)); 
			$day_starts = date("d",strtotime($event_starts)); 
			$day_ends = date("d",strtotime($event_ends)); 
			$dates = ( !empty( $day_ends ) && $day_ends != $day_starts ) ? $day_starts .' - '. $day_ends : $day_starts; 

			$event_place = get_post_meta( get_the_ID(), 'event_place' , true );
			$event_place_city = get_post_meta( get_the_ID(), 'event_place_city' , true );
			$event_place_state = get_post_meta( get_the_ID(), 'event_place_state' , true );
			$event_place_country = get_post_meta( get_the_ID(), 'event_place_country' , true );

	
			$event_custom_class = '';
			if ( !empty($user_location['country']) && ( $user_location['country'] == $event_place_country ) ){
				$event_custom_class = 'visitor_country';
			}
			
			$event_list .= '<tr class="'.$event_custom_class.'">
								<td>';
			$event_list .= 			'<div class="side_date">
										<div class="month">'.$month.'</div>
										<div class="day">'.$dates.'</div>
									</div>
								</td>';

			$event_list .= 		'<td>
									<div class="event">
										<h6>'.get_the_title().'</h6>
										<p>';

			// If event is in user country, make it shine! 


			// Load the full event address:
			if ( !empty($event_place) ) { $event_list .= $event_place . ', '; }
			if ( !empty($event_place_city) ) { $event_list .= $event_place_city . ', '; }
			if ( !empty($event_place_state) ) { $event_list .= $event_place_state . ', '; }
			if ( !empty($event_place_country) ) { $event_list .= $event_place_country; }


			$event_list .= 				'</p>
									</div>
								</td>
							</tr>';
		endwhile; 
		$event_list .= '</table>';

		return $event_list;
	endif; 
	wp_reset_postdata(); 
	
	
}
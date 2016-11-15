<?php
/* Events shortcode */
add_shortcode( 'wpel_events', 'wpel_events_shortcode' );
function wpel_events_shortcode(){
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
	
		$event_list = '<table width="600" border="0" cellspacing="2">';
		while ( $events_query->have_posts() ) : $events_query->the_post();
			$event_list .= '<tr>
								<td>';
					$event_starts = get_post_meta( get_the_ID(), 'event_date_start' , true );
					$event_ends = get_post_meta( get_the_ID(), 'event_date_end' , true );
					$month = date("M",strtotime($event_starts)); 
					$day_starts = date("d",strtotime($event_starts)); 
					$day_ends = date("d",strtotime($event_ends)); 
					$dates = ( !empty( $day_ends ) && $day_ends != $day_starts ) ? $day_starts .' - '. $day_ends : $day_starts; 
					$event_list .= '<div class="side_date">
										<div class="month">'.$month.'</div>
										<div class="day">'.$dates.'</div>
									</div>
								</td>
								<td>
									<div class="event">
										<h6>'.get_the_title().'</h6>
										<p>'.get_post_meta( get_the_ID(), 'event_place' , true ).'</p>
									</div>
								</td>
							</tr>';
		endwhile; 
		$event_list .= '</table>';

		return $event_list;
	endif; 
	wp_reset_postdata(); 
	
	
}
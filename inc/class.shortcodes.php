<?php

class WPeventsList_Shortcodes {
	
	/* Events shortcode */
	public static function get_featured( $atts ){
		
		wp_enqueue_style( 'wpel.style' );
		wp_enqueue_style( 'wpel.style.list' );
		
		global $user_location;
		
		$event_list = '';
	
		$filters = shortcode_atts( array(
			'qty' => 1,
			'featured' => 1, 
			'type' => 'wpel_event',
			'default_link' => '/company/events/',
		), $atts, 'rossvideo' );
		
		$seemoreurl = $atts['default_link'];
		
		// create args for Query:
		$args = array( 
			'post_type' => $filters['type'], 
			'meta_key' => 'event_date_start',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'posts_per_page' => $filters['qty'],
			'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => 'event_featured',
									'value' => $filters['featured'],
									'compare' => '=',
								),
								array(
									'key' => 'event_date_start',
									'value' => date('Y-m-d'),
									'compare' => '>=',
								)
							) 
		); 

		$event_list = get_transient( 'ross_event_featured' );
		
		if ( false === $event_list ) :
			if(is_multisite()){ switch_to_blog(1); }

			$events_query = new WP_Query ( $args );

			if ( $events_query->have_posts() ): 

				while ( $events_query->have_posts() ) : $events_query->the_post();

					$event_meta = get_post_meta( get_the_ID() );
					//print_r( $event_meta );

					$event_starts = $event_meta ['event_date_start'][0];
					$event_ends = $event_meta ['event_date_end'][0];
					$month = date("M",strtotime($event_starts)); 
					$year = date("Y",strtotime($event_starts)); 
					$day_starts = date("d",strtotime($event_starts)); 
					$day_ends = date("d",strtotime($event_ends)); 
					$dates = ( !empty( $day_ends ) && $day_ends != $day_starts ) ? $day_starts .' - '. $day_ends : $day_starts; 

					$event_place = $event_meta ['event_place'];
					$event_place_city = $event_meta ['event_place_city'][0];
					$event_place_state = $event_meta ['event_place_state'][0];
					$event_place_country = $event_meta ['event_place_country'][0];
					$event_url = $event_meta ['event_link'][0];

					$event_url = !empty($event_url) ? $event_url : $seemoreurl;
					$event_link =  '<a class="event_button" href="'.$event_url.'">Read More</a>';
					$event_title = get_the_title();


					$event_custom_class = '';
					if ( !empty($user_location['country']) && ( $user_location['country'] == lcfirst(strtolower($event_place_country)) ) ){
						$event_custom_class = 'user_country';
					}

					$event_list .= '<div class="event_featured">';

						$event_list .= !empty($event_url) ? '<h6 class="event_title"><a target="_blank" href="'.$event_url.'">'.$event_title.'</a></h6>' : '<h6>'.$event_title.'</h6>';

						$event_list .= '<p class="event_details">';

						$event_list .=  $event_place_city.', '. $event_place_country .' - '.$month.' '.$dates.', '.$year;

						$event_list .= '</p>';

						$event_list .= $event_link;

					$event_list .= '</div>';

				endwhile; 



			else:

				$event_list = '<div class="event_featured"><p>' . __('Take a look at where we’ll be next!','wp_events_list') . '</p>' . '<a target="_blank" class="event_button" href="/company/events">Events Calendar</a></div>';
				

			endif; 

			if(is_multisite()){ restore_current_blog(); }
			wp_reset_postdata(); 
			set_transient( 'ross_event_featured', $event_list, 12 * HOUR_IN_SECONDS );
			
		
		endif;
		
		return $event_list;
		
		
	}
	
	/* Events shortcode */
	public static function get_events( $atts ){
		wp_enqueue_style( 'wpel.style' );
		wp_enqueue_style( 'wpel.style.list' );
		
		global $user_location;
	
		$filters = shortcode_atts( array(
			'qty' => -1,
			'cat' => '', // 56: APAC
			'type' => 'wpel_event',
			'region' => ''
		), $atts, 'rossvideo' );

		$selectedRegion = $filters['region'];

		$region['APAC'] = array( 'Australia', 'Bangladesh', 'Bhutan', 'Brunei', 'Burma', 'Cambodia', 'China', 'Fiji', 'India', 'Indonesia', 'Japan', 'Kiribati', 'Laos', 'Malaysia', 'Maldives', 'Micronesia', 'Mongolia', 'Nauru', 'Nepal', 'North Korea', 'Pakistan', 'Palau', 'Philippines', 'Samoa', 'Singapore', 'South Korea', 'Sri Lanka', 'Thailand', 'Tonga', 'Tuvalu', 'Vanuatu', 'Vietnam' );

		$region['NA'] = array ( 'United States', 'USA', 'Canada', 'Mexico', 'Puerto Rico', 'Cuba', 'Costa Rica', 'Dominican Republic', 'Jamaica', 'Bahamas', 'Panama' );  

		$region['LATAM'] = array ( 'Antigua & Barbuda', 'Aruba', 'Bahamas', 'Barbados', 'Cayman Islands', 'Cuba', 'Dominica', 'Dominican Republic', 'Grenada', 'Guadeloupe', 'Haiti', 'Jamaica', 'Martinique', 'Puerto Rico', 'Saint Barthélemy', 'St. Kitts & Nevis', 'St. Lucia', 'St. Vincent and the Grenadines', 'Trinidad & Tobago', 'Turks & Caicos Islands', 'Virgin Islands', 'Belize', 'Costa Rica', 'El Salvador', 'Guatemala', 'Honduras', 'Mexico', 'Nicaragua', 'Panama', 'Argentina', 'Bolivia', 'Brazil', 'Chile', 'Colombia', 'Ecuador', 'French Guiana', 'Guyana', 'Paraguay', 'Peru', 'Suriname', 'Uruguay', 'Venezuela' );  

		$region['EMEA'] = array( 'Albania', 'Algeria', 'Andorra', 'Angola', 'Austria', 'Bahrain', 'Belarus', 'Belgium', 'Benin', 'Bosnia and Herzegovina', 'Botswana', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cameroon', 'Cape Verde', 'Central African Republic', 'Chad', 'Comoros', 'Croatia', 'Cyprus', 'Czech Republic', 'Democratic Republic of the Congo', 'Denmark', 'Djibouti', 'Egypt', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Faroe Islands', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Guernsey', 'Guinea', 'Guinea-Bissau', 'Hungary', 'Iceland', 'Iran', 'Iraq', 'Ireland', 'Isle Of Man', 'Israel', 'Italy', 'Ivory Coast', 'Jersey', 'Jordan', 'Kenya', 'Kuwait', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macedonia', 'Madagascar', 'Malawi', 'Mali', 'Malta', 'Mauritania', 'Mauritius', 'Moldova', 'Monaco', 'Montenegro', 'Morocco', 'Mozambique', 'Namibia', 'Netherlands', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Palestine', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Rwanda', 'San Marino', 'Sao Tome & Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Slovakia', 'Slovenia', 'Somalia', 'South Africa', 'Spain', 'Sudan', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Tanzania', 'Togo', 'Tunisia', 'Turkey', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'Vatican City', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe (Rhodesia)' );

		// create args for Query:
		$args = array( 
			'post_type' => $filters['type'], 
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'posts_per_page' => $filters['qty'],
			'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => 'event_date_start',
									'value' => date('Y-m-d'),
									'compare' => '>=',
								)
							) 
		); 

		// If querying events:
		if ( !empty( $filters['region'] ) ){
			$args['meta_query'][] = 
				array(
					'key' => 'event_place_country',
					'value' => $region[ $selectedRegion ], //array
					'compare' => 'IN'
				);
		}

		if ( false === ( $event_list = get_transient( 'ross_event_list' ) ) ) :
		
			if(is_multisite()){ switch_to_blog(1); }

			$events_query = new WP_Query ( $args );

			if ( $events_query->have_posts() ): 

				$event_list = '<table class="wpel_event_list" width="600" border="0" cellspacing="2">';
				while ( $events_query->have_posts() ) : $events_query->the_post();

					$event_starts = get_post_meta( get_the_ID(), 'event_date_start' , true );
					$event_ends = get_post_meta( get_the_ID(), 'event_date_end' , true );
					$month = date("M",strtotime($event_starts)); 
					$year = date("Y",strtotime($event_starts)); 
					$day_starts = date("d",strtotime($event_starts)); 
					$day_ends = date("d",strtotime($event_ends)); 
					$dates = ( !empty( $day_ends ) && $day_ends != $day_starts ) ? $day_starts .' - '. $day_ends : $day_starts; 

					$event_place = get_post_meta( get_the_ID(), 'event_place' , true );
					$event_place_city = get_post_meta( get_the_ID(), 'event_place_city' , true );
					$event_place_state = get_post_meta( get_the_ID(), 'event_place_state' , true );
					$event_place_country = get_post_meta( get_the_ID(), 'event_place_country' , true );
					$event_link = get_post_meta( get_the_ID(), 'event_link' , true );


					$event_custom_class = '';
					if ( !empty($user_location['country']) && ( $user_location['country'] == lcfirst(strtolower($event_place_country)) ) ){
						$event_custom_class = 'user_country';
					}

					$event_title = !empty($event_link) ? '<h6><a target="_blank" href="'.$event_link.'">'.get_the_title().'</a></h6>' : '<h6>'.get_the_title().'</h6>';

					$event_list .= '<tr class="'.$event_custom_class.'">
										<td>';
					$event_list .= 			'<div class="side_date">
												<div class="month">'.$month.'</div>
												<div class="day">'.$dates.'</div>
												<div class="year">'.$year.'</div>
											</div>
										</td>';

					$event_list .= 		'<td>
											<div class="event">
												'.$event_title.'
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

				restore_current_blog();

			else:
				if(is_multisite()){ restore_current_blog(); }
				wp_reset_postdata(); 

				$event_list = '<p>' . __('No upcoming events','wp_events_list') . '</p>';

			endif; 

			if(is_multisite()){ restore_current_blog(); }
			wp_reset_postdata(); 
			set_transient( 'ross_event_list', $event_list, 12 * HOUR_IN_SECONDS );
		
		
		endif;
		
		return $event_list;


	}
	
}
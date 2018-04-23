
(function( $ ) {
    "use strict";
	$(function() {
	
		
		if ( $('body.post-type-wpel_event').length > 0 ){
			
			var startDate = '';
			var endDate = '';

			if ( $('input#event_date_start').val() ) {
				startDate = $('input#event_date_start').val();
			}

			if ( $('input#event_date_end').val() ) {
				endDate = $('input#event_date_end').val();
			}
 
			$( "#event_date_start_picker" ).datepicker({
				dateFormat: "yy-mm-dd",
				onSelect: function(dateText, inst) {
					$("input[name=\'event_date_start\']").val(dateText);
					$("input[name=\'event_date_end\']").val(dateText);
					$("#event_date_end_picker").datepicker( "setDate", dateText );
					$("#event_date_end_picker").datepicker( "option", "minDate", dateText );
				},
			}).datepicker( "setDate", startDate );

			$( "#event_date_end_picker" ).datepicker({
				dateFormat: "yy-mm-dd",
				minDate: "'.$event_date_start.'",
				onSelect: function(dateText, inst) {
					$("input[name=\'event_date_end\']").val(dateText);
				},
			}).datepicker( "setDate", endDate );
		}
						  
	});
}($));
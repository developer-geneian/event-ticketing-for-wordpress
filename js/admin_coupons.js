jQuery(document).ready(function($) {

	$('#wpet_admin_coupons_add').submit(function() {
		//do validation
		if ( ! $.isNumeric( $( '#amount' ).val() ) ) {
				alert( wpet_coupons_add.amount_not_numeric );
				return false;
		}

		if ( $('#type').val() == 'percentage' ) {
			if ( $( '#amount' ).val() > 100 ) {
				alert( wpet_coupons_add.percent_too_high );
				return false;
			}
				
		} 

		return true;
	});

});
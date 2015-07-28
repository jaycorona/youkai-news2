jQuery(document).ready(function($) {

	var $Form = $(".wauc_form");

	$('.handlediv' , $Form).live( 'click', function() {
		$(this).parent().toggleClass('closed');
	});

});

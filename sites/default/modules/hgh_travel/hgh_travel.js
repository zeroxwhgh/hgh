(function($) {
	$(document).ready(function() {
		$("#inquire-form").validate({
			rules: {
				first_name: {required: true},
				last_name: {required: true},				
				email: {required: true, email: true},
				country: {required: true},
				arrival_date: {required: true},
				duration: {required: true}
			},
			messages: {
				first_name: "",
				last_name: "",
				email: "",
				country: "",
				arrival_date: "",
				duration: "",
				captcha_response: ""
			}
		});
	});
})(jQuery);
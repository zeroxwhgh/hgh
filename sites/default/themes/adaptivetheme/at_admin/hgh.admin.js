jQuery(document).ready(function() {
	$url = location.href;
	if (($url.indexOf('node/add/tour') > -1) || $url.indexOf('node/add/tour-day') > -1) {
		jQuery('#edit-body a.link-edit-summary').click();
	}
	
	jQuery('#form-inquiry-list .inquiry-delete').click(function() {
		if (confirm("Do you want to delete selected items?")) {
			jQuery("#form-inquiry-list").submit();
		}
	});
	
	jQuery('#form-inquiry-list .inquiry-checkall').click(function() {
		if (jQuery('.inquiry-checkall').val() == "Check All") {
			jQuery(".inquires-list .inquiry-checkbox").attr('checked', true);
			jQuery('.inquiry-checkall').val("Un-Check All")
		} else {
			jQuery(".inquires-list .inquiry-checkbox").attr('checked', false);
			jQuery('.inquiry-checkall').val("Check All")
		}
	});
});
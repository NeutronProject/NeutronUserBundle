jQuery(document).ready(function(){
	
	jQuery('#_submit').click(function(){
		
		jQuery(this).attr('disabled', true);
		jQuery('#username').attr('disabled', true);
		jQuery('#container-error').find('strong').text('').end().hide();
		jQuery('#ajax-loader').show();
		
		var params = {
			username: jQuery('#username').val(),
		};
		
		jQuery.post(jQuery('#form-request').attr('action'), params, function(response){
			var data = jQuery.parseJSON(response);
			if(data.success == true){
				window.location.href = data.url;
			} else {
				jQuery('#container-error').find('span').text(data.error).end().fadeIn();
				jQuery('#password').val('');
			}
			
			jQuery('#ajax-loader').hide();
			jQuery('#username').attr('disabled', false);
			jQuery('#_submit').attr('disabled', false);
		});
		
		return false;
	});
	
});
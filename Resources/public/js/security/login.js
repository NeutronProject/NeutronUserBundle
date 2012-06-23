jQuery(document).ready(function(){
	
	jQuery('#_submit').click(function(){
		
		jQuery(this).attr('disabled', true);
		jQuery('#username').attr('disabled', true);
		jQuery('#password').attr('disabled', true);
		jQuery('#_remember_me').attr('disabled', true);
		jQuery.uniform.update();
		jQuery('#container-error').find('strong').text('').end().hide();
		jQuery('#ajax-loader').show();
		
		var params = {
			_username: jQuery('#username').val(),
			_password: jQuery('#password').val(),
			_remember_me: jQuery('#_remember_me').is(':checked'),
			_csrf_token: jQuery('#_csrf_token').val()
		};
		
		jQuery.post(jQuery('#form-login').attr('action'), params, function(response){
			var data = jQuery.parseJSON(response);
			if(data.success == true){
				window.location.href = data.url;
			} else {
				jQuery('#container-error').find('span').text(data.error).end().fadeIn();
				jQuery('#password').val('');
			}
			
			jQuery('#ajax-loader').hide();
			jQuery('#username').attr('disabled', false);
			jQuery('#password').attr('disabled', false);
			jQuery('#_remember_me').attr('disabled', false);
			jQuery.uniform.update();
			jQuery('#_submit').attr('disabled', false);
		});
		
		return false;
	});
	
});
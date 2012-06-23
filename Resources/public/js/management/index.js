jQuery(document).ready(function(){
	jQuery('body').bind('neutron.datagrid.event.massAction', function(event){
		if(event.action == 'enableUsers'){
			jQuery('#messages').append(jQuery('#message-enable-users-prototype').html());
		} else if(event.action == 'disableUsers'){
			jQuery('#messages').append(jQuery('#message-disable-users-prototype').html());
		} else if(event.action == 'lockUsers'){
			jQuery('#messages').append(jQuery('#message-lock-users-prototype').html());
		} else if(event.action == 'unlockUsers'){
			jQuery('#messages').append(jQuery('#message-unlock-users-prototype').html());
		}
		
	});
});

//call by homepage
jQuery(function(){
	var no = new ComboBox("cb_identifier");
	
	jQuery('#cb a').click(function(){
		jQuery.ajax({
			'data': 'ii='+jQuery(this).attr('rel')+'&isAjaxRequest=1',
			'method': 'GET',
			'url': jQuery(this).parents('form').attr('action'),
			'success': function(output){
				//alert(output);
				jQuery('#inter_rates').html(output);
			}
		});
	})
});
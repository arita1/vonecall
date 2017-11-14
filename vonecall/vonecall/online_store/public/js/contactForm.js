/*
 * Use for contact form
 */
//QT: cập nhật giá trị cho category
jQuery(function(){
	var no = new ComboBox("cb_identifier1");
	
	//for remain status when subtmit
	$isError = jQuery('#contact-form_es_').css('display') == 'block'?1:0;
	
	if($isError)
		{
			$category = jQuery('#ContactForm_category').val();
			jQuery('#cb a[rel='+$category+']').trigger('click');
		}
	
		jQuery('#cb a').click(function(){
			$category = jQuery(this).attr('rel');
			
			jQuery('#ContactForm_category').val($category);
		});
});
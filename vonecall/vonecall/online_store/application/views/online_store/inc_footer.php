<footer class="my_footers">
<div  height="37">   
	<div>
		<div style="float:left">
			<p class="leftt" style="padding-left: 15px;">
				<a href="<?php echo base_url();?>vonecall-terms">Terms</a> |
				<a href="<?php echo base_url();?>vonecall-contact-us">Support</a>
			</p>
		</div>
	    	
	    	<a href="#" class="autho"><img src="<?php echo base_url(); ?>img/authorize_net.png" title="" /></a>
	    	<a href="#" class="my_cards"><img src="<?php echo base_url(); ?>img/footer-credit-card-logos-highres.png" title="" height="20px;" /></a>
	    	
		<div style="float:right"><p align="right">
			<a href="#" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube </a> &nbsp; 
	    	<a href="http://www.facebook.com" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i>
					Facebook </a> <?php echo '&copy' . date("Y") ; ?> Vonecall. All rights reserved</p>
		</div>
	</div>
</div>



<!-- js -->
<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.bxslider.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js" ></script>

<!---------------------------------->






<script type="text/javascript" src="<?php echo base_url()?>js/jquery.validate.min.js"></script>      
<script type="text/javascript" src="<?php echo base_url()?>js/form-validator.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/contact-form-script.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/validationScript.js"></script>
<!--common scripts for all pages-->
<!--<script type="text/javascript" src="<?php echo base_url()?>js/scripts.js"></script>-->



<script type="text/javascript">
$("#card_number").mask("9999999999999999");
$("#card_ccv").mask("9999");
</script>


<!------------------------------------>
<script>
$(document).ready(function(){
  $('.bxslider').bxSlider({
  auto: true,
  autoControls: false
});
});
</script>
<script type="text/javascript">
  
	$('#city_rate').change(function(){
		
	var city_rate=$(this).val();
	
	$.ajax({
		
		type:"post",
		url:"<?php echo base_url();?>frontend/getCityRatesByID/",	   
	    data:"ID="+city_rate,
	    success:function(result)
	    {
			// console.log(result);return false;
				
			if(result!='false')    	
             {
				var json_obj = $.parseJSON(result);//parse JSON
	            
	            for (var i in json_obj) 
	            {
	                
	                 document.getElementById("city_rates").innerHTML =json_obj[i].rate+' &cent;/Min';
	                 document.getElementById("destination").innerHTML =json_obj[i].destination;

	            }
			 }else{
				
					$("#error").html("please try again ");
					$("#error").show();
				  }
			}   
      });
   });

function submitForm(){
    // Initiate Variables With Form Content
    var name = $("#name").val();
    var email = $("#email").val();
    var msg_subject = $("#msg_subject").val();
    var message = $("#message").val();


    $.ajax({
        type: "POST",
        url: "<?php echo base_url()?>frontend/save_enqury",
        data: "name=" + name + "&email=" + email + "&msg_subject=" + msg_subject + "&message=" + message,
        success : function(text){
        	
            if (text == "success"){
            	
                formSuccess();
            } else {
                formError();
                submitMSG(false,text);
            }
        }
    });
}

function formSuccess(){
    $("#contactForm")[0].reset();
    submitMSG(true, "Message Submitted!")
}

function formError(){
    $("#contactForm").removeClass().addClass('shake animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        $(this).removeClass();
    });
}

function submitMSG(valid, msg){
    if(valid){
        var msgClasses = "h3 text-center tada animated text-success";
    } else {
        var msgClasses = "h3 text-center text-danger";
    }
    $("#msgSubmit").removeClass().addClass(msgClasses).text(msg);
}		


</script>

<script>
function selectUser() {
    var x = document.getElementById("login_select").value;
    
    if(x==1){
    	 window.location="https://www.vonecall.com/";
    }else if(x==2){
    	 window.location="https://www.vonecall.com/";
    }else if(x==3){
    	window.location="https://www.vonecall.com/distributors/login";
    }else{
    	
    }
    
    
}


</script>


<script type="text/javascript"> 
$(document).ready(function(){ 
	$('#act_funding').click(function(){
		$('#funding_div').show();
		$('#recharge_div').hide();
	});
	$('#act_recharge').click(function(){
		$('#funding_div').hide();
		$('#recharge_div').show();
	});
	
});

$(document).ready(function(){ 
	$('#mylogin').click(function(){
		
		var getemail = $('#login_email').val().trim();
		var getPassword = $('#login_password').val().trim();
		//alert(getPassword);
		var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = emailReg.test(getemail);		
		
		if(valid == true){	 
			$.ajax({ 				
				type: 'post',
				url: 'http://xtowing.com/superadmin/forgetpassword', 
				data: {'emailvalue' : getemail},
				success: function(response){ 
					console.log(response);
					$('#errorMessage').html(response); 
					var getresponse= $('#errorMessage');
					getresponse.show().html(response); 					
					setTimeout(function(){getresponse.hide().html('response');}, 10000)
				} 
			});
		}
		else { 			
			$('#errorMessage').show().html('Please enter valid email address');
			$('#errorMessage').delay(3000).fadeOut(400);
			return false;
		}
	});
	
	
	
});


$("input[name=my_card_type]").change(function () {
    //alert(this.value);
    if(this.value == "1")
    {
         $('.new_card').hide();
           $('.saved_card').show();
    }
    else
    {
          $('.new_card').show();
          $('.saved_card').hide();
          $('.enter_cvv').hide();
          document.getElementById('my_card_value').value = '';
  
    }
});

//=========signup form phone number verification=============================
	function verifyPhoneNumber() {
		
		var phoneNumber = document.getElementById('phone_number').value;
		var validPhone = new RegExp('^[0-9]{10}$');
		var valid = validPhone.test(phoneNumber);
		$('.loading').show(); 	 
		if(valid == true){
			
			$.ajax({ 				
				type: 'post',
				url: "<?php echo base_url()?>frontend/verify_phone_number",
				data: {'phoneNumber' : phoneNumber},
				success: function(response){
					
					var json_obj = $.parseJSON(response);//parse JSON
	                var redirect_rule =json_obj.redirect_rule;
		            
		           // alert(redirect_rule);
		           
		            if(redirect_rule==1){
						//exist on portaone and exist on db
						 $('.loading').hide();
						 $('.option_div').show();
						 $('.phone_div').hide();
						 
						
		               return false;
					}else if(redirect_rule==2){
						//exist on portaone but not in db	
				
						 $('.loading').hide();
						 $('.ask_for_otp').show();
						 $('.phone_div').hide();
						 return false;
						
						
					}else if(redirect_rule==3){
						//exist on db but not in portaone
						
						 $('.loading').hide();
						 $('.ask_for_otp').show();
						 $('.phone_div').hide();
						 return false;
					}else{
						//not in both means fresh customer
						 $('#successMessagePhone').show().html('You are verified , please continue with singup ');
						 document.getElementById('newPhone').setAttribute('value',phoneNumber);
						 $('.loading').hide();
						 $('.ask_for_otp').hide();
						 $('.phone_div').hide();
						  $('.hide-show-dive').show();
						 return false;
					}
				} 
			});
		}
		else { 			
			
			$('#errorMessagePhone').show().html('Please enter 10 digits valid phone number');
			$('#errorMessagePhone').delay(3000).fadeOut(400);
			$('.loading').hide();
			return false;
		}
	}
	//===========================================================================
	
	
	//=========Verify OTP=============================
	function verifyOTP() {
		
		var otp = document.getElementById('otp').value;
		var phone_number = document.getElementById('phone_number').value;
		var validOTP = new RegExp('^[0-9]{4}$');
		var valid = validOTP.test(otp);
		$('.loading').show(); 	 
		if(valid == true){
			
			$.ajax({ 				
				type: 'post',
				url: "<?php echo base_url()?>frontend/verify_OTP",
				data: {'otpPhone' :phone_number,'otpString':otp},
				success: function(response){
					
					var json_obj = $.parseJSON(response);//parse JSON
	                var status =json_obj.status;
		          
		            if(status=='true'){
		            	$('#successMessagePhone').show().html('Your phone verified successfully , Please continue with singup !');
						$('#successMessagePhone').delay(5000).fadeOut(400);
						$('.loading').hide();
						$("#newPhone").val(phone_number);
						 $('.otp_class').hide();
						 $('.phone_div').hide();
						$('.hide-show-dive').show();
						
					}else{
						$('#errorMessagePhone').show().html('Unable to verify OTP, try after sometime!');
						$('#errorMessagePhone').delay(3000).fadeOut(400);
						$('.loading').hide();
						return false;
					}
				} 
			});
		}
		else { 			
			
			$('#errorMessagePhone').show().html('Please enter 4 digits valid OTP');
			$('#errorMessagePhone').delay(3000).fadeOut(400);
			$('.loading').hide();
			return false;
		}
	}
	//===========================================================================
	
		//=========send otp=============================
	function send_otp() {
		
		var phone_number = document.getElementById('phone_number').value;
        $('.loading').show(); 	 
         if(phone_number!=''){
         	 $.ajax({	type: 'post',
				url: "<?php //echo base_url()?>frontend/goToMyAccount",
				data: {'phone':phone_number},
				success: function(response){
					//alert(response);
					var json_obj1 = $.parseJSON(response);//parse JSON
					var smsStatus =json_obj1.smsStatus;	
					var pwd =json_obj1.pwd;	
					alert(pwd);
					$('.loading').hide(); 	
					if(smsStatus==true){
						 window.location="<?php echo base_url()?>vonecall-user-login/2";
						 return false;
					}else{
						window.location="<?php echo base_url()?>vonecall-user-login/2";
					}
				}
			});
         }else{
         	  window.location="<?php echo base_url()?>vonecall-user-login/1";
         }
		
	}
	//===========================================================================
	
	//=========check phone number on portaone=============================
	function verifyPhoneNumberPortaOne(val) {
		
	    var phoneNumberPortaOne = val;
	   
		var validPhone = new RegExp('^[0-9]{10}$');
		var valid = validPhone.test(phoneNumberPortaOne);
		$('.loading').show(); 	 
		if(valid == true){
			
			$.ajax({ 				
				type: 'post',
				url: "<?php echo base_url()?>frontend/verify_phone_number_porta_one",
				data: {'phoneNumber' : phoneNumberPortaOne},
				success: function(response){
					
					var json_obj = $.parseJSON(response);//parse JSON
					
					
					
	                var redirect_rule =json_obj.redirect_rule;
		           
		            if(redirect_rule==1){
						// var vonecall =json_obj.vonecall;
		                // var customerId =vonecall.customerID;
						 window.location="<?php //echo base_url()?>vonecall-recharge-phone/"+phoneNumberPortaOne;
						//$('#successMessagePhonePorta').show().html('You are existing Pinless customer, Please continue with payment !');
						//$('#successMessagePhonePorta').delay(5000).fadeOut(400);
						
						$('.loading').hide();
						return false;
		                 
						 //alert(customerId);
					}else if(redirect_rule==2){
						
						
		                 $('#successMessagePhonePorta').show().html('Phone number verified , please continue with payment');
						 $('#successMessagePhonePorta').delay(5000).fadeOut(400);
						 $('.loading').hide();
						 return false;
		                
						
					}else{
						$('#errorMessagePhonePorta').show().html('Invalid phone number !');
						$('#errorMessagePhonePorta').delay(3000).fadeOut(400);
						$('.loading').hide();
						return false;
					}
				} 
			});
		}
		else { 			
			
			$('#errorMessagePhonePorta').show().html('Please enter 10 digits valid phone number');
			$('#errorMessagePhonePorta').delay(3000).fadeOut(400);
			$('.loading').hide();
			return false;
		}
	}
	//===========================================================================
	
	 
function getval(sel) {
      
       $('#enter_cvv').show();
     }
	

/**
*   I don't recommend using this plugin on large tables, I just wrote it to make the demo useable. It will work fine for smaller tables 
*   but will likely encounter performance issues on larger tables.
*
*		<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
*		$(input-element).filterTable()
*		
*	The important attributes are 'data-action="filter"' and 'data-filters="#table-selector"'
*/
(function(){
    'use strict';
	var $ = jQuery;
	$.fn.extend({
		filterTable: function(){
			return this.each(function(){
				$(this).on('keyup', function(e){
					$('.filterTable_no_results').remove();
					var $this = $(this), 
                        search = $this.val().toLowerCase(), 
                        target = $this.attr('data-filters'), 
                        $target = $(target), 
                        $rows = $target.find('tbody tr');
                        
					if(search == '') {
						$rows.show(); 
					} else {
						$rows.each(function(){
							var $this = $(this);
							$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
						})
						if($target.find('tbody tr:visible').size() === 0) {
							var col_count = $target.find('tr').first().find('td').size();
							var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
							$target.find('tbody').append(no_results);
						}
					}
				});
			});
		}
	});
	$('[data-action="filter"]').filterTable();
})(jQuery);

$(function(){
    // attach table filter plugin to inputs
	$('[data-action="filter"]').filterTable();
	
	$('.container').on('click', '.panel-heading span.filter', function(e){
		var $this = $(this), 
			$panel = $this.parents('.panel');
		
		$panel.find('.panel-body').slideToggle();
		if($this.css('display') != 'none') {
			$panel.find('.panel-body input').focus();
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
})
</script>
<script>
$(document).ready(function(){
	

 	$('#submitButton').click(function(e) { 		
	   e.preventDefault();
	   var countryCode = ($('input[name=countryCode]').val());
	   var phoneNumber = ($('input[name=phoneNumber]').val());
	   <?php if(isset($rechargeAmount)){?>	
	   var amount = ($('input[name=amount]').val());
	   <?php }else{?>
	   var amountSKU = ($('select[name=amount]').val());
	   var rechargeAmount = amountSKU.split('-');
	   var amount = rechargeAmount[0];
	   <?php }?>
	   if((phoneNumber && amount) !=''){
			if(confirm('You are about to recharge phone number +'+countryCode+phoneNumber+' with amount $'+amount)){
				$('.loading').show();	
				$("#topup_form").submit(); 
				return true;
			}else{
				return false;
			}	
		}
		$("#topup_form").submit(); 
	}); 

    $("#topup_form").validate({
    	rules: {
			phoneNumber:{
				required: true,
				minlength:10,
				number: true
			},
			<?php if(isset($rechargeAmount)){?>				
			amount:{
				required: true,
				range: [ <?php echo $results->vproductMinAmount?>, <?php echo $results->vproductmaxAmount?>]
			},
			<?php }else{?>
			amount:{
				required: true,
			},	
			<?php }?>						
		},
		messages: {
			phoneNumber: {
				required: "This field can not be empty",
				minlength: "The length of phone should be <?php echo $results->vLocalPhoneNumberLength?> character long",
				number: "Please enter valid phone number",
			},					
        },
	});

 });
</script>
</footer>

</div>
</body>

</html>
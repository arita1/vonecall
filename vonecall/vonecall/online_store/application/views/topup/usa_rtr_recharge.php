<?php include APPPATH.'views/_header.php';?>
<style>
	.countryFlag span {
		float: left;
		width:100%;
		font-weight: bold;
		font-size: 14px;
	}
	.countryFlag{
		min-height: 120px;
	}
	.error {
	    color: red !important;
	    float: left !important;
	}
	.message{
		width:100%;
	}
	.box_makepayment label {	    
	    width: 340px;
	}
</style>
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

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('recharge_point');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
        <div class="box_makepayment ">
        	
        	<!-- Loading Image -->
		    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
	    
	        <div class="countryBox">
	        	<div class="flag">
	            	 <div class="countryFlag">
	            	 	<img width="150" height="90" alt="<?php echo $results->vproductVendor ?>" src="<?php echo $this->config->item('base_url')?>systemadmin/public/uploads/product_logo/<?php echo $results->logoName;?>">
	            	 	<span> <?php echo $results->vproductName; echo isset($rechargeAmount)?' ('.$rechargeAmount.')':''
	            	 		?> </span>
	            	 </div> 
	            </div>            
	        </div>
	    
            <?php echo form_open_multipart(site_url('topup-usa-rtr-recharge/'.$results->ppnProductID), array('id'=>'topup_form', 'name'=>'topup_form'));?>
            <label class="label1"> Mobile Number:</label>
            <span style="float: left;">+<?php echo $results->CountryCode?></span> 
            <input type="text" name="phoneNumber" maxlength="<?php echo $results->vLocalPhoneNumberLength?>" value="<?php echo isset($phoneNumber)?$phoneNumber:''?>">
            <input type="hidden" name="countryCode" value="<?php echo $results->CountryCode?>">
            <?php if (isset($error['phoneNumber'])) {?><span class="p155l red_color"><?php echo $error['phoneNumber'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">Amount:</label>
            <?php if(isset($recharge_amount)){
            	echo form_dropdown('amount', $recharge_amount, (isset($amount)?$amount:''), 'class="w162 float_left" id="amount"');?>
            <?php }else{ ?>
            	<input type="text" name="amount" value="<?php echo isset($amount)?$amount:''?>" placeholder="<?php echo $rechargeAmount?>" />
            	<input type="hidden" name="skuID" value="<?php echo isset($skuID)?$skuID:''?>" />
            <?php }?>
            <?php if (isset($error['amount'])) {?><span class="p155l red_color"><?php echo $error['amount'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">Sender Mobile:</label>
            <input type="text" name="senderPhone" value="<?php echo isset($senderPhone)?$senderPhone:''?>">
            <?php if (isset($error['senderPhone'])) {?><span class="p155l red_color"><?php echo $error['senderPhone'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <div class="p155l p10b">
            	<a class="bt_submit4 float_left" href="javascript:void(0);" id="submitButton"> Charge </a>
            </div>
            <?php echo form_close();?>
           
        </div>
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>

<?php include APPPATH.'views/_footer.php';?>
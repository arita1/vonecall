<?php include APPPATH.'views/_header.php';?>
<style>
.box_makepayment .label1 {
    margin: 3px;
    max-width: 300px;
    min-width: 220px;
   /* padding: 2px; */
}
.box_makepayment { padding-left: 4px; padding-top: 15px; }
.sale_column_right { width: 480px; min-height: 395px; }
.sale_column_left { width: 470px !important; }
.font{ font-size: 14px }	
.bt_submit4 { float: right; margin-right: 30%; }	
.col_big{min-height: 410px;}
.pinless_form label {
   font-size: 14px; width: 190px !important;
}
.error{
    color: red !important;
    font-size: 12px !important;
}
</style>

<script>
$(document).ready(function(){
	$("#account_form").validate({
    	/*rules: {
			newBalance:{
				required: true,
				min: 2,
				number: true
			},			
		},
		messages: {
			newBalance:{
				min: 'Please enter minimum recharge amount $2',				
			},	
        },*/
	});

 });
</script>

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('pinless');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
    	
    	<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <div class="box_makepayment ">
        	<div class="sale_column_right" style="background-color: #fff;">        		
        		<div class="pinless_heading"> <h3> Add / Manage an account </h3> </div>
        		<div class="pinless_form">
		        	<?php echo form_open_multipart(site_url('customer/create_new_account'), array('id'=>'account_form', 'name'=>'account_form'));?>
		            <input type="hidden" name="phone" value="<?php echo $login;?>" />
		            <input type="hidden" name="promotionAmount" value="<?php echo $promoAmount;?>" />
		            
		            <label class="label">Phone Number:</label>
		            <label class="label1 font"><?php echo format_phone_number($login);?></label>
		            <div class="cb"></div>
		            <!--
		            <p class="label1 font" style="width: 730px;">
		            	<input type="radio" name="openingBalance" value="1" checked="checked" <?php echo isset($openingBalance)&&($openingBalance == 1)?'checked="checked"' : ''?> />&nbsp; &nbsp;
		            	Create the account with <?php echo format_price($promoAmount)?> free credit              	
		            </p>
		            <div class="cb"></div>
		            <p class="label1 font" style="height: auto; width: 100%; max-width: none;">
		            	<input type="radio" name="openingBalance" value="2" <?php echo isset($openingBalance)&&($openingBalance == 2)?'checked="checked"' : ''?>> &nbsp; &nbsp;
		            	Create the account with <?php echo format_price($promoAmount)?> free credit and transfer 
		            </p>
		            <p class="label1 font" style="height: auto; width: 40%; margin-left: 44% ">
		            	$<input type="text" name="newBalance" value="<?php echo isset($newBalance)?$newBalance:''?>" class="box_makepayment_txt"  disabled /> <br>
		            	<?php if(isset($error['newBalance'])){ ?> <label for="newBalance" generated="true" class="error"> <?php echo $error['newBalance']?> </label> <?php }?>
		            </p>
		            <div class="cb"></div>
		           -->
		            <label class="label"> Enter Amount: </label>
		            $<input type="text" name="newBalance" value="<?php echo isset($newBalance)?$newBalance:''?>" class="box_makepayment_txt" /> <br>
		            <?php if(isset($error['newBalance'])){ ?> <label for="newBalance" generated="true" class="error"> <?php echo $error['newBalance']?> </label> <?php }?>
		            <div class="cb"></div>
		            	
		            <label class="label"> Preferred Language </label>
		            <?php echo form_dropdown('accessLanguage', $option_language, (isset($accessLanguage)?$accessLanguage:''), 'class="w187 float_left"');?>
		            <div class="cb"></div>
		            
		            <label class="label2"> &nbsp; </label>
					<a class="bt_submit4 float_left" href="javascript:void(0)" onclick="confirmSubmit('<?php echo format_phone_number($login);?>');">Next</a>
					<div class="cb"></div>
					 <?php echo form_close();?> 
				</div>          
	            <div class="cb"></div>   
	        </div>
	        
	        <div class="sale_column_left">
	            <label class="label1"><?php echo $this->lang->line('store_code');?>:</label>
	            <label class="text_right"><?php echo substr($info->securityCode, -7);?></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('current_balance');?>:</label>
	            <label class="text_right"><?php echo format_price($info->balance);?></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('time_period');?>:</label>
	            <?php echo form_dropdown('time_period', $option_time_period, 'today', 'class="w187 float_left" onchange="calculate_agent(this.value);"');?>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('customer_sales');?>:</label>
	            <label class="text_right" id="agent_sale"></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('gross_commission');?>:</label>
	            <label class="text_right" id="agent_commission"></label>
	            <div class="cb"></div>
	        </div>  
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
</div>

<script>

<?php if(isset($openingBalance) && $openingBalance==2){ ?>
	$("input[type=radio][name='openingBalance']:checked").val();
	 $("input[name=newBalance]").attr("disabled", false);
<?php }?>

// COnfirm Function

$("input[name=openingBalance]").click(function() {
	$("input[name=newBalance]").attr("disabled", true);
    if ($("input[name=openingBalance]:checked").val() == 2) {
        $("input[name=newBalance]").attr("disabled", false);
    }
});

function confirmSubmit(phone){
	var openingType = ($('input[name=openingBalance]:checked').val());
	
	if(openingType == 2){  // If User Select 2nd radio box
		var openingBalance = ($('input[name=newBalance]').val());
		if(openingBalance != '' && openingBalance >= 2){
			if(confirm('You are about to add Phone number '+phone+' as your primary account')){
				$('.loading').show();
				$('#account_form').submit()
			}else{
				return false;
			}	
		}else if(openingBalance != '' && openingBalance < 2){
			alert('Please enter minimum recharge amount $2');
			return false;
		}else{
			alert('Please enter an amount');
			return false;
		}
	}else{				// If User Select 1st radio box
		if(confirm('You are about to add Phone number '+phone+' as your primary account')){
			$('.loading').show();
			$('#account_form').submit()
		}else{
			return false;
		}	
	}
	
}

// Calculate Commission
function calculate_agent(time_period) {
	$.ajax({
		url: '<?php echo site_url('customer/calculate_agent');?>',
		type: 'POST',
		cache: false,
		data:{time_period:time_period},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				$("#agent_sale").html(data.Sale);
				//$("#agent_payment").html(data.Payment);
				$("#agent_commission").html(data.Commission);
			} else {
				alert('<?php echo $this->lang->line('please_try_again');?>');
			}
		},
		error: function () {
			alert('<?php echo $this->lang->line('ajax_cannot_get_store');?>');
		}
	});
	return;
}
calculate_agent('today');
</script>
<?php include APPPATH.'views/_footer.php';?>
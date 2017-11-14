<?php include '_header.php';?>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.box_makepayment .label1 {width: 200px;}
.btn_checkbox {margin-left:205px; width: 330px;}
.box_makepayment .label_short1 {width: 80px;}
.add_phone_text3 {width: 39px;}
</style>
<?php } else {?>
<style>
.box_makepayment .label1 {width: 160px;}
</style>
<?php } 
?>

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('payment');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
        <div class="box_makepayment ">
            <?php echo form_open_multipart(site_url('payment'), array('id'=>'payment_form', 'name'=>'payment_form'));?>
            
            <?php if (isset($error['amount'])) {?><span class="p155l red_color"><?php echo $error['amount'];?></span><div class="cb"></div><?php }?>
            <label class="label1"><?php echo $this->lang->line('amount');?>:<span class="red_color">*</span></label>
            <input name="amount" value="<?php echo isset($amount)?$amount:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
            <div class="cb"></div>
            
            <!-- start creadit card form -->
            
            <?php if (isset($error['savedCard'])) {?><span class="p155l red_color"><?php echo $error['savedCard'];?></span><div class="cb"></div><?php }?>
            <label class="label1"> <?php echo $this->lang->line('use_previous_creditcard');?>: </label>
            <?php echo form_dropdown('savedCard', $paymentProfiles, (isset($savedCard)?$savedCard:''), 'class="w132 float_left" onchange="use_previous_creditcard(this.value);" style="width: 280px !important;"');?>
            <div class="cb"></div>
            
            <div class="newCard">           
	            <?php if (isset($error['card_number'])) {?><span class="p155l red_color"><?php echo $error['card_number'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('credit_card_number');?>:<span class="red_color">*</span></label>
	            <input name="card_number" value="<?php echo isset($card_number)?$card_number:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_exp'])) {?><span class="p155l red_color"><?php echo $error['card_exp'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('expiration_date');?>:<span class="red_color">*</span></label>
	            <?php echo form_dropdown('card_exp_month', $option_month, (isset($card_exp_month)?$card_exp_month:''), 'class="w132 float_left"');?>
	            <?php echo form_dropdown('card_exp_year', $option_year, (isset($card_exp_year)?$card_exp_year:''), 'class="w132 float_left m101"');?>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_cvv'])) {?><span class="p155l red_color"><?php echo $error['card_cvv'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('cvv_code');?>:<span class="red_color">*</span></label>
	            <input name="card_cvv" value="<?php echo isset($card_cvv)?$card_cvv:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_name'])) {?><span class="p155l red_color"><?php echo $error['card_name'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('name_on_credit_card');?>:</label>
	            <input name="card_name" value="<?php echo isset($card_name)?$card_name:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <a class="btn_checkbox" href="javascript:void(0);" onclick="same_address();"><?php echo $this->lang->line('click_same_info');?></a>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_address'])) {?><span class="p155l red_color"><?php echo $error['card_address'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('address');?>:</label>
	            <input name="card_address" value="<?php echo isset($card_address)?$card_address:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_city'])) {?><span class="p155l red_color"><?php echo $error['card_city'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('city');?>:</label>
	            <input name="card_city" value="<?php echo isset($card_city)?$card_city:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['card_state'])) {?><span class="p155l red_color"><?php echo $error['card_state'];?></span><div class="cb"></div><?php }?>
	            <?php if (isset($error['card_zipcode'])) {?><span class="p155l red_color"><?php echo $error['card_zipcode'];?></span><div class="cb"></div><?php }?>
	            <label class="label1"><?php echo $this->lang->line('state');?>: </label>
	            <?php echo form_dropdown('card_state', $option_state, (isset($card_state)?$card_state:''), 'class="w132 float_left"');?>
	            <label class="label_short1 float_left"><?php echo $this->lang->line('zip');?></label>
	            <input name="card_zipcode" value="<?php echo isset($card_zipcode)?$card_zipcode:'';?>" class="add_phone_text3 float_left" type="text"/>
	            <div class="cb"></div>
	            
	            <?php if (isset($error['saveCard'])) {?><span class="p155l red_color"><?php echo $error['saveCard'];?></span><div class="cb"></div><?php }?>
	            <label class="label1" style="width: 170px">
	            	<span><?php echo $this->lang->line('store_card_details');?> </span>
	            </label>
	            <input name="saveCard" value="Y" class="box_makepayment_txt float_left" style="width: 40px;" type="checkbox" <?php echo isset($saveCard)?'checked="checked"':'';?> checked="checked" />
	            <div class="cb"></div>
	            <!-- end creadit card form -->
            </div>
            <label class="label1">&nbsp;</label>
            <a class="bt_submit float_left" href="javascript:void(0);" onclick="confirmPayment();"><?php echo $this->lang->line('submit');?></a>
            <div class="cb"></div>
            <?php echo form_close();?>
          
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>
<script>

// Same Address ==================
function same_address() {
	$("input[name=card_address]").val('<?php echo $info->address;?>');
	$("input[name=card_city]").val('<?php echo $info->city;?>');
	$("select[name=card_state]").val('<?php echo $info->stateID;?>');
	$("input[name=card_zipcode]").val('<?php echo $info->zipCode;?>');
	return;
}

// check profile onload ============================
use_previous_creditcard($("select[name=savedCard]").val());
function use_previous_creditcard(id) {
	if(id){
		$('.newCard').hide();	
	}else{
		$('.newCard').show();
	}
	 
}

// Confirm Payment =================================
function confirmPayment(){
	var amount  = $("input[name=amount]").val();
	
	if($("select[name=savedCard]").val()){					// IF payment process with SAVED CARD
		var cardNum = $("select[name=savedCard] option:selected").text();		
		if(confirm('The amount of $'+amount+' will be withdrawn from the card '+cardNum+'.  Please Click "OK" for confirming the withdrawal and that you read the Terms and Conditions or click "Cancel" for cancel process.')){
			$('#payment_form').submit();
		}else{
			return false;
		}
	}else{													// IF payment process with NEW CARD
		var cardNum = $("input[name=card_number]").val();
		if(confirm('The amount of $'+amount+' will be withdrawn from the card '+cardNum.slice(-4)+'.  Please Click "OK" for confirming the withdrawal and that you read the Terms and Conditions or click "Cancel" for cancel process.')){
			$('#payment_form').submit();
		}else{
			return false;
		}	
	}	
	
}

</script>
<?php include '_footer.php';?>
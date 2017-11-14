<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">
	Your Current Balance:  <span style="color: #6F9C01;"><?php echo format_price($balance)?></span> 
  </div>
 <div class="bg_title_content">Make Payment</div>
  <div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['auth_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['auth_error'];?></li></ul></dd></dl><?php }?>  
  <?php echo form_open_multipart(site_url('payment'), array('id'=>'make_payment', 'name'=>'make_payment'))?>
  <table border="0" align="left" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2">Fields denoted by an asterisk (<span class="red_color">*</span>) are required. </td>
    </tr>
    <tr>
      <td>Amount ($)<span class="red_color">*</span></td>
      <td>
        <input type="text" name="chargedAmount" value="<?php echo isset($chargedAmount)?$chargedAmount:'';?>" class="w325"/>
        <?php if (isset($error['chargedAmount'])) {?><span class="red_color"><?php echo $error['chargedAmount'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Use Previous Credit Card </td>
      <td>
        <?php echo form_dropdown('savedCard', $paymentProfiles, (isset($savedCard)?$savedCard:''), 'class="w132 float_left" onchange="use_previous_creditcard(this.value);"');?>
        <?php if (isset($error['savedCard'])) {?><span class="p155l red_color"><?php echo $error['savedCard'];?></span><div class="cb"></div><?php }?>
      </td>
    </tr>
    <tr id="tr_card_number" class="newCard">
      <td>Credit Card Number<font color=red>*</font></td>
      <td>
      	<input name="card_number" id="card_number" value="<?php echo isset($card_number)?$card_number:'';?>" type="text" class="w325"/>
      	<?php if (isset($error['card_number'])) {?><span class="red_color"><?php echo $error['card_number'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_card_exp" class="newCard">
      <td>Expiration Date<font color=red>*</font></td>
      <td>
      	<?php echo form_dropdown('card_exp_month', $option_month, (isset($card_exp_month)?$card_exp_month:''), 'class="w162"');?>
      	<?php echo form_dropdown('card_exp_year', $option_year, (isset($card_exp_year)?$card_exp_year:''), 'class="w162"');?>
      	<?php if (isset($error['card_exp'])) {?><span class="red_color"><?php echo $error['card_exp'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_card_cvv" class="newCard">
      <td>CVV Code<font color=red>*</font></td>
      <td>
      	<input name="card_cvv" id="card_cvv" value="<?php echo isset($card_cvv)?$card_cvv:'';?>" type="text" class="w325"/>
      	<?php if (isset($error['card_cvv'])) {?><span class="red_color"><?php echo $error['card_cvv'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_card_name" class="newCard">
      <td>Name on Credit Card</td>
      <td>
      	<input name="card_name" value="<?php echo isset($card_name)?$card_name:'';?>" type="text" class="w325"/>
      	<?php if (isset($error['card_name'])) {?><span class="red_color"><?php echo $error['card_name'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_address" class="newCard">
      <td><?php echo $this->lang->line('address');?></td>
      <td>
      	<input type="text" name="card_address" value="<?php echo isset($card_address)?$card_address:'';?>" class="w325"/>
      	<?php if (isset($error['card_address'])) {?><span class="red_color"><?php echo $error['card_address'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_city" class="newCard">
      <td><?php echo $this->lang->line('city');?></td>
      <td>
      	<input type="text" name="card_city" value="<?php echo isset($card_city)?$card_city:'';?>" class="w325"/>
      	<?php if (isset($error['card_city'])) {?><span class="red_color"><?php echo $error['card_city'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_zipcode" class="newCard">
      <td><?php echo $this->lang->line('zipCode');?></td>
      <td>
      	<input type="text" name="card_zipcode" value="<?php echo isset($card_zipcode)?$card_zipcode:'';?>" class="w325"/>
      	<?php if (isset($error['card_zipcode'])) {?><span class="red_color"><?php echo $error['card_zipcode'];?></span><?php }?>
      </td>
    </tr>
    <tr id="tr_state" class="newCard">
      <td><?php echo $this->lang->line('state');?></td>
      <td>
      	<?php echo form_dropdown('card_state', $option_state, (isset($card_state)?$card_state:''), 'class="w327"');?>
      	<?php if (isset($error['card_state'])) {?><span class="red_color"><?php echo $error['card_state'];?></span><?php }?>
      </td>
    </tr>
    
    <tr>
      <td>Comment</td>
      <td><textarea class="w325" name="comment" cols="45" rows="5"><?php echo isset($comment)?$comment:'';?></textarea></td>
    </tr>
    <tr class="newCard">
      <td>Store this card for future use</td>
      <td><input name="saveCard" value="Y" class="box_makepayment_txt float_left" style="width: 40px;" type="checkbox" <?php echo isset($saveCard)?'checked="checked"':'';?> checked="checked" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <a class=" bt_check m5r" href="javascript:void(0);" onclick="$('#make_payment').submit();">Submit</a>
      </td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
</div>
<script type="text/javascript">
use_previous_creditcard($("select[name=savedCard]").val());

function use_previous_creditcard(id) {
	if(id){
		$('.newCard').hide();	
	}else{
		$('.newCard').show();
	}
	 
}
</script>
<?php include '_footer.php';?>

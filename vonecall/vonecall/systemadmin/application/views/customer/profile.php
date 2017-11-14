<script type="text/javascript">
	$(document).ready(function(){
		WireAutoTab('phone_1','phone_2', 3);
		WireAutoTab('phone_2','phone_3', 3);
	      
		$("#phone_1").keyup(function () {
			if($(this).val().match(/\d{3}/)){
					$(this).removeClass('tx_red');
				}
			});

			$("#phone_2").keyup(function () {
				if($(this).val().match(/\d{3}/)){
					$(this).removeClass('tx_red');
				}
			});

			$("#phone_3").keyup(function () {
				if($(this).val().match(/\d{4}/)){
					$(this).removeClass('tx_red');
				}
			});
	});
</script>
<div class="bg_title_content">Update Customer Information</div>
<div class="form_addcustomer p15t">
<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
<?php echo form_open_multipart(site_url('customer/profile/'.$customerID), array('id'=>'form_customer_edit', 'name'=>'form_customer_edit'))?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
  <tr>
    <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
  </tr>
  <!--  
  <tr>
    <td><?php echo $this->lang->line('alert');?></td>
    <td>
    	<?php echo form_dropdown('alert', $option_alert, (isset($alert)?$alert:''), 'class="w327 m5r float_left"');?>
    	<?php if (isset($error['alert'])) {?><span class="red_color"><?php echo $error['alert'];?></span><?php }?>
    </td>
  </tr>
  -->
  <tr>
    <td width="319"><?php echo $this->lang->line('firstName_require');?></td>
    <td width="905">
    	<input name="firstName" value="<?php echo isset($firstName)?$firstName:'';?>" type="text" class="w325" />
    	<?php if (isset($error['firstName'])) {?><span class="red_color"><?php echo $error['firstName'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('lastName_require');?></td>
    <td>
    	<input type="text" name="lastName" value="<?php echo isset($lastName)?$lastName:'';?>" class="w325" />
    	<?php if (isset($error['lastName'])) {?><span class="red_color"><?php echo $error['lastName'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td>Phone Number<span class="red_color">*</span></td>
    <td>
      <input type="text" name="phone1" id="phone_1" value="<?php echo isset($phone1)?$phone1:'';?>" class="w76 float_left" maxlength="3"/>
      <span style="float:left; display:inline-block; padding:3px 3px"> - </span>
      <input type="text" name="phone2" id="phone_2" value="<?php echo isset($phone2)?$phone2:'';?>" class="w76 float_left" maxlength="3"/>
      <span style="float:left; display:inline-block; padding:3px 3px"> - </span>
      <input type="text" name="phone3" id="phone_3" value="<?php echo isset($phone3)?$phone3:'';?>" class="w76 float_left m5r" maxlength="4"/>
      <a class="bt_check float_left" onclick="verify_phone_number();">Verify</a>
      <span id="notice_phone_number" class="red_color"><?php if (isset($error['phone'])) {?><?php echo $error['phone'];?><?php }?></span>
      
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('fax');?></td>
    <td>
    	<input type="text" name="fax" value="<?php echo isset($fax)?$fax:'';?>" class="w325" />
    	<?php if (isset($error['fax'])) {?><span class="red_color"><?php echo $error['fax'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('email');?></td>
    <td>
    	<input type="text" name="email" value="<?php echo isset($email)?$email:'';?>" class="w325" />
    	<?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('password');?></td>
    <td>
    	<input type="text" name="password" value="<?php echo isset($password)?$password:'';?>" class="w325" />
    	<?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('address');?></td>
    <td>
    	<input type="text" name="address" value="<?php echo isset($address)?$address:'';?>" class="w325" />
    	<?php if (isset($error['address'])) {?><span class="red_color"><?php echo $error['address'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('city');?></td>
    <td>
    	<input type="text" name="city" value="<?php echo isset($city)?$city:'';?>" class="w325" />
    	<?php if (isset($error['city'])) {?><span class="red_color"><?php echo $error['city'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('zipCode');?></td>
    <td>
    	<input type="text" name="zipCode" value="<?php echo isset($zipCode)?$zipCode:'';?>" class="w325" />
    	<?php if (isset($error['zipCode'])) {?><span class="red_color"><?php echo $error['zipCode'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('state');?></td>
    <td>
    	<?php echo form_dropdown('state', $option_state, (isset($state)?$state:''), 'class="w327 m5r float_left"');?>
    	<?php if (isset($error['state'])) {?><span class="red_color"><?php echo $error['state'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('same_as');?></td>
    <td><a class="bt_atatement_address" href="javascript:void(0);" onclick="same_address();return false;">Statement Address</a></td>
  </tr>
  <tr>
    <td><strong><?php echo $this->lang->line('statementAddress');?></strong></td>
    <td>
        <input type="text" name="statementAddress" value="<?php echo isset($statementAddress)?$statementAddress:'';?>" class="w325" />
    	<?php if (isset($error['statementAddress'])) {?><span class="red_color"><?php echo $error['statementAddress'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><strong><?php echo $this->lang->line('statementCity');?></strong></td>
    <td>
    	<input type="text" name="statementCity" value="<?php echo isset($statementCity)?$statementCity:'';?>" class="w325" />
    	<?php if (isset($error['statementCity'])) {?><span class="red_color"><?php echo $error['statementCity'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><strong><?php echo $this->lang->line('statementZipCode');?></strong></td>
    <td>
    	<input type="text" name="statementZipCode" value="<?php echo isset($statementZipCode)?$statementZipCode:'';?>" class="w325" />
    	<?php if (isset($error['statementZipCode'])) {?><span class="red_color"><?php echo $error['statementZipCode'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><strong><?php echo $this->lang->line('statementState');?></strong></td>
    <td>
    	<?php echo form_dropdown('statementState', $option_state, (isset($statementState)?$statementState:''), 'class="w327 m5r float_left"');?>
    	<?php if (isset($error['statementState'])) {?><span class="red_color"><?php echo $error['statementState'];?></span><?php }?>
    </td>
  </tr>
  <!--  
  <tr>
    <td>Reset Password</td>
    <td><a class="bt_atatement_address" href="javascript:void(0);" onclick="reset_password();return false;">Reset Password</a></td>
  </tr>
  -->
  <tr>
    <td>&nbsp;</td>
    <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_customer_edit').submit();">Save</a></td>
  </tr>
</table>
<?php echo form_close();?>
</div>
<script>
function same_address() {
	address = $("input[name=address]").val();
	$("input[name=statementAddress]").val(address);
	city = $("input[name=city]").val();
	$("input[name=statementCity]").val(city);
	zipCode = $("input[name=zipCode]").val();
	$("input[name=statementZipCode]").val(zipCode);
	state = $("select[name=state]").val();
	$("select[name=statementState]").val(state);
	return false;
}
function verify_phone_number() {
	if(check_phone_number("phone_1","phone_2","phone_3")) {
		phone_1 = $('#phone_1').val();
		phone_2 = $('#phone_2').val();
		phone_3 = $('#phone_3').val();
		$.ajax({
			url: '<?php echo site_url('customer/verify_phone_number');?>/<?php echo $customerID;?>',
			type: 'POST',
			cache: false,
			data:{phone_number:phone_1+phone_2+phone_3},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					$("#notice_phone_number").attr("class", "blu_color");
				} else {
					$("#notice_phone_number").attr("class", "red_color");
				}
				$('#notice_phone_number').html(data.text);
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}
function reset_password() {
	if (confirm("Are you sure want to reset password?")) {
		$.ajax({
			url: '<?php echo site_url('customer/reset_password/'.$customerID);?>',
			type: 'POST',
			cache: false,
			data:{},
			dataType: "json",
			success: function(data) {
				alert(data.text);
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}

</script>
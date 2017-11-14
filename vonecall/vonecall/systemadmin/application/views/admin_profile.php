<div class="bg_title_content">Update Store Information</div>
<div class="form_addcustomer p15t">
<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>

<?php echo form_open_multipart(site_url('store/profile/'.$agentID), array('id'=>'form_agent_edit', 'name'=>'form_agent_edit'))?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
  <tr>
    <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
  </tr>
  <tr>
    <td width="319">Store Name</td>
    <td width="905">
    	<input name="storeName" value="<?php echo isset($storeName)?$storeName:'';?>" class="w325" name="" type="text" />
      	<?php if (isset($error['storeName'])) {?><span class="red_color"><?php echo $error['storeName'];?></span><?php }?>
    </td>
  </tr>
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
    <td><?php echo $this->lang->line('phone_number');?></td>
    <td>
    	<input type="text" name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="w325" />
    	<?php if (isset($error['phone'])) {?><span class="red_color"><?php echo $error['phone'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('cell_phone');?></td>
    <td>
    	<input type="text" name="cellPhone" value="<?php echo isset($cellPhone)?$cellPhone:'';?>" class="w325" />
    	<?php if (isset($error['cellPhone'])) {?><span class="red_color"><?php echo $error['cellPhone'];?></span><?php }?>
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
    <td><?php echo $this->lang->line('email');?></td>
    <td>
    	<input type="text" name="email" value="<?php echo isset($email)?$email:'';?>" class="w325" />
    	<?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td>Password</td>
    <td>
    	<input type="text" name="password" value="<?php echo isset($password)?$password:'';?>" class="w325" />
    	<?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td>Distributor</td>
    <td>
    	 <?php echo form_dropdown('distributor', $option_account_rep, (isset($distributor)?$distributor:''), 'class="w327 m5r float_left"');?>
    	<?php if (isset($error['distributor'])) {?><span class="red_color"><?php echo $error['distributor'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->lang->line('notes');?></td>
    <td>
    	<input type="text" name="note" value="<?php echo isset($note)?$note:'';?>" class="w325" />
    	<?php if (isset($error['note'])) {?><span class="red_color"><?php echo $error['note'];?></span><?php }?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_agent_edit').submit();">Update</a></td>
  </tr>
</table>
<?php echo form_close();?>
</div>
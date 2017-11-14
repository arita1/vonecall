<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Add New Store</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('add-new-store'), array('id'=>'add_new_agent', 'name'=>'add_new_agent'));?>
      <input type="hidden" name="edit" value="0" />
      <label>Store Name<span class="red_color">*</span></label>
      <input name="storeName" value="<?php echo isset($storeName)?$storeName:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['storeName'])) {?><span class="red_color"><?php echo $error['storeName'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Owner First Name</label>
      <input name="firstName" value="<?php echo isset($firstName)?$firstName:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['firstName'])) {?><span class="red_color"><?php echo $error['firstName'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Owner Last Name</label>
      <input name="lastName" value="<?php echo isset($lastName)?$lastName:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['lastName'])) {?><span class="red_color"><?php echo $error['lastName'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Phone Number</label>
      <input name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['phone'])) {?><span class="red_color"><?php echo $error['phone'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Cell Phone</label>
      <input name="cellphone" value="<?php echo isset($cellphone)?$cellphone:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['cellphone'])) {?><span class="red_color"><?php echo $error['cellphone'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Address</label>
      <input name="address" value="<?php echo isset($address)?$address:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['address'])) {?><span class="red_color"><?php echo $error['address'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>City</label>
      <input name="city" value="<?php echo isset($city)?$city:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['city'])) {?><span class="red_color"><?php echo $error['city'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>State</label>
      <?php echo form_dropdown('state', $option_state, (isset($state)?$state:''), 'class="w327"');?>
      <?php if (isset($error['state'])) {?><span class="red_color"><?php echo $error['state'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>ZipCode</label>
      <input name="zipCode" value="<?php echo isset($zipCode)?$zipCode:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['zipCode'])) {?><span class="red_color"><?php echo $error['zipCode'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Email<span class="red_color">*</span></label>
      <input name="email" value="<?php echo isset($email)?$email:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>UserName<span class="red_color">*</span></label>
      <input name="agentLoginID" value="<?php echo isset($agentLoginID)?$agentLoginID:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['agentLoginID'])) {?><span class="red_color"><?php echo $error['agentLoginID'];?></span><?php }?>
      <div class="cb"></div>
      
      <label><?php echo $this->lang->line('password');?><span class="red_color">*</span></label>
      <input name="agentPassword" value="<?php echo isset($agentPassword)?$agentPassword:'';?>" class="w325" name="" type="password" />
      <?php if (isset($error['agentPassword'])) {?><span class="red_color"><?php echo $error['agentPassword'];?></span><?php }?>
      <div class="cb"></div>
      
      <label><?php echo $this->lang->line('passwordConfirm');?><span class="red_color">*</span></label>
      <input name="agentPasswordConfirm" value="<?php echo isset($agentPasswordConfirm)?$agentPasswordConfirm:'';?>" class="w325" name="" type="password" />
      <?php if (isset($error['agentPasswordConfirm'])) {?><span class="red_color"><?php echo $error['agentPasswordConfirm'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Notes</label>
      <input name="note" value="<?php echo isset($note)?$note:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['note'])) {?><span class="red_color"><?php echo $error['note'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#add_new_agent').submit();">Add New Store</a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
</div>
<?php include '_footer.php';?>
<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Add New Distributor</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('add-new-destributor'), array('id'=>'add_new_destributor', 'name'=>'add_new_destributor'));?>
        
      <label>Company Name<span class="red_color">*</span></label>
      <input name="company_name" value="<?php echo isset($company_name)?$company_name:'';?>" class="w325" type="text" />
      <?php if (isset($error['company_name'])) {?><span class="red_color"><?php echo $error['company_name'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>First Name<span class="red_color">*</span></label>
      <input name="firstName" value="<?php echo isset($firstName)?$firstName:'';?>" class="w325" type="text" />
      <?php if (isset($error['firstName'])) {?><span class="red_color"><?php echo $error['firstName'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Last Name<span class="red_color">*</span></label>
      <input name="lastName" value="<?php echo isset($lastName)?$lastName:'';?>" class="w325" type="text" />
      <?php if (isset($error['lastName'])) {?><span class="red_color"><?php echo $error['lastName'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Address<span class="red_color">*</span></label>
      <input name="address" value="<?php echo isset($address)?$address:'';?>" class="w325" type="text" />
      <?php if (isset($error['address'])) {?><span class="red_color"><?php echo $error['address'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Address 2</label>
      <input name="address2" value="<?php echo isset($address2)?$address2:'';?>" class="w325" type="text" />
      <?php if (isset($error['address2'])) {?><span class="red_color"><?php echo $error['address2'];?></span><?php }?>
      <div class="cb"></div>
       
      <label>City<span class="red_color">*</span></label>
      <input name="city" value="<?php echo isset($city)?$city:'';?>" class="w325" type="text" />
      <?php if (isset($error['city'])) {?><span class="red_color"><?php echo $error['city'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>State<span class="red_color">*</span></label>
      <?php echo form_dropdown('state', $option_state, (isset($state)?$state:''), 'class="w327"');?>
      <?php if (isset($error['state'])) {?><span class="red_color"><?php echo $error['state'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Zip<span class="red_color">*</span></label>
      <input name="zip" value="<?php echo isset($zip)?$zip:'';?>" class="w325" type="text" />
      <?php if (isset($error['zip'])) {?><span class="red_color"><?php echo $error['zip'];?></span><?php }?>
      <div class="cb"></div>
            
      <label>Phone Number<span class="red_color">*</span></label>
      <input name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="w325" type="text" />
      <?php if (isset($error['phone'])) {?><span class="red_color"><?php echo $error['phone'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Cell Phone Number<span class="red_color">*</span></label>
      <input name="cellphone" value="<?php echo isset($cellphone)?$cellphone:'';?>" class="w325" type="text" />
      <?php if (isset($error['cellphone'])) {?><span class="red_color"><?php echo $error['cellphone'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Email<span class="red_color">*</span></label>
      <input name="email" value="<?php echo isset($email)?$email:'';?>" class="w325" type="text" />
      <?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Login Name<span class="red_color">*</span></label>
      <input name="agentLoginID" value="<?php echo isset($agentLoginID)?$agentLoginID:'';?>" class="w325" type="text" />
      <?php if (isset($error['agentLoginID'])) {?><span class="red_color"><?php echo $error['agentLoginID'];?></span><?php }?>
      <div class="cb"></div>
      
      <label><?php echo $this->lang->line('password');?><span class="red_color">*</span></label>
      <input name="agentPassword" value="<?php echo isset($agentPassword)?$agentPassword:'';?>" class="w325" type="password" />
      <?php if (isset($error['agentPassword'])) {?><span class="red_color"><?php echo $error['agentPassword'];?></span><?php }?>
      <div class="cb"></div>
      
      <label><?php echo $this->lang->line('passwordConfirm');?><span class="red_color">*</span></label>
      <input name="agentPasswordConfirm" value="<?php echo isset($agentPasswordConfirm)?$agentPasswordConfirm:'';?>" class="w325" type="password" />
      <?php if (isset($error['agentPasswordConfirm'])) {?><span class="red_color"><?php echo $error['agentPasswordConfirm'];?></span><?php }?>
      <div class="cb"></div>
            
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#add_new_destributor').submit();">Submit</a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
</div>
<?php include '_footer.php';?>
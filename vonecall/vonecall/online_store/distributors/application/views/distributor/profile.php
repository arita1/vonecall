<div class="bg_title_content">Update Sub-distributor Information</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('distributor/profile/'.$agentID), array('id'=>'form_distributor', 'name'=>'form_distributor'))?>
  <input type="hidden" name="edit" value="1" />
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
    </tr>
    
    <tr>
      <td width="319">Company Name</td>
      <td width="905">
        <input name="company_name" value="<?php echo isset($company_name)?$company_name:'';?>" class="w325" type="text" />
        <?php if (isset($error['company_name'])) {?><span class="red_color"><?php echo $error['company_name'];?></span><?php }?>
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
      <td>Address</td>
      <td>
        <input name="address" value="<?php echo isset($address)?$address:'';?>" class="w325" type="text" />
        <?php if (isset($error['address'])) {?><span class="red_color"><?php echo $error['address'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Address 2</td>
      <td>
        <input name="address2" value="<?php echo isset($address2)?$address2:'';?>" class="w325" type="text" />
        <?php if (isset($error['address2'])) {?><span class="red_color"><?php echo $error['address2'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>City</td>
      <td>
        <input name="city" value="<?php echo isset($city)?$city:'';?>" class="w325" type="text" />
        <?php if (isset($error['city'])) {?><span class="red_color"><?php echo $error['city'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>State</td>
      <td>
         <?php echo form_dropdown('state', $option_state, (isset($state)?$state:''), 'class="w327"');?>
        <?php if (isset($error['state'])) {?><span class="red_color"><?php echo $error['state'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Zip</td>
      <td>
        <input name="zip" value="<?php echo isset($zip)?$zip:'';?>" class="w325" type="text" />
        <?php if (isset($error['zip'])) {?><span class="red_color"><?php echo $error['zip'];?></span><?php }?>
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
      <td>Cell Phone Number</td>
      <td>
        <input name="cellphone" value="<?php echo isset($cellphone)?$cellphone:'';?>" class="w325" type="text" />
        <?php if (isset($error['cellphone'])) {?><span class="red_color"><?php echo $error['cellphone'];?></span><?php }?>
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
    	<input type="password" name="password" value="" class="w325" placeholder="<?php echo isset($password)?$password:'';?>" />
    	<?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
      </td>
    </tr>   
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_distributor').submit();">Update</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
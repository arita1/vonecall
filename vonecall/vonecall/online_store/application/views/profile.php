<?php include '_header.php';?>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('update_profile');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <!--  
      <div class=" p13l p12t p10r">
        <div class="box_info">
          <table width="32%"   border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="51%" class="bd_r"><?php echo $this->lang->line('credit_balance');?>: <span class="ogr_color"><strong><?php echo format_price($info->balance);?></strong></span></td>
              <td width="49%" align="left" class="p20l"><?php echo $this->lang->line('store_code');?>: <span class="ogr_color"><strong><?php echo substr($info->securityCode, -7);?></strong></span></td>
            </tr>
          </table>
        </div>
        <br />
      </div>
      -->
      <div class="box_makepayment ">
        <?php echo form_open_multipart(site_url('profile'), array('id'=>'profile_form', 'name'=>'profile_form'));?>
        <!--  
        <?php if (isset($error['firstName'])) {?><span class="p190l red_color"><?php echo $error['firstName'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('first_name');?>:</label>
        <input name="firstName" value="<?php echo isset($firstName)?$firstName:'';?>" type="text" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['lastName'])) {?><span class="p190l red_color"><?php echo $error['lastName'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('last_name');?>:</label>
        <input type="text" name="lastName" value="<?php echo isset($lastName)?$lastName:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['phone'])) {?><span class="p190l red_color"><?php echo $error['phone'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('telephone');?>:</label>
        <input type="text" name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['cellPhone'])) {?><span class="p190l red_color"><?php echo $error['cellPhone'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('cellphone');?>:</label>
        <input type="text" name="cellPhone" value="<?php echo isset($cellPhone)?$cellPhone:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['address'])) {?><span class="p190l red_color"><?php echo $error['address'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('address');?>:</label>
        <input type="text" name="address" value="<?php echo isset($address)?$address:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['city'])) {?><span class="p190l red_color"><?php echo $error['city'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('city');?>:</label>
        <input type="text" name="city" value="<?php echo isset($city)?$city:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['zipCode'])) {?><span class="p190l red_color"><?php echo $error['zipCode'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('zipcode');?>:</label>
        <input type="text" name="zipCode" value="<?php echo isset($zipCode)?$zipCode:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['state'])) {?><span class="p190l red_color"><?php echo $error['state'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('state');?>:</label>
        <?php echo form_dropdown('state', $option_state, (isset($state)?$state:''), 'class="w268"');?>
        <br class="cb">
        
        <?php if (isset($error['note'])) {?><span class="p190l red_color"><?php echo $error['note'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('note');?>:</label>
        <input type="text" name="note" value="<?php echo isset($note)?$note:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        -->
        
        <?php if (isset($error['email'])) {?><span class="p190l red_color"><?php echo $error['email'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('email');?>:</label>
        <input type="text" name="email" value="<?php echo isset($email)?$email:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['currentPassword'])) {?><span class="p190l red_color"><?php echo $error['currentPassword'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('current_password');?>:</label>
        <input type="password" name="currentPassword" value="<?php echo isset($currentPassword)?$currentPassword:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['agentPassword'])) {?><span class="p190l red_color"><?php echo $error['agentPassword'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('new_password');?>:</label>
        <input type="password" name="agentPassword" value="<?php echo isset($agentPassword)?$agentPassword:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['agentPasswordConfirm'])) {?><span class="p190l red_color"><?php echo $error['agentPasswordConfirm'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('confirm_password');?>:</label>
        <input type="password" name="agentPasswordConfirm" value="<?php echo isset($agentPasswordConfirm)?$agentPasswordConfirm:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <div class="p190l p5t p10b"> <a class="bt_submit" href="javascript:void(0);" onclick="$('#profile_form').submit();"><?php echo $this->lang->line('update');?></a></div>
        <?php echo form_close();?>
      </div>
    </div>
    <div class="cb"></div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
<?php include '_footer.php';?>
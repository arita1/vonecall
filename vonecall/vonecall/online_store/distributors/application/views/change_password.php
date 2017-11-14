<?php include '_header.php';?>
<div id="main" class="mh730">
  <div class="bg_title">Admin Change Password</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('change-password'), array('id'=>'admin_change_password', 'name'=>'admin_change_password'));?>
      <div class="cb"></div>
      <p class="p13t p13b">Fields denoted by an asterisk (<span class="red_color">*</span>) are required. </p>
      <label>Old Password:<span class="red_color">*</span></label>
      <input type="password" name="oldPassword" value="<?php echo isset($oldPassword)?$oldPassword:'';?>" class="w325" />
      <?php if (isset($error['oldPassword'])) {?><span class="red_color"><?php echo $error['oldPassword'];?></span><?php }?>
      <div class="cb"></div>
      <label>New Password:<span class="red_color">*</span></label>
      <input type="password" name="password" value="<?php echo isset($password)?$password:'';?>" class="w325" />
      <?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
      <div class="cb"></div>
      <label>Confirm Password:<span class="red_color">*</span></label>
      <input type="password" name="passwordConfirm" value="<?php echo isset($passwordConfirm)?$passwordConfirm:'';?>" class="w325" />
      <?php if (isset($error['passwordConfirm'])) {?><span class="red_color"><?php echo $error['passwordConfirm'];?></span><?php }?>
      <div class="cb"></div>
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#admin_change_password').submit();">Update</a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
</div>
<?php include '_footer.php';?>
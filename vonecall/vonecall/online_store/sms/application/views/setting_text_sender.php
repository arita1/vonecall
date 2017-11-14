<?php include '_header.php';?>
<div id="main" class="mh730">
  <div class="bg_title">Admin Change Password</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('text-sender'), array('id'=>'admin_text_sender', 'name'=>'admin_text_sender'));?>
      <div class="cb"></div>
      <p class="p13t p13b">Fields denoted by an asterisk (<span class="red_color">*</span>) are required. </p>
      <label>Select Provider:<span class="red_color">*</span></label>
      <select name="provider">
      	<?php foreach($option_senders as $key => $value ){ ?>
      		<option value="<?php echo $key?>" <?php echo ($key == $get_settings->settingParameter) ? 'selected="selected"' : '';?>> <?php echo $value;?> </option>
      	<?php }?>
      </select>     
      <?php if (isset($error['provider'])) {?><span class="red_color"><?php echo $error['provider'];?></span><?php }?>
      <div class="cb"></div>     
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#admin_text_sender').submit();">Update</a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
</div>

<?php include '_footer.php';?>
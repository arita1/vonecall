
<style>
	.w327 {
	    width: 155px;
	}
</style>

<div class="bg_title_content"> Text Message Settings </div>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	
	<?php echo form_open_multipart(site_url('text-message'), array('id'=>'text_message', 'name'=>'text_message'));?>
      <label> Data24-7 Username</label>
      <input type="text" name="username" value="<?php echo isset($username)?$username:''?>" />
      <?php if (isset($error['username'])) {?><span class="red_color"><?php echo $error['username'];?></span><?php }?>
      <div class="cb"></div>
      
      <label> Data24-7 Password</label>
      <input type="text" name="password" value="<?php echo isset($password)?$password:''?>" />
      <?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#text_message').submit();" >Submit</a>
      <div class="cb"></div>
      
      <?php echo form_close();?>
  </div>
</div>



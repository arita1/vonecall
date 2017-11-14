
<style>
	.w327 {
	    width: 155px;
	}
</style>

<div class="bg_title_content"> PPN Settings </div>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	
	<?php echo form_open_multipart(site_url('ppn-mode'), array('id'=>'ppn_mode', 'name'=>'ppn_mode'));?>
      <label>PPN Mode:</label>
	  <?php echo form_dropdown('ppnMode', $ppn_mode, (isset($ppnMode)?$ppnMode:''), 'class="w327" id="ppnMode"');?>
	  <?php if (isset($error['ppnMode'])) {?><span class="red_color"><?php echo $error['ppnMode'];?></span><?php }?>
	  <div class="cb"></div>
	                
      <label>PPN Username</label>
      <input type="text" name="username" value="<?php echo isset($username)?$username:''?>" />
      <?php if (isset($error['username'])) {?><span class="red_color"><?php echo $error['username'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>PPN Password</label>
      <input type="text" name="password" value="<?php echo isset($password)?$password:''?>" />
      <?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#ppn_mode').submit();" >Submit</a>
      <div class="cb"></div>
      
      <?php echo form_close();?>
  </div>
</div>



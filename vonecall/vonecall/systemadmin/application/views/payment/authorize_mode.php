<?php include APPPATH.'views/_header.php';?>
<style>
	.w327 {
	    width: 155px;
	}
</style>
<div id="main">
  <div class="bg_title">Authorize.net Mode</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('authorize-mode'), array('id'=>'authorize_mode', 'name'=>'authorize_mode'));?>
      <label>Authorize.net Mode:</label>
	  <?php echo form_dropdown('authMode', $auth_mode, (isset($authMode)?$authMode:''), 'class="w327" id="authMode"');?>
	  <?php if (isset($error['authMode'])) {?><span class="red_color"><?php echo $error['authMode'];?></span><?php }?>
	  <div class="cb"></div>
	                
      <label>Authorize.net Login Key</label>
      <input type="text" name="authLogin" value="<?php echo isset($authLogin)?$authLogin:''?>" />
      <?php if (isset($error['authLogin'])) {?><span class="red_color"><?php echo $error['authLogin'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>Authorize.net Secret Key</label>
      <input type="text" name="authSecret" value="<?php echo isset($authSecret)?$authSecret:''?>" />
      <?php if (isset($error['authSecret'])) {?><span class="red_color"><?php echo $error['authSecret'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#authorize_mode').submit();" >Submit</a>
      <div class="cb"></div>
      
      <?php echo form_close();?>
    </div>
    
  </div> 
</div>

<script>
function paging(num) {
	$('#reset').val(0);
	$('input[name=page]').val(num);
	$('#import_products').submit();
}

function confirmReset(){
	if(confirm('All the records of this Ratesheet will be deleted, would you like to proceed?')){ 		
		$('#reset').val(1);
		$('#import_products').submit();
	}else{
		return false;
	}
}

function confirmImport(){
	if(confirm('New records ready to import, would you like to proceed?')){ 		
		$('input[name=page]').val('')
		$('#import_products').submit();
	}else{
		return false;
	}
}
</script>

<?php include APPPATH.'views/_footer.php';?>

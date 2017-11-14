<?php include '_header.php';?>
<style>
	.w50{
		float: left;
	    width: 45%;
	}
</style>
<div id="main">
  <div class="bg_title">
  	<div style="border-right: 1px solid #d8d8d8; float: left; width: 47.3%;">Add Funds to Store</div>
  	<div style="width: 50%; float: right;">Add Funds to Distributor</div>
  </div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b w50"  style="border-right: 1px solid #d8d8d8;">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('add-funds'), array('id'=>'add_funds', 'name'=>'add_funds'));?>
      <input type="hidden" name="edit" value="0">
      <label>Store</label>
      <?php echo form_dropdown('store', $option_stores, (isset($store)?$store:''), 'class="w327"');?>
      <?php if (isset($error['store'])) {?><span class="red_color"><?php echo $error['store'];?></span><?php }?>
      <div class="cb"></div>
            
      <label>Amount</label>
      <input name="fundAmount" value="<?php echo isset($fundAmount)?$fundAmount:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['fundAmount'])) {?><span class="red_color"><?php echo $error['fundAmount'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#add_funds').submit();"> Submit </a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
  
  <div class="p20l p20r p13b w50">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('add-funds-to-distributor'), array('id'=>'add_funds_dist', 'name'=>'add_funds_dist'));?>
      <input type="hidden" name="edit" value="0">
      <label>Distributor </label>
      <?php echo form_dropdown('dist', $option_dist, (isset($dist)?$dist:''), 'class="w327"');?>
      <?php if (isset($error['dist'])) {?><span class="red_color"><?php echo $error['dist'];?></span><?php }?>
      <div class="cb"></div>
            
      <label>Amount</label>
      <input name="fundAmountDist" value="<?php echo isset($fundAmountDist)?$fundAmountDist:'';?>" class="w325" name="" type="text" />
      <?php if (isset($error['fundAmountDist'])) {?><span class="red_color"><?php echo $error['fundAmountDist'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#add_funds_dist').submit();"> Submit </a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
  </div>
</div>

<?php include '_footer.php';?>
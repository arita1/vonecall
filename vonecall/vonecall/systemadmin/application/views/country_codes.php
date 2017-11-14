<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Import Access Number</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('admin/import_country'), array('id'=>'import_country', 'name'=>'import_country'));?>
      <input type="hidden" name="export" id="export" value="0" />
                  
      <label>Upload File: (.csv)</label>
      <input type="file" name="country" />
      <?php if (isset($error['country'])) {?><span class="red_color"><?php echo $error['country'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#import_country').submit();">Import</a>

      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
    
  </div>
</div>
<?php include '_footer.php';?>
<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Upload Logo</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['image_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['image_error'];?></li></ul></dd></dl><?php }?>
  
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('product-logo-upload'), array('id'=>'upload_product_logo', 'name'=>'upload_product_logo'));?>
      <label>Product List:</label>
	  <?php echo form_dropdown('ppnProductID', $productOperator, (isset($ppnProductID)?$ppnProductID:''), 'class="w327" id="productType"');?>
	  <?php if (isset($error['ppnProductID'])) {?><span class="red_color"><?php echo $error['ppnProductID'];?></span><?php }?>
	  <div class="cb"></div>
	                
      <label>Upload Image: </label>
      <input type="hidden" name="import" value="1" />
      <input type="hidden" name="reset" id="reset" value="0" />
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/> 
      <input type="file" name="file" id="file" />
      <?php if (isset($error['products'])) {?><span class="red_color"><?php echo $error['products'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#upload_product_logo').submit();" >Upload</a>
      <div class="cb"></div>
      <br>
      <?php echo form_close();?>
    </div>
    <?php if(isset($results)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Operator </td>
          <td align="center" class="bg_table boder_right"> Country </td>
          <td align="center" class="bg_table boder_right"> Operator Logo </td>           
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->vproductVendor;?></td>
          <td class="boder_right"><?php echo $item->CountryName;?></td>
          <td class="boder_right"><img src="<?php echo $this->config->item('base_url')?>public/uploads/product_logo/<?php echo $item->logoName;?>" alt="<?php echo $item->CountryName;?>" width="200px" /></td>                 
        </tr>
        <?php $i++;?>
        <?php }?> 
        <?php } else {?>
        <tr>
          <td colspan="7"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
      <?php echo isset($paging)?$paging:'';?>             
    </div>
    <?php }?>
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

<?php include '_footer.php';?>
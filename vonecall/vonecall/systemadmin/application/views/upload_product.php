<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Import Rates</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('products-upload'), array('id'=>'import_products', 'name'=>'import_products'));?>
      <label>Product List:</label>
	  <?php echo form_dropdown('productType', $product_types, (isset($productType)?$productType:''), 'class="w327" id="productType"');?>
	  <?php if (isset($error['productType'])) {?><span class="red_color"><?php echo $error['productType'];?></span><?php }?>
	  <div class="cb"></div>
	                
      <label>Upload File: (.csv)</label>
      <input type="hidden" name="import" value="1" />
      <input type="hidden" name="reset" id="reset" value="0" />
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/> 
      <input type="file" name="products" id="products" />
      <?php if (isset($error['products'])) {?><span class="red_color"><?php echo $error['products'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="confirmImport()" >Import</a>
      <div class="cb"></div>
      <br>
      <label> Highly recommended before you upload new file </label>
      <a class="bt_save float_left" onclick="window.location.href='<?php echo site_url('export-products')?>';" href="javascript:void(0);" >Download</a><br>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
      
    <?php if(isset($getvProducts)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Product Name </td>
          <td align="center" class="bg_table boder_right"> Product List </td>
          <td align="center" class="bg_table boder_right"> Product Type </td>
          <td align="center" class="bg_table boder_right"> Product Category </td>
          <td align="center" class="bg_table boder_right"> Product Vendor </td>
          <td align="center" class="bg_table boder_right">  Total Commissions(%) </td>
          <td align="center" class="bg_table boder_right">  Admin Commission(%) </td>
          <td align="center" class="bg_table boder_right">  Destributor Commission(%) </td>
          <td align="center" class="bg_table boder_right">  Max Store Commission(%) </td>
          <td align="center" class="bg_table boder_right">  Min Store Commission(%) </td>
          <td align="center" class="bg_table boder_right">  SKU ID </td>
          <td align="center" class="bg_table boder_right">  Country </td>
          <td align="center" class="bg_table">  Effective Date </td>     
        </tr>
        <?php if(count($getvProducts)>0) { ?>
        <?php $i=1;?>
        <?php foreach($getvProducts as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->vproductName;?></td>
          <td class="boder_right"><?php echo $item->productName;?></td>
          <td class="boder_right"><?php echo $item->vproductType;?></td>
          <td class="boder_right"><?php echo $item->vproductCategory;?></td>
          <td class="boder_right"><?php echo $item->vproductVendor;?></td> 
          <td class="boder_right"><?php echo $item->vproducttotalCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductAdminCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductDistCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductMaxStoreCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductMinStoreCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductSkuID ? $item->vproductSkuID : 'NA';?></td> 
          <td class="boder_right"><?php echo $item->vproductCountryCode ? $item->CountryName : 'NA';?></td> 
          <td class=""><?php echo date('m-d-Y', strtotime($item->effectiveDate));?></td>         
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
    <p>    	
    	<a onclick="confirmReset()" href="javascript:void(0);" class="bt_atatement_address m7b">Reset</a>
    </p>
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
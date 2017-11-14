<div id="main">
  <div class="bg_title_content">Product List</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('product-list'), array('id'=>'commission_rate_form', 'name'=>'commission_rate_form'));?>
      	<input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>     	
      	<table width="80%" border="0" cellspacing="0" cellpadding="0">
	        <tr class="">
	          <td align="left" class=" "> Search Product By Name: </td>
	          <td align="left" class=" "> <?php echo form_dropdown('productName', $option_productName, (isset($productName)?$productName:''), 'class="w327"');?> </td>
	          <td align="left" class=" "> <a class="bt_check" href="javascript:void(0);" onclick="$('#page').val('');$('#commission_rate_form').submit();">Search
	          	</a> </td>
	        </tr>
	        <tr class="">
	        	<td colspan="3"> <a class="bt_atatement_address" href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('export-products')?>';" style="margin-bottom: 7px;">Download List</a> </td>
	        </tr>
	    </table>
      <?php echo form_close();?>
    </div>

    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Product Name</td>
          <td class="bg_table boder_right">Sku Name </td>
	      <!--<td class="bg_table boder_right">Product List</td>-->
	      <td class="bg_table boder_right">Product Type</td>
	      <td class="bg_table boder_right">Product Category</td>
	      <td class="bg_table boder_right">Total Commission % Rate</td>
	      <td class="bg_table boder_right">Minimum Store Commission (%)</td>
	      <td class="bg_table boder_right">Maximum Store Commission (%)</td>
        </tr>
        <?php if(count($results)>0) { //vproductSkuName echo '<pre>';print_r($results);?>
        <?php $i=1;foreach($results as $item) {?>
	          <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	           <td class="boder_right"><?php echo $item->vproductName;?></td>
	           <td class="boder_right"><?php echo $item->vproductSkuName;?></td>
		       <!--<td class="boder_right"><?php echo $item->productName;?></td>-->
		       <td class="boder_right"><?php echo $item->vproductType;?></td>
		       <td class="boder_right"><?php echo $item->vproductCategory;?></td>
		       <td class="boder_right"><?php echo $item->vproducttotalCommission - $item->vproductAdminCommission;?>%</td>
		       <td class="boder_right"><?php echo $item->vproductMinStoreCommission;?>%</td>
		       <td class="boder_right"><?php echo $item->vproductMaxStoreCommission;?>%</td>
	          </tr>
	    <?php $i++;}?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
       <?php  if(!isset($productName)) {
       		 echo isset($paging)?$paging:'';
	   }?>
    </div>    
<?php //}?>

  </div>
  <div class="cb"></div>
</div>

<script>
function paging(num) {
	$('input[name=page]').val(num);
	$('#commission_rate_form').submit();
}
</script>

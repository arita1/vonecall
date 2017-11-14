<div class="bg_title_content">Get Products By Carrier</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('ppn/product-list'), array('id'=>'form_product_list', 'name'=>'form_product_list'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Carrier</td>
		<td>
			<?php echo form_dropdown('carrierID', $carrierList, (isset($carrierID)?$carrierID:''), 'class="w327" id="id"');?>
			<?php if (isset($error['carrierID'])) {?><span class="red_color"><?php echo $error['carrierID'];?></span><?php }?>
		</td>
	</tr>
	
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_product_list').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($products)) {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">ID</td>	
      <td align="center" class="bg_table boder_right">SKU ID</td>
      <td class="bg_table boder_right">Product Name</td>
      <td class="bg_table boder_right">Minimum Amount</td>
      <td class="bg_table boder_right">Maximum Amount</td>
      <td class="bg_table boder_right">Discount</td>
      <td class="bg_table">Category</td>
    </tr>
    <?php if(count($products)>0) {?>
    <?php $i=1;?>
    <?php if(is_array($products)){
    	foreach($products as $item) {?>
	    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	      <td align="center" class="boder_right"> <?php echo $i;?> </td>
	      <td class="boder_right"> <?php echo $item->skuId;?> </td>
	      <td class="boder_right"> <?php echo $item->productName;?> </td>
	      <td class="boder_right"> <?php echo $item->minAmount;?> </td> 
	      <td class="boder_right"> <?php echo $item->maxAmount;?> </td>
	      <td class="boder_right"> <?php echo $item->discount;?> </td>
	      <td class="boder_right"> <?php echo $item->category;?> </td>
	    </tr>
		<?php }
	}else{ ?>
		<tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	      <td align="center" class="boder_right"> <?php echo $i;?> </td>
	      <td class="boder_right"> <?php echo $products->skuId;?> </td>
	      <td class="boder_right"> <?php echo $products->productName;?> </td>
	      <td class="boder_right"> <?php echo $products->minAmount;?> </td> 
	      <td class="boder_right"> <?php echo $products->maxAmount;?> </td>
	      <td class="boder_right"> <?php echo $products->discount;?> </td>
	      <td class="boder_right"> <?php echo $products->category;?> </td>
	    </tr>
	<?php }?>
    <?php $i++;?>
    <?php } else {?>
    <tr>
      <td colspan="8">Record Not Found</td>
    </tr>
    <?php }?>
  </table>
<?php }?>


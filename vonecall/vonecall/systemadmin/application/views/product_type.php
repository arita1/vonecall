<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Products</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="bg_title_content">Add / Edit Product List</div>
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('product-type'), array('id'=>'product_type_form', 'name'=>'product_type_form'));?>
      <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
      <table border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td> Name: <span class="red_color">*</span> </td>
	      <td><input name="productName" value="<?php echo isset($productName)?$productName:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['productName'])) {?><span class="red_color"><?php echo $error['productName'];?></span><?php }?></td>
	    </tr>
	    <!--
	    <tr>
	      <td> RYD Commission: <span class="red_color">*</span> </td>
	      <td><input name="rydCommission" value="<?php echo isset($rydCommission)?$rydCommission:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['rydCommission'])) {?><span class="red_color"><?php echo $error['rydCommission'];?></span><?php }?></td>
	    </tr>	  
	    <tr>
	      <td> Distributor Commission: <span class="red_color">*</span> </td>
	      <td><input name="distCommission" value="<?php echo isset($distCommission)?$distCommission:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['distCommission'])) {?><span class="red_color"><?php echo $error['distCommission'];?></span><?php }?></td>
	    </tr>  
	    <tr>
	      <td> Minimum Store Commission: <span class="red_color">*</span> </td>
	      <td><input name="minStoreCommission" value="<?php echo isset($minStoreCommission)?$minStoreCommission:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['minStoreCommission'])) {?><span class="red_color"><?php echo $error['minStoreCommission'];?></span><?php }?></td>
	    </tr>	    
	    <tr>
	      <td> Maximum Store Commission: <span class="red_color">*</span> </td>
	      <td><input name="maxStoreCommission" value="<?php echo isset($maxStoreCommission)?$maxStoreCommission:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['maxStoreCommission'])) {?><span class="red_color"><?php echo $error['maxStoreCommission'];?></span><?php }?></td>
	    </tr>	
	    -->    
	    <tr>
	      <td>Note:</td>
	      <td><input name="note" value="<?php echo isset($note)?$note:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['note'])) {?><span class="red_color"><?php echo $error['note'];?></span><?php }?></td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>
	        <a class="bt_check" href="javascript:void(0);" onclick="$('#product_type_form').submit();">Submit</a>
	        <a class="bt_check" href="javascript:void(0);" onclick="window.location='<?php echo site_url('product-type');?>';">Cancel</a>
	      </td>
	      <td></td>
	    </tr>
	  </table>
      <?php echo form_close();?>
      <div class="cb"></div>
    </div>
    
    <div class="bg_title_content">List Products</div>
    <div class="box_phonenumber p12t p13b">
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="50" align="center" class="bg_table boder_right"> ID </td>
          <td align="center" class="bg_table boder_right"> Product Name </td>
          <!--
          <td align="center" class="bg_table boder_right"> RYD Commission </td>
          <td align="center" class="bg_table boder_right"> Distributor Commission </td>
          <td align="center" class="bg_table boder_right"> Minimum Store Commission </td>
          <td align="center" class="bg_table boder_right"> Maximum Store Commission </td>
          -->
          <td align="center" class="bg_table boder_right">  Note </td>          
          <td colspan="2" align="center" class="bg_table">  Action</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo $item->productName;?></td>
          <!--
          <td class="boder_right" align="right"><?php echo $item->adminCommission;?>%</td>
          <td class="boder_right" align="right"><?php echo $item->distributorCommission;?>%</td>
          <td class="boder_right" align="right"><?php echo $item->minStoreCommission;?>%</td>
          <td class="boder_right" align="right"><?php echo $item->maxStoreCommission;?>%</td>
          -->
          <td class="boder_right"><?php echo $item->note;?></td>
          <td width="100" align="center" class="boder_right">
          	<a class="bt_edit" onclick="updateProduct(	<?php echo $item->productID;?>, '<?php echo $item->productName;?>', '<?php echo $item->adminCommission;?>', '<?php echo $item->distributorCommission;?>', '<?php echo $item->minStoreCommission;?>','<?php echo $item->maxStoreCommission;?>', '<?php echo $item->note;?>' )">Edit</a></td>
          <td width="100" align="center"><a class="bt_delete" onclick="deleteProduct(<?php echo $item->productID;?>, '<?php echo $item->productName;?>');">Delete</a></td>
        </tr>
        <?php  $i++;}?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
function deleteProduct(productID, productName) {
	if (confirm("Are you sure want to delete this Product?")) {
		$.ajax({
			url: '<?php echo site_url('admin/delete_product_type');?>',
			type: 'POST',
			cache: false,
			data:{productID:productID, productName:productName},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					window.location.reload();
				} else {
					alert(data.text);
				}
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
	
	return;
}
function updateProduct(productID, productName, adminComm, distComm, minStoreComm,maxStoreComm, note) {
	$("input[name=edit]").val(productID);
	$("input[name=productName]").val(productName);
	//$("input[name=rydCommission]").val(adminComm);
	//$("input[name=distCommission]").val(distComm);
	//$("input[name=minStoreCommission]").val(minStoreComm);
	//$("input[name=maxStoreCommission]").val(maxStoreComm);
	$("input[name=note]").val(note);
	$("input[name=productName]").focus();
}

</script>
<?php include '_footer.php';?>
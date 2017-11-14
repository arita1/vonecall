<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Reports</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="bg_title_content">All Reports</div>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="50" align="center" class="bg_table boder_right"> ID </td>
          <td align="center" class="bg_table boder_right"> Product Name </td>
          <td align="center" class="bg_table boder_right"> Product List </td>
          <td align="center" class="bg_table boder_right"> User </td>
		  <td align="center" class="bg_table boder_right"> Charged By </td>
		  <td align="center" class="bg_table boder_right"> Amount </td>
		  <td align="center" class="bg_table boder_right"> Store Commission </td>
          <td align="center" class="bg_table boder_right"> Distributor Commission </td>
          <td align="center" class="bg_table boder_right"> RYD Commission </td>          
        </tr>
        <?php //echo '<pre>';print_r($allPayments);die;?>
        <?php if(count($allPayments)>0) {?>
        <?php $i=1;foreach($allPayments as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo $item->vproductName;?></td>
          <td class="boder_right"><?php echo $item->productName;?></td>
          <td class="boder_right"><?php echo $item->agentTypeName.' - '.$item->firstName.' '.$item->lastName;?></td>
          <td class="boder_right"><?php echo $item->chargedBy;?></td>
          <td class="boder_right" align="right"><?php echo format_price($item->chargedAmount);?></td>
          <td class="boder_right" align="right"><?php echo format_price($item->storeCommission);?></td>
          <td class="boder_right" align="right"><?php echo format_price($item->accountRepCommission);?></td>
          <td class="boder_right" align="right"><?php echo format_price($item->adminCommission);?></td>
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
	$("input[name=rydCommission]").val(adminComm);
	$("input[name=distCommission]").val(distComm);
	$("input[name=minStoreCommission]").val(minStoreComm);
	$("input[name=maxStoreCommission]").val(maxStoreComm);
	$("input[name=note]").val(note);
	$("input[name=productName]").focus();
}

</script>
<?php include '_footer.php';?>
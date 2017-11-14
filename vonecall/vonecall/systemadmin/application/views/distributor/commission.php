<div class="bg_title_content">Distributor Commission</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('destributor/commission/'.$agentID), array('id'=>'commission_form', 'name'=>'commission_form'))?>
  <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
    </tr>
    <tr>
      <td>Product</td>
      <td>
        <?php echo form_dropdown('product', $option_product, (isset($product)?$product:''), 'class="w327"');?> <!-- onchange="select_store_commission(this.value);" -->
        <?php if (isset($error['product'])) {?><span class="red_color"><?php echo $error['product'];?></span><?php }?>
      </td>
    </tr>
    <!--
    <tr>
      <td>Commission % Rate</td>
      <td>
        <input type="text" name="commissionRate" value="<?php echo isset($commissionRate)?$commissionRate:'';?>" class="w35" />%
        <?php if (isset($error['commissionRate'])) {?><span class="red_color"><?php echo $error['commissionRate'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Maximum Store Commission</td>
      <td>
        <?php echo form_dropdown('maxStoreCommission', $option_max_store_commission, (isset($maxStoreCommission)?$maxStoreCommission:''), 'class="w327"');?>
        <?php if (isset($error['maxStoreCommission'])) {?><span class="red_color"><?php echo $error['maxStoreCommission'];?></span><?php }?>
      </td>
    </tr>
    
    <tr>
      <td><?php echo $this->lang->line('notes');?></td>
      <td>
        <input type="text" name="note" value="<?php echo isset($note)?$note:'';?>" class="w325" />
        <?php if (isset($error['note'])) {?><span class="red_color"><?php echo $error['note'];?></span><?php }?>
      </td>
    </tr>
    -->
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#commission_form').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<div class="bg_title_content">Commission Table</div>
<div class="box_phonenumber p12t p13b">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td class="bg_table boder_right">Product Name</td>
      <!--
      <td class="bg_table boder_right">Commission % Rate</td>
      <td class="bg_table boder_right">Max Store Commission</td>
      -->
      <td class="bg_table boder_right">Entered By</td>
      <td class="bg_table boder_right">Note</td>
      <td class="bg_table" colspan="2" width="200" align="center">Action</td>
    </tr>
    <?php if(count($results)>0) {?>
    <?php $i=1;?>
    <?php foreach($results as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td class="boder_right"><?php echo $item->productName;?></td>
      <!--
      <td class="boder_right"><?php echo number_format($item->commissionRate, 2);?></td>
      <td class="boder_right"><?php echo $item->maxStoreCommission;?></td> 
      -->
      <td class="boder_right"><?php echo $item->enteredBy;?></td>
      <td class="boder_right"><?php echo $item->note;?></td>
      <td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="updateCommission(<?php echo $item->ID;?>, '<?php echo $item->productID;?>', '<?php echo $item->commissionRate;?>', '<?php echo $item->maxStoreCommission;?>', '<?php echo $item->note;?>');">Edit</a></td>
      <td width="100" align="center"><a class="bt_delete" onclick="deleteCommission(<?php echo $item->ID;?>);">Delete</a></td>
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="7"><?php echo $this->lang->line('empty_results');?></td>
    </tr>
    <?php }?>
  </table>
</div>

<script type="text/javascript">
function deleteCommission(id) {
	if (confirm("Are you sure want to delete this Commission?")) {
		$.ajax({
			url: '<?php echo site_url('destributor/delete-commission/'.$agentID);?>',
			type: 'POST',
			cache: false,
			data:{id:id},
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
function updateCommission(id, productID, commissionRate, maxStoreCommission, note) {
	$('html, body').animate({ scrollTop: 0 }, 'slow');
	$("input[name=edit]").val(id);
	$("select[name=product]").val(productID);
	$("input[name=commissionRate]").val(commissionRate);
	//$("select[name=maxStoreCommission]").val(maxStoreCommission);
	$("input[name=note]").val(note);
	$("select[name=productName]").focus();
}


</script>
<?php include '_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker({format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
  <div class="bg_title">Promotion</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="bg_title_content">Add Promotion</div>
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('promotion'), array('id'=>'promotion_form', 'name'=>'promotion_form'));?>
      <table border="0" cellspacing="0" cellpadding="0">
      	<input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
	    <tr>
	      <td>Promotion Start Date:</td>
	      <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
	      <td><?php if (isset($error['from_date'])) {?><span class="red_color"><?php echo $error['from_date'];?></span><?php }?></td>
	    </tr>
	    <tr>
	      <td>Promotion End Date:</td>
	      <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
	      <td><?php if (isset($error['to_date'])) {?><span class="red_color"><?php echo $error['to_date'];?></span><?php }?></td>
	    </tr>
	    <tr>
	      <td>Promotion Dollar Amount:</td>
	      <td><input name="amount" value="<?php echo isset($amount)?$amount:'';?>" type="text" class="w162"/></td>
	      <td><?php if (isset($error['amount'])) {?><span class="red_color"><?php echo $error['amount'];?></span><?php }?></td>
	    </tr>
	    <tr>
	      <td>Product:</td>
	      <td>
	        <?php echo form_dropdown('product', $option_product, (isset($product)?$product:''), 'class="w162"');?>
	        <?php if (isset($error['product'])) {?><span class="red_color"><?php echo $error['product'];?></span><?php }?>
	      </td>
	    </tr>
	    <tr>
	      <td>Customer Type:</td>
	      <td><?php echo form_dropdown('customer_type', $option_customer_type, (isset($customer_type)?$customer_type:''), 'class="w162"');?></td>
	      <td><?php if (isset($error['customer_type'])) {?><span class="red_color"><?php echo $error['customer_type'];?></span><?php }?></td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><a class="bt_check" href="javascript:void(0);" onclick="$('#promotion_form').submit();">Submit</a></td>
	      <td></td>
	    </tr>
	  </table>
      <?php echo form_close();?>
      <div class="cb"></div>
    </div>
    
    <div class="bg_title_content">List of Promotion</div>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Promotion Start Date</td>
          <td class="bg_table boder_right">Promotion End Date</td>
          <td class="bg_table boder_right" align="right">Amount</td>
          <td class="bg_table boder_right">Product</td>
          <td class="bg_table boder_right">Customer Type</td>
          <td class="bg_table" align="center" width="100" colspan="2">Action</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y', strtotime($item->fromDate));?></td>
          <td class="boder_right"><?php echo date('m/d/Y', strtotime($item->toDate));?></td>
          <td class="boder_right" align="right"><?php echo format_price($item->amount);?></td>
          <td class="boder_right"><?php echo $item->productName;?></td>
          <td class="boder_right">New Only</td>
          <td align="center" class="boder_right"><a class="bt_edit" onclick="updatePromotion('<?php echo $item->ID;?>', '<?php echo date('m/d/Y', strtotime($item->fromDate));?>','<?php echo date('m/d/Y', strtotime($item->toDate));?>','<?php echo $item->amount;?>','<?php echo $item->productID;?>');">Edit</a></td>
          <td align="center"><a class="bt_delete" onclick="deletePromotion('<?php echo $item->ID;?>');">Delete</a></td>
        </tr>
        <?php  $i++;}?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
  </div>
</div>
<script>
function deletePromotion(id) {
	if (confirm("Are you sure want to delete this promotion history?")) {
		$.ajax({
			url: '<?php echo site_url('admin/delete_promotion');?>',
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
			error: function () {
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}

function updatePromotion(ID, startDate, endDate, amount, product) {
	$("input[name=edit]").val(ID);
	$("input[name=from_date]").val(startDate);
	$("input[name=to_date]").val(endDate);
	$("input[name=amount]").val(amount);
	$("select[name=product]").val(product);	
}
</script>
<?php include '_footer.php';?>
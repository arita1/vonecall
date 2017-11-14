<div class="bg_title_content">TOPUP RTR</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('ppn/topup-recharge'), array('id'=>'form_topup_recharge', 'name'=>'form_topup_recharge'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td> Store</td>
		<td> <?php echo form_dropdown('store', $allStore, (isset($store)?$store:''), 'class="w327"');?> 
			<?php if (isset($error['store'])) {?><span class="red_color"><?php echo $error['store'];?></span><?php }?> </td>
	</tr>
    <tr>
      <td> Product </td>
      <td> <?php echo form_dropdown('product', $products, (isset($product)?$product:''), 'class="w327"');?> 
      	 <?php if (isset($error['product'])) {?><span class="red_color"><?php echo $error['product'];?></span><?php }?> </td>
    </tr>
    <tr>
      <td> Amount </td>
      <td> <input type="text" name="rechargeAmount" value="<?php echo isset($rechargeAmount)?$rechargeAmount:''?>" class="w327" /> 
      	<?php if (isset($error['rechargeAmount'])) {?><span class="red_color"><?php echo $error['rechargeAmount'];?></span><?php }?> </td>
    </tr>
    <tr>
      <td> Area Code </td>
      <td> <input type="text" name="areaCode" value="<?php echo isset($areaCode)?$areaCode:''?>" class="w327" /> 
      	<?php if (isset($error['areaCode'])) {?><span class="red_color"><?php echo $error['areaCode'];?></span><?php }?> </td>
    </tr>
    <tr>
      <td> Recipient Mobile </td>
      <td> <input type="text" name="rechargeNumber" value="<?php echo isset($rechargeNumber)?$rechargeNumber:''?>" class="w327" /> 
      	<?php if (isset($error['rechargeNumber'])) {?><span class="red_color"><?php echo $error['rechargeNumber'];?></span><?php }?> </td>
    </tr> 
    <tr>
      <td> Sender Mobile </td>
      <td> <input type="text" name="senderNumber" value="<?php echo isset($senderNumber)?$senderNumber:''?>" class="w327" /> <br>
        <b>Sender Mobile is required, when the recharge is international topup.</b>
        <?php if (isset($error['senderNumber'])) {?><span class="red_color"><?php echo $error['senderNumber'];?></span><?php }?> 
      </td>
    </tr>   
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_topup_recharge').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<script>
function get_product(value) {
	$.ajax({
		url: '<?php echo site_url('prepaynation/get_carrier_products/');?>/'+value,
		type: 'POST',
		cache: false,
		data:{},
		success: function(data) {
			$("select[name=product]").html(data);
		}
	});
	
}
</script>
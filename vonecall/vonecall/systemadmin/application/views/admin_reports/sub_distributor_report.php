<script>

function submit_summary(button_type) {
	$('input[name=\'button_type\']').val(button_type); 
	$('input[name=\'submit_type\']').val('summary'); 
	$('#subdist_report').submit();
}
function submit_detail(button_type) {
	$('input[name=\'button_type\']').val(button_type);
	$('input[name=\'submit_type\']').val('detail');
	$('#subdist_report').submit();
}
</script>
<div id="main">
  <div class="bg_title_content">Sales Report</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('reports/sub-distributor-report'), array('id'=>'subdist_report', 'name'=>'subdist_report'));?>
      <input type="hidden" name="button_type" value=""/>
      <input type="hidden" name="submit_type" value=""/>
      <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>From Date</td>
          <td>To Date</td>
          <td>Product</td>          
          <td>Sub-Distributor</td>
          <td>Store</td>
        </tr>
        <tr>
          <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><?php echo form_dropdown('products', $option_products, (isset($products)?$products:''), 'class="w162"');?></td>
          <td><?php echo form_dropdown('distributor', $option_dist, (isset($distributor)?$distributor:''), 'class="w162" onchange="get_store_by_dist(this.value);"');?></td>
          <td><?php echo form_dropdown('store', $option_store, (isset($store)?$store:''), 'class="w162"');?></td>          
        </tr>
        <tr>
        	<td colspan="5"><a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_detail('date');" style="margin-bottom: 7px;">Search</a></td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>

<?php if (isset($results)) {  ?>    
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Store Name</td>
          <td align="right" class="bg_table boder_right">Store Payment Amount</td>
          <td align="right" class="bg_table boder_right">Store Commission</td>
          <td align="right" class="bg_table boder_right">Distributor Commission</td>
          <td align="right" class="bg_table boder_right">Admin Commission</td>          
          <td align="right" class="bg_table boder_right">Product Type</td>
          <td align="right" class="bg_table boder_right">Category</td>
          <td align="right" class="bg_table">Product Name</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;$totalAmount=0;$totalStoreCommission=0;$totalDistCommission=0; $totalAdminCommission=0;?>
	    	<?php foreach($results as $item) {  ?>
	        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
	          <td class="boder_right"><?php echo $item->company_name;?></td>
	          <td align="right" class="boder_right"><?php echo $item->isRefund?'-':''?><?php echo format_price($item->chargedAmount);?></td>
	          <td align="right" class="boder_right"><?php echo format_price($item->storeCommission);?></td>
	          <td align="right" class="boder_right"><?php echo format_price($item->accountRepCommission);?></td>
	          <td align="right" class="boder_right"><?php echo format_price($item->adminCommission);?></td>	          
	          <td align="right" class="boder_right"><?php echo $item->vproductType;?></td>
	          <td align="right" class="boder_right"><?php echo $item->isRefund?'Refund':$item->vproductCategory;?></td>
	          <td align="right"><?php echo $item->vproductName;?></td>
	        </tr>
	        <?php $totalAmount += $item->isRefund?0:(float)$item->chargedAmount;?>
	        <?php $totalStoreCommission += (float)$item->storeCommission;?>
	        <?php $totalDistCommission += (float)$item->accountRepCommission;?>
	        <?php $totalAdminCommission += (float)$item->adminCommission;?>
	        <?php $i++;?>
	    	<?php } //End Foreach?>	        
	        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
	          <td colspan="2"></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalStoreCommission);?></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalDistCommission);?></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAdminCommission);?></td>	          
	          <td colspan="3"></td>
	        </tr>	        
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>    
<?php }?>
  </div>
  <div class="cb"></div>
</div>

<script>
/***** Search store by sub-dist *****/
<?php if(isset($distributor)){ ?>
	get_store_by_dist(<?php echo $distributor?>);
<?php } ?>
function get_store_by_dist(distID){ 
	var ID = distID;
	if(!distID)
		var ID = 0;
		
	$.ajax({
		url: '<?php echo site_url('admin/get_store_by_dist/');?>/'+ID,
		type: 'POST',
		cache: false,
		data:{},		
		success: function(data) {
			$("select[name=store]").html(data);
			<?php  if(isset($store)){ ?>
			$("select[name=store]").val('<?php echo $store;?>');
			<?php }?>
		}
	});
}
</script>

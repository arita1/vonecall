<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
  <div class="bg_title_content">Sub Distributor Report</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('subdist-report'), array('id'=>'subdist_report', 'name'=>'subdist_report'));?>
      <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>From Date</td>
          <td>To Date</td>
          <td>Sub Dist</td>
          <td>Store</td>          
        </tr>
        <tr>
          <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><?php echo form_dropdown('sub_dist', $option_subdist, (isset($sub_dist)?$sub_dist:''), 'class="w162"  onchange="get_store_by_dist(this.value);"');?></td>
          <td><?php echo form_dropdown('agent', $option_agent, (isset($agent)?$agent:''), 'class="w162"');?></td>              
        </tr>
        <tr>
          <td colspan="2"> &nbsp; </td>
          <td> <?php if (isset($error['sub_dist'])) {?><span class="red_color"><?php echo $error['sub_dist'];?></span><?php }?> </td>
          <td> <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('#subdist_report').submit();" style="margin-bottom: 7px;">Search</a> </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>

<?php if (isset($store_payments)) { ?>    
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Store ID</td>
          <td align="right" class="bg_table boder_right">Store Commission</td>
          <td align="right" class="bg_table boder_right">Distributor Commission</td>
          <td align="right" class="bg_table boder_right">Store Payment Amount</td>
          <td align="right" class="bg_table boder_right">Product Type</td>
          <td align="right" class="bg_table">Product Name</td>
        </tr>
       
        <?php if(count($store_payments)>0) {?>
        <?php $i=1;$totalAmount=0;$totalStoreCommission=0;$totalCommission=0;?>
        <?php foreach($store_payments as $item) { ?>
    		<tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
	          <td class="boder_right"><?php echo $item->agentLoginID;?></td>
	          <td align="right" class="boder_right"><?php echo format_price($item->storeCommission);?></td>
	          <td align="right" class="boder_right"><?php echo format_price($item->accountRepCommission);?></td>
	          <td align="right" class="boder_right"><?php echo $item->isRefund?'-':''?><?php echo format_price($item->chargedAmount);?></td>
	          <td align="right" class="boder_right"><?php echo $item->vproductType;?></td>
	          <td align="right"><?php echo $item->vproductName;?></td>
	        </tr>
	        <?php 
	        if($item->isRefund){
	         	$totalAmount = (float) ((float) $totalAmount - (float)$item->chargedAmount);  	
	       	}else{
	       		$totalAmount = (float)((float) $totalAmount + $item->chargedAmount);
	       	}
	       // $totalAmount += $item->isRefund?0:(float)$item->chargedAmount;
	        
	        
	        ?>
	        <?php $totalStoreCommission += (float)$item->storeCommission;?>
	        <?php $totalCommission += (float)$item->accountRepCommission;?>
	        <?php $i++;?>
        <?php }?>
        <?php if($i > 1){?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
          <td colspan="2"></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalStoreCommission);?></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalCommission);?></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
          <td colspan="2"></td>
        </tr>
        <?php }else{ ?> 
        	<tr>
	          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
	        </tr>	
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>    
<?php }?>


<?php if (isset($allStoreReports)) {  ?>    
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Store ID</td>
          <td align="right" class="bg_table boder_right">Store Commission</td>
          <td align="right" class="bg_table boder_right">Distributor Commission</td>
          <td align="right" class="bg_table boder_right">Store Payment Amount</td>
          <td align="right" class="bg_table boder_right">Product Type</td>
          <td align="right" class="bg_table">Product Name</td>
        </tr>
        <?php if(count($allStoreReports)>0) {?>
        <?php $i=1;$totalAmount=0;$totalStoreCommission=0;$totalCommission=0;?>
	        <?php foreach($allStoreReports as $item) {
	        		//if(count($item)>1){
	        			foreach($item as $items){	
				    ?>
				        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
				          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($items->createdDate));?></td>
				          <td class="boder_right"><?php echo $items->agentLoginID;?></td>
				          <td align="right" class="boder_right"><?php echo format_price($items->storeCommission);?></td>
				          <td align="right" class="boder_right"><?php echo format_price($items->accountRepCommission);?></td>
				          <td align="right" class="boder_right"><?php echo format_price($items->chargedAmount);?></td>
				          <td align="right" class="boder_right"><?php echo $items->vproductType;?></td>
				          <td align="right"><?php echo $items->vproductName;?></td>
				        </tr>
				        <?php $totalAmount += (float)$items->chargedAmount;?>
				        <?php $totalStoreCommission += (float)$items->storeCommission;?>
				        <?php $totalCommission += (float)$items->accountRepCommission;?>
				        <?php $i++;?>
				        <?php } //End IF-Foreach?>
	        	<?php //} //End IF?>
	        <?php } // END Foreach?>
	        <?php if($i > 1){?>
	        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
	          <td colspan="2"></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalStoreCommission);?></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalCommission);?></td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
	          <td colspan="2"></td>
	        </tr>
	        <?php } else { ?> 
	        	<tr>
		          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
		        </tr>	
	        <?php }?>
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
<?php if(isset($sub_dist)){ ?>
	get_store_by_dist(<?php echo $sub_dist?>);
<?php } ?>
function get_store_by_dist(distID){
	var ID = distID;
	if(!distID)
		var ID = 0;
		
	$.ajax({
		url: '<?php echo site_url('get-store-by-subdist/');?>/'+ID,
		type: 'POST',
		cache: false,
		data:{},		
		success: function(data) {
			$("select[name=agent]").html(data);
			<?php  if(isset($agent)){ ?>
			$("select[name=agent]").val('<?php echo $agent;?>');
			<?php }?>
		}
	});
}
</script>


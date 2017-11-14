<script>
function submit_summary(button_type) {
	$('input[name=\'button_type\']').val(button_type); 
	$('input[name=\'submit_type\']').val('summary'); 
	$('#commission_report').submit();
}
function submit_detail(button_type) {
	$('input[name=\'button_type\']').val(button_type);
	$('input[name=\'submit_type\']').val('detail');
	$('#commission_report').submit();
}

$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
  <div class="bg_title_content">Commission Report</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('commission-report'), array('id'=>'commission_report', 'name'=>'commission_report'));?>
      <input type="hidden" name="button_type" value=""/>
      <input type="hidden" name="submit_type" value=""/>
      <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>From Date</td>
          <td>To Date</td>
          <td>Store</td>
          <td></td>
        </tr>
        <tr>
          <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><?php echo form_dropdown('agent', $option_agent, (isset($agent)?$agent:''), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_detail('date');" style="margin-bottom: 7px;">Search</a>
          </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
    
<?php if (isset($commission_reports)) { //echo '<pre>';print_r($commission_reports);die;?>    
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Transaction Date</td>
          <td class="bg_table boder_right">Store Name</td>
          <td class="bg_table boder_right">Source</td>
          <td class="bg_table boder_right">Account ID</td>
          <td class="bg_table boder_right">Product</td>
          <td class="bg_table boder_right">Trans Type</td>
          <td align="right" class="bg_table boder_right">Amount</td>
          <td align="right" class="bg_table boder_right">Commission</td>
        </tr>
        <?php if(count($commission_reports)>0) {?>
        <?php $i=1;$totalAmount=0;$totalDiscount=0;$totalCommission=0;?>
        <?php foreach($commission_reports as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td class="boder_right"><?php echo $item->firstName.' '.$item->lastName;?></td>
          <td class="boder_right"><?php echo $item->agentTypeName;?></td>
          <td class="boder_right"><?php echo $item->agentLoginID;?></td>
          <td class="boder_right"><?php echo $item->vproductName;?></td>
          <td class="boder_right"><?php echo $item->paymentMethod;?></td>
          <td class="boder_right" align="right"><?php echo $item->isRefund?'-':''?><?php echo format_price($item->chargedAmount);?></td>
          <td align="right"><?php echo format_price($item->accountRepCommission);?></td>
        </tr>
        <?php
    	if($item->isRefund){
         	$totalAmount = (float) ((float) $totalAmount - (float)$item->chargedAmount);  	
       	}else{
       		$totalAmount = (float)((float) $totalAmount + $item->chargedAmount);
       	}
        
        //$totalAmount += $item->isRefund?0:(float)$item->chargedAmount;?>
        <?php $totalCommission += (float)$item->accountRepCommission;?>
        <?php $i++;?>
        <?php }?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
          <td colspan="6"></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalCommission);?></td>
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

<?php include '_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datepick({dateFormat: "mm/dd/yy"});
	$('input[name=\'to_date\']').datepick({dateFormat: "mm/dd/yy"});
});
function submit_summary(button_type) {
	$('input[name=\'button_type\']').val(button_type); 
	$('input[name=\'submit_type\']').val('summary'); 
	$('#sale_report').submit();
}
function submit_detail(button_type) {
	$('input[name=\'button_type\']').val(button_type);
	$('input[name=\'submit_type\']').val('detail');
	$('#sale_report').submit();
}
</script>
<div id="main">
  <div class="bg_title">Sales Report</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('sales-report'), array('id'=>'sale_report', 'name'=>'sale_report'));?>
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
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_summary('date');" style="margin-bottom: 7px;">Summary</a>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_detail('date');" style="margin-bottom: 7px;">Detail</a>
          </td>
        </tr>
        <tr>
          <td>Time Period</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td><?php echo form_dropdown('time_period', $option_time_period, (isset($time_period)?$time_period:''), 'class="w162"');?></td>
          <td colspan="3">
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_summary('period');" style="margin-bottom: 7px;">Summary</a>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="submit_detail('period');" style="margin-bottom: 7px;">Detail</a>
          </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
    
<?php if (isset($summaries)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right" width="20%">Date</td>
          <td align="right" class="bg_table boder_right" width="20%">Total Sales (from Customer)</td>
          <td align="right" class="bg_table boder_right" width="20%">Total Payments (from Store)</td>
          <td align="right" class="bg_table boder_right" width="20%">Store Commissions</td>
          <td align="right" class="bg_table" width="20%">Account Rep Commissions</td>
        </tr>
        <?php if(count($summaries)>0) {?>
        <?php $i=1;?>
        <?php foreach($summaries as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y', strtotime($result_from_date)).' - '.date('m/d/Y', strtotime($result_to_date));?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->Sale);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->StorePayment);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->StoreCommission);?></td>
          <td align="right"><?php echo format_price($item->AccountRepCommission);?></td>
        </tr>
        <?php $i++;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
<?php }?>
<?php if (isset($customer_sales)) {?>
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Customer Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Customer ID</td>
          <td class="bg_table boder_right">Store ID</td>
          <td class="bg_table boder_right">Payment Method</td>
          <td align="right" class="bg_table boder_right">Amount</td>
          <td align="right" class="bg_table boder_right">Promotion</td>
          <td align="right" class="bg_table boder_right">Total Sale</td>
          <td align="right" class="bg_table boder_right">Store Commission</td>
          <td align="right" class="bg_table boder_right">Account Rep Commissions</td>
          <td class="bg_table">Note</td>
        </tr>
        <?php if(count($customer_sales)>0) {?>
        <?php $i=1;$totalAmount=0;$totalPromotion=0;$totalAll=0; $accRepCommission=0; $storeCommission=0;?>
        <?php foreach($customer_sales as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td class="boder_right"><?php echo $item->loginID;?></td>
          <td class="boder_right"><?php echo $item->agentLoginID;?></td>
          <td class="boder_right"><?php echo $item->paymentMethod;?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->promotion);?></td>
          <td align="right" class="boder_right"><?php echo format_price((float)$item->chargedAmount + (float)$item->promotion);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->storeCommission);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->accountRepCommission);?></td>
          <td><?php echo $item->comment;?></td>
        </tr>
        <?php $totalAmount += (float)$item->chargedAmount;?>
        <?php $totalPromotion += (float)$item->promotion;?>
        <?php $totalAll += (float)$item->chargedAmount + (float)$item->promotion;?>
        <?php $storeCommission += (float)$item->storeCommission; ?>
        <?php $accRepCommission += (float)$item->accountRepCommission; ?>
        <?php $i++;?>
        <?php }?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
          <td colspan="4"></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
          <td align="right"><?php echo format_price($totalPromotion);?></td>
          <td align="right"><?php echo format_price($totalAll);?></td>
          <td align="right"><?php echo format_price($storeCommission);?></td>
          <td align="right"><?php echo format_price($accRepCommission);?></td>
          <td align="right"></td>
        </tr>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
<?php }?>

<?php if (isset($store_payments)) {?>    
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store Sale</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Store ID</td>
          <td class="bg_table boder_right">Paid To</td>
          <td class="bg_table boder_right">Collected by Company</td>
          <td align="right" class="bg_table">Store Payment Amount</td>
        </tr>
        <?php if(count($store_payments)>0) {?>
        <?php $i=1;$totalAmount=0;$totalDiscount=0;$totalCommission=0;?>
        <?php foreach($store_payments as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td class="boder_right"><?php echo $item->loginID;?></td>
          <td class="boder_right"><?php echo $item->paidTo;?></td>
          <td class="boder_right"><?php echo $item->collectedByCompany==1?'Yes':'No';?></td>
          <td align="right"><?php echo format_price($item->chargedAmount);?></td>
        </tr>
        <?php $totalAmount += (float)$item->chargedAmount;?>
        <?php $totalDiscount += (float)$item->chargedDiscount;?>
        <?php $totalCommission += (float)$item->accountRepCommission;?>
        <?php $i++;?>
        <?php }?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
          <td colspan="4"></td>
          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
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
<?php include '_footer.php';?>
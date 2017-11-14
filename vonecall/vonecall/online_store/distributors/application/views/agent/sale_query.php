<script>
$(document).ready(function() {
	/*$('input[name=\'from_date\']').datepick({dateFormat: "mm/dd/yy"});
	$('input[name=\'to_date\']').datepick({dateFormat: "mm/dd/yy"});*/
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div class="bg_title_content">Store Sale Query</div>
<div class="form_addcustomer p12t p5b">
  <?php echo form_open_multipart(site_url('store/sale-query/'.$agentID), array('id'=>'sale_query', 'name'=>'sale_query'));?>
  <input type="hidden" id="button_type" name="button_type" value="<?php echo isset($button_type)?$button_type:'';?>"/>
  <input type="hidden" id="excel" name="excel" value="0"/>
  <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:0;?>"/>
  <input type="hidden" id="old_from_date" name="old_from_date" value="<?php echo isset($from_date)?$from_date:'';?>"/>
  <input type="hidden" id="old_to_date" name="old_to_date" value="<?php echo isset($to_date)?$to_date:'';?>"/>
  <input type="hidden" id="old_time_period" name="old_time_period" value="<?php echo isset($time_period)?$time_period:'';?>"/>
  <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>From Date</td>
      <td>To Date</td>
      <td></td>
    </tr>
    <tr>
      <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
      <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
      <td>
        <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('input[name=excel]').val(0);$('#button_type').val('date'); $('#sale_query').submit();" style="margin-bottom: 7px;">Submit</a>
      </td>
    </tr>
    <tr>
      <td>Time Period</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo form_dropdown('time_period', $option_time_period, (isset($time_period)?$time_period:''), 'class="w162"');?></td>
      <td colspan="2">
        <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('input[name=excel]').val(0);$('#button_type').val('period'); $('#sale_query').submit();" style="margin-bottom: 7px;">Submit</a>
      </td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
<?php if (isset($results)) {?>
<div class="box_phonenumber p12t p13b">
  <div class="p5t p5b" style="font-weight:bold;">Results: <?php echo date('m/d/Y', strtotime($results_from_date));?> - <?php echo date('m/d/Y', strtotime($results_to_date));?></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td class="bg_table boder_right">Date</td>
      <td class="bg_table boder_right">Customer ID</td>
      <td class="bg_table boder_right">Payment Method</td>
      <td class="bg_table boder_right">Entered By</td>
      <td align="right" class="bg_table boder_right">Sales</td>
      <td align="right" class="bg_table">Store Commission</td>
    </tr>
    <?php if(count($results)>0) {?>
    <?php $total=0;$i=1;foreach($results as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
      <td class="boder_right"><?php echo $item->loginID;?></td>
      <td class="boder_right"><?php echo $item->paymentMethod;?></td>
      <td class="boder_right"><?php echo $item->enteredBy;?></td>
      <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
      <td align="right"><?php echo format_price($item->storeCommission);?></td>
    </tr>
    <?php $total+=$item->chargedAmount;$i++;}?>
    <?php } else {?>
    <tr>
      <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
    </tr>
    <?php }?>
  </table>
  <?php echo isset($paging)?$paging:'';?>
</div>
<div class="form_addcustomer p13b">
  <div class="p5t p10b"><?php echo isset($total)?'Total: '.format_price($total):'';?></div>
</div>

<script>
function paging(num) {
	$('input[name=excel]').val(0);
	$('input[name=page]').val(num);
	$('input[name=\'from_date\']').val($('input[name=old_from_date]').val());
	$('input[name=\'to_date\']').val($('input[name=old_to_date]').val());
	$('select[name=\'time_period\']').val($('input[name=old_time_period]').val());
	$('#sale_query').submit();
}
</script>
<?php }?>
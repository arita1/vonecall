<?php include '_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datepick({dateFormat: "mm/dd/yy"});
	$('input[name=\'to_date\']').datepick({dateFormat: "mm/dd/yy"});
});
</script>
<div id="main">
  <div class="bg_title">Sales Report</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('store-summary'), array('id'=>'agent_summary', 'name'=>'agent_summary'));?>
      <?php if (isset($error['form'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <input type="hidden" id="excel" name="excel" value="0"/>
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
      <input type="hidden" name="report_type" id="report_type" value="<?php echo isset($report_type)?$report_type:'';?>"/>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>From Date:</td>
          <td>To Date:</td>
          <td></td>
        </tr>
        <tr>
          <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td></td>
          <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><a class="bt_atatement_address" href="javascript:void(0);" onclick="$('#excel').val(0);$('#agent_summary').submit();">View Report</a></td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
          
    <?php if (isset($results)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right">No</td>
          <td align="center" class="bg_table boder_right">Store ID</td>
          <td align="center" class="bg_table boder_right">First Name</td>
          <td align="center" class="bg_table boder_right">Last Name</td>
          <td align="center" class="bg_table boder_right">Login ID</td>
          <td align="center" class="bg_table boder_right">Payment by Store</td>
          <td align="center" class="bg_table boder_right">Total Cash Rec'd by Store</td>
          <td align="center" class="bg_table">Balance</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="center" class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo $item->agentID;?></td>
          <td class="boder_right"><?php echo $item->firstName;?></td>
          <td class="boder_right"><?php echo $item->lastName;?></td>
          <td class="boder_right"><?php echo $item->loginID;?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->paymentByAgent);?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->totalCashRecByAgent);?></td>
          <td align="right"><?php echo format_price($item->balance);?></td>
        </tr>
        <?php $i++;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
    <!-- 
    <p><a class="bt_atatement_address m7b" href="javascript:void(0);" onclick="$('#excel').val(1);$('#agent_summary').submit();">Export To Excel</a></p>
    -->
    <?php }?>
  </div>
  <div class="cb"></div>
</div>
<script>
function paging(num) {
	$('#excel').val(0);
	$('input[name=page]').val(num);
	$('#sales_report').submit();
}
</script>
<?php include '_footer.php';?>
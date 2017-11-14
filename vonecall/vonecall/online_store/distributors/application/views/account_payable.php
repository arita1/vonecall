<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Accounts Payable</div>
  <div class="p20l p20r p13b">
    <div class="box_submenu p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20%" class="boder_right">Total Payments: <?php echo format_price($total_payments);?></td>
          <td width="20%" class="boder_right">Total Uncollected: <?php echo format_price($total_uncollected);?></td>
          <td width="20%" class="boder_right">Total Collected: <?php echo format_price($total_collected);?></td>
          <td width="20%"></td>
          <td width="20%"></td>
        </tr>
      </table>
    </div>
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('account-payable'), array('id'=>'agent_collection', 'name'=>'agent_collection'));?>
      <input type="hidden" id="button_type" name="button_type" value="<?php echo isset($button_type)?$button_type:'';?>"/>
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:0;?>"/>
      <input type="hidden" id="old_agent" name="old_agent" value="<?php echo isset($agent)?$agent:'';?>"/>
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td width="162">Search Store</td>
          <td></td>
        </tr>
        <tr>
          <td><?php echo form_dropdown('agent', $option_agent, (isset($agent)?$agent:''), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address0" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('#button_type').val('search_agent'); $('#agent_collection').submit();" style="margin-bottom: 7px;">View</a>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <a class="bt_atatement_address2" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('#button_type').val('search_all_collected'); $('#agent_collection').submit();" style="margin-bottom: 7px;">All Paid to Company</a>
            <a class="bt_atatement_address2" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('#button_type').val('search_all_uncollected'); $('#agent_collection').submit();" style="margin-bottom: 7px;">All Unpaid to Company</a>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=page]').val(0);$('#button_type').val('search_all_payment'); $('#agent_collection').submit();" style="margin-bottom: 7px;">All Store Payments</a>
          </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
    
    <?php if (isset($button_type) && $button_type=='search_agent') {?>
    <div class="box_phonenumber p12t p13b">
      <div class="p5t p5b" style="font-weight:bold;">Store ID: <?php echo $results_loginID;?></div>
      <div class="p5t p5b" style="font-weight:bold;">Total Paid to Company: <?php echo format_price($results_collected);?></div>
      <div class="p5t p5b" style="font-weight:bold;">Total Unpaid to Company: <?php echo format_price($results_uncollected);?></div>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="left" class="bg_table boder_right" width="200">Date & Time</td>
          <td align="right" class="bg_table boder_right" width="100">Amount</td>
          <td align="left" class="bg_table" width="100">Paid To Company</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;$total=0;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="left" class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
          <td align="left">
            <span id="viewCollectedByCompany<?php echo $item->paymentID;?>"><?php echo $item->collectedByCompany==1?'Yes':'No';?></span>
          </td>
        </tr>
        <?php $i++;$total+=$item->chargedAmount;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="3"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
    <?php }?>
    <?php if (isset($button_type) && ($button_type=='search_all_uncollected' || $button_type=='search_all_collected' || $button_type=='search_all_payment')) {?>
    <div class="box_phonenumber p12t p13b">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="left" class="bg_table boder_right" width="200">Date & Time</td>
          <td align="left" class="bg_table boder_right" width="200">Store ID</td>
          <td align="right" class="bg_table boder_right" width="100">Amount</td>
          <td align="left" class="bg_table">Paid To Company</td>
        </tr>
        <?php if(count($results)>0) {?>
        <?php $i=1;$total=0;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="left" class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td align="left" class="boder_right"><?php echo $item->agentLoginID;?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
          <td align="left" class="boder_right">
            <span id="viewCollectedByCompany<?php echo $item->paymentID;?>"><?php echo $item->collectedByCompany==1?'Yes':'No';?></span>
          </td>
        </tr>
        <?php $i++;$total+=$item->chargedAmount;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="4"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
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
function edit(id) {
	$('#viewCollectedByCompany'+id).hide();
	$('#editCollectedByCompany'+id).show();
	$('#edit'+id).hide();
	$('#save'+id).show();
}
function save(id) {
	$.ajax({
		url: '<?php echo site_url('agent/update_collected_by_company');?>',
		type: 'POST',
		cache: false,
		data:{paymentID:id, collectedByCompany:$('#editCollectedByCompany'+id).val()},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				$('#viewCollectedByCompany'+id).html(data.textCollectedByCompany);
				$('#dateCollectedByCompany'+id).html(data.dateCollectedByCompany);
				$('#viewCollectedByCompany'+id).show();
				$('#editCollectedByCompany'+id).hide();
				$('#edit'+id).show();
				$('#save'+id).hide();
			} else {
				alert('error!');
			}
		},
		error: function (){
			alert('Please try again!');
		}
	});
}
</script>
<?php include '_footer.php';?>
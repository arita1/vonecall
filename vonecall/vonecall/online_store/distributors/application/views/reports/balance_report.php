<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div class="bg_title_content">Store Balance Report</div>
<div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('balance-report/'), array('id'=>'balance_report', 'name'=>'balance_report'));?>
      <input type="hidden" name="submit_type" value=""/>
      <table border="0" cellspacing="0" cellpadding="0">
      	<tr>
          <!--
          <td>From Date</td>
          <td>To Date</td>
          -->
          <td>Store</td>
          <td></td>
        </tr>
        <tr>
          <!--
          <td><input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          <td><input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/></td>
          -->
          <td><?php echo form_dropdown('agent', $option_agent, (isset($agent)?$agent:''), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('summary'); $('#balance_report').submit();" style="margin-bottom: 7px;">Get Report</a>
          </td>
        </tr>
        <tr>
      	  <td>&nbsp;</td>
          <td>&nbsp;</td>
      	  <td><?php if (isset($error['agent'])) {?><span class="red_color"><?php echo $error['agent'];?></span><?php }?></td>
      	  <td> &nbsp; </td>
      	</tr>
      </table>
      <?php echo form_close();?>
</div>

<?php if (isset($details)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Store Name</td>
          <td align="right" class="bg_table boder_right">Current Balance</td>
          <td align="right" class="bg_table boder_right">Store Owner</td>
          <td align="right" class="bg_table boder_right">Phone</td>
          <td align="right" class="bg_table boder_right">Cellphone</td>          
        </tr>
        <?php if(count($details)>0) {?>
        <?php $i=1;?>
        <?php foreach($details as $item) {?>
        <?php
        /*
        $balance = $item->balance;
        if ($item->category == 'Payment') {
        	$balance += $item->chargedAmount + $item->storeCommission;
        } 
        if ($item->category == 'Sale') {
        	$balance += -$item->chargedAmount;
        }
		*/
        ?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A');?></td>
          <td class="boder_right"><?php echo $item->company_name;?></td>
          <td align="right" class="boder_right"><?php echo '<b>('.format_price($item->balance).')</b>';?></td>
          <td align="right" class="boder_right"><?php echo $item->firstName.' '.$item->lastName ?></td>
          <td align="right" class="boder_right"><?php echo $item->phone ?></td>
          <td align="right" class="boder_right"><?php echo $item->cellPhone ?></td>          
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


<?php if (isset($detailsByDistributor)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td class="bg_table boder_right">Date</td>
          <td class="bg_table boder_right">Entered By</td>
          <td align="right" class="bg_table boder_right">Store Payment</td>
          <td align="right" class="bg_table boder_right">Sales</td>
          <td align="right" class="bg_table boder_right">Store Commission</td>
          <td align="right" class="bg_table boder_right">Account Rep Commission</td>
          <td align="right" class="bg_table boder_right">Current Balance</td>
        </tr>
        <?php //echo '<pre>';print_r($detailsByDistributor);die;?>
        <?php if(count($detailsByDistributor)>0) {?>
        <?php $i=1;?>
        <?php foreach($detailsByDistributor as $item) { 
        	
			if(count($item)>1){
				foreach($item as $items){
					$balance = $items->balance;
			        if ($items->category == 'Payment') {
			        	$balance += $items->chargedAmount + $items->storeCommission;
			        } 
			        if ($items->category == 'Sale') {
			        	$balance += -$items->chargedAmount;
			        }					
					 ?>
			        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
			          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($items->createdDate));?></td>
			          <td class="boder_right"><?php echo $items->enteredBy;?></td>
			          <td align="right" class="boder_right"><?php echo $items->category == 'Payment'?'('.format_price($items->chargedAmount).')':'';?></td>
			          <td align="right" class="boder_right"><?php echo $items->category == 'Sale'?format_price($items->chargedAmount):'';?></td>
			          <td align="right" class="boder_right"><?php echo $items->category == 'Sale'?'('.format_price($items->storeCommission).')':'';?></td>
			          <td align="right" class="boder_right"><?php echo $items->category == 'Sale'?'('.format_price($items->accountRepCommission).')':'';?></td>
			          <td align="right" class="boder_right"><?php echo '('.format_price($balance).')';?></td>
			        </tr>
			        <?php $i++;?>
			        <?php }?>
	        <?php } /*else {?>
			        <tr>
			          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
			        </tr>
			        <?php }*/
				}
			}else {?>
	        <tr>
	          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
	        </tr>
	        <?php } ?>      
      </table>
    </div>
<?php }?>

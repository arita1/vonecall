<div class="bg_title_content">Store Balance Report</div>
<div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('store/balance-report/'.$agentID), array('id'=>'balance_report', 'name'=>'balance_report'));?>
      <input type="hidden" name="submit_type" value=""/>
      <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('summary'); $('#balance_report').submit();" style="margin-bottom: 7px;">Get Report</a>
      <!--
      <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('detail'); $('#balance_report').submit();" style="margin-bottom: 7px;">Detail</a>
              
      <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Month</td>
          <td>Year</td>
          <td></td>
        </tr>
        <tr>
          <td><?php echo form_dropdown('month', $option_month, (isset($month)?$month:date('m')), 'class="w162"');?></td>
          <td><?php echo form_dropdown('year', $option_year, (isset($year)?$year:date('Y')), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('summary'); $('#balance_report').submit();" style="margin-bottom: 7px;">Summary</a>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('detail'); $('#balance_report').submit();" style="margin-bottom: 7px;">Detail</a>
          </td>
        </tr>
      </table>
      -->
      <?php echo form_close();?>
</div>

<?php if (isset($submit_type) && $submit_type=='summary') {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="25%" align="right" class="bg_table boder_right">Store Payment</td>
          <td width="25%" align="right" class="bg_table boder_right">Store Commission</td>
          <td width="25%" align="right" class="bg_table boder_right">Sale</td>
          <td width="25%" align="right" class="bg_table">Current Balance</td>
        </tr>
        <tr>
          <td align="right" class="boder_right"><?php echo $info->TotalPayment;?></td>
          <td align="right" class="boder_right"><?php echo $info->TotalCommission;?></td>
          <td align="right" class="boder_right"><?php echo $info->TotalSale;?></td>
          <td align="right"><?php echo format_price($info->balance);?></td>
        </tr>
      </table>
    </div>
<?php }?>

<?php if (isset($details)) {?>
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
          <td class="bg_table">Customer Login ID</td>
        </tr>
        <?php if(count($details)>0) {?>
        <?php $i=1;?>
        <?php foreach($details as $item) {?>
        <?php
        $balance = $item->balance;
        if ($item->category == 'Payment') {
        	$balance += $item->chargedAmount + $item->storeCommission;
        } 
        if ($item->category == 'Sale') {
        	$balance += -$item->chargedAmount;
        } 
        ?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td class="boder_right"><?php echo $item->enteredBy;?></td>
          <td align="right" class="boder_right"><?php echo $item->category == 'Payment'?'('.format_price($item->chargedAmount).')':'';?></td>
          <td align="right" class="boder_right"><?php echo $item->category == 'Sale'?format_price($item->chargedAmount):'';?></td>
          <td align="right" class="boder_right"><?php echo $item->category == 'Sale'?'('.format_price($item->storeCommission).')':'';?></td>
          <td align="right" class="boder_right"><?php echo $item->category == 'Sale'?'('.format_price($item->accountRepCommission).')':'';?></td>
          <td align="right" class="boder_right"><?php echo '('.format_price($balance).')';?></td>
          <td><?php echo $item->category == 'Sale'?$item->loginID:'';?></td>
          
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
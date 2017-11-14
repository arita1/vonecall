<?php if (isset($results)) {?>
<table class="font8" cellspacing="0" cellpadding="3" rules="all" bordercolor="#999999" border="1" id="dgSaleReport" style="background-color:White;border-color:#999999;border-width:1px;border-style:None;border-collapse:collapse;">
  <tr style="color:White;background-color:#000084;font-weight:bold;">
          <td align="center" class="bg_table boder_right">No</td>
          <td align="center" class="bg_table boder_right">Date</td>
          <td align="center" class="bg_table boder_right">Customer Login ID</td>
          <td align="center" class="bg_table boder_right">Charged By</td>
          <td align="center" class="bg_table boder_right">Entered By</td>
          <td align="center" class="bg_table boder_right">Amount</td>
          <td align="center" class="bg_table boder_right">Store Login ID</td>
          <td align="center" class="bg_table boder_right">Account Rep Login ID</td>
          <!--  
          <td align="center" class="bg_table boder_right">Store Commission</td>
          <td align="center" class="bg_table boder_right">Account Rep Commission</td>
          -->
          <td align="center" class="bg_table">Payment Type</td>
  </tr>
  <?php if(count($results)>0) {?>
  <?php $i=1;?>
  <?php foreach($results as $item) {?>
  <tr style="color:Black;background-color:<?php echo($i%2==1)?'#EEEEEE;':'Gainsboro;';?>">
          <td align="center" class="boder_right"><?php echo $i;?></td>
          <td align="left" class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td align="left" class="boder_right"><?php echo $item->loginID;?></td>
          <td align="left" class="boder_right"><?php echo $item->chargedBy;?></td>
          <td align="left" class="boder_right"><?php echo $item->enteredBy;?></td>
          <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
          <td align="left" class="boder_right"><?php echo $item->agentLoginID;?></td>
          <td align="left" class="boder_right"><?php echo $item->accountRepLoginID;?></td>
          <!--  
          <td align="right" class="boder_right"><?php echo $item->commissionRate>0?format_price($item->commissionRate/100*$item->chargedAmount):'';?></td>
          <td align="right" class="boder_right"><?php echo $item->accountRepCommissionRate>0?format_price($item->accountRepCommissionRate/100*$item->chargedAmount):'';?></td>
          -->
          <td><?php echo $item->paymentMethod;?></td>
  </tr>
  <?php $i++;?>
  <?php }?>
  <?php } else {?>
  <tr class="bg_ligh_gray">
    <td colspan="10"><?php echo $this->lang->line('empty_results');?></td>
  </tr>
  <?php }?>
</table>
<?php }?>
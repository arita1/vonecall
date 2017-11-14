<?php if (isset($results)) {?>
<table class="font8" cellspacing="0" cellpadding="3" rules="all" bordercolor="#999999" border="1" id="dgSaleReport" style="background-color:White;border-color:#999999;border-width:1px;border-style:None;border-collapse:collapse;">
  <tr style="color:White;background-color:#000084;font-weight:bold;">
          <td>No</td>
          <td>Store ID</td>
          <td>First Name</td>
          <td>Last Name</td>
          <td>Login ID</td>
          <td>Payment by Store</td>
          <td>Total Cash Rec'd by Store</td>
          <td>Balance</td>
  </tr>
  <?php if(count($results)>0) {?>
  <?php $i=1;?>
  <?php foreach($results as $item) {?>
  <tr style="color:Black;background-color:<?php echo($i%2==1)?'#EEEEEE;':'Gainsboro;';?>">
          <td><?php echo $i;?></td>
          <td><?php echo $item->agentID;?></td>
          <td><?php echo $item->firstName;?></td>
          <td><?php echo $item->lastName;?></td>
          <td><?php echo $item->loginID;?></td>
          <td><?php echo format_price($item->paymentByAgent);?></td>
          <td><?php echo format_price($item->totalCashRecByAgent);?></td>
          <td><?php echo format_price($item->balance);?></td>
  </tr>
  <?php $i++;?>
  <?php }?>
  <?php } else {?>
  <tr class="bg_ligh_gray">
    <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
  </tr>
  <?php }?>
</table>
<?php }?>
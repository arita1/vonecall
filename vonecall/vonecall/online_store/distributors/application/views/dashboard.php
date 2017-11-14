<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Customer Payment</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="box_phonenumber">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top" class="boder_right">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/profile/'.$customerID);?>">Customer Information</a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
            <tr>
              <td width="20%">Login ID:</td>
              <td width="80%"><?php echo $info->loginID;?></td>
            </tr>
            <tr>
              <td>Current Balance:</td>
              <td><?php echo format_price($info->balance);?></td>
            </tr>
            <tr>
              <td>First Name:</td>
              <td><?php echo $info->fistName;?></td>
            </tr>
            <tr>
              <td>Last Name:</td>
              <td><?php echo $info->lastName;?></td>
            </tr>
            <tr>
              <td>Store ID:</td>
              <td><?php echo $info->agentLoginID;?></td>
            </tr>
            </table>
          </td>
          <td width="50%" valign="top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/add-modify-phone/'.$customerID);?>">Phone Numbers</a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="white_color">
                <td align="center" class="bg_table boder_right" width="30">No.</td>
                <td class="bg_table boder_right">Phone Number</td>
                <td align="center" class="bg_table" width="100">Action</td>
              </tr>
              <?php $i=1;?>
              <?php foreach($phone_numbers as $value) {?>
              <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td align="center" class="boder_right"><?php echo $i;?></td>
                <td class="boder_right"><?php echo format_phone_number($value);?></td>
                <td align="center" class="boder_right"><a class="bt_delete" onclick="deletePhone('<?php echo $value;?>');">Delete</a></td>
              </tr>
              <?php $i++;?>
              <?php }?>
            </table>
            <div class="p13b"></div>
          </td>
        </tr>
        <tr>
          <td valign="top" class="boder_right boder_top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/payment-history/'.$customerID);?>">Payment History</a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="white_color">
                <td class="bg_table boder_right">Date</td>
                <td class="bg_table boder_right">Payment Method</td>
                <td align="right" class="bg_table boder_right">Amount</td>
                <td class="bg_table boder_right">Store ID</td>
                <td class="bg_table">Note</td>
              </tr>
              <?php $i=1;$total=0;?>
              <?php foreach($payment_histories as $item) {?>
              <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
                <td class="boder_right"><?php echo $item->paymentMethod;?></td>
                <td align="right" class="boder_right"><?php echo format_price($item->chargedAmount);?></td>
                <td class="boder_right"><?php echo $item->agentLoginID;?></td>
                <td><?php echo $item->comment;?></td>
              </tr>
              <?php $i++;$total+=$item->chargedAmount;?>
              <?php }?>
            </table>
            <div class="p13b"></div>
          </td>
          <td valign="top" class="boder_top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/call-details/'.$customerID);?>">Call Detail Record</a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="white_color">
                <td class="bg_table boder_right" width="">Call Time</td>
                <td class="bg_table boder_right" width="">Call From</td>
                <td class="bg_table boder_right" width="">Call To</td>
                <td class="bg_table boder_right" width="">Dest. Name</td>
                <td class="bg_table boder_right" width="">Dur.(Min)</td>
                <td align="right" class="bg_table" width="">Cost(US$)</td>
              </tr>
              <?php if (count($call_details)>0) {?>
              <?php $i=1;?>
              <?php foreach($call_details as $item) {?>
              <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td class="boder_right"><?php echo date('m/d/Y h:m:s A', strtotime($item->SeizeDateTime))?></td>
                <td class="boder_right"><?php echo $item->OriginalCallingNumber;?></td>
                <td class="boder_right"><?php echo format_phone_number($item->OriginalCalledNumber);?></td>
                <td class="boder_right"><?php echo $item->OUT_CountryName;?></td>
                <td class="boder_right"><?php echo $item->OUT_Duration;?></td>
                <td><?php echo format_price($item->OUT_AmountCharge);?></td>
              </tr>
              <?php $i++;?>
              <?php }?>
              <?php } else {?>
              <tr>
                <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
              </tr>
              <?php }?>
            </table>
            <div class="p13b"></div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<script>
function deletePhone(phone) {
	if (confirm("Are you sure want to delete this phone?")) {
		$.post("<?php echo site_url('customer/delete_phone/'.$info->subscriberID);?>/"+phone, {},
		    function(data){
				window.location.reload();
		    }
	    );
	} else {
		return false;
	}
}
</script>
<?php include '_footer.php';?>
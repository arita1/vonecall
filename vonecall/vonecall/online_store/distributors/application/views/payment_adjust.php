<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">
	Your Current Balance:  <span style="color: #6F9C01;"><?php echo format_price($balance)?></span> 
  </div>
 <div class="bg_title_content">Add Payment to Store's Account</div>
  <div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['credit_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['credit_error'];?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('payment-adjust'), array('id'=>'adjust_payment', 'name'=>'adjust_payment'))?>
  <table border="0" align="left" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2">Fields denoted by an asterisk (<span class="red_color">*</span>) are required. </td>
    </tr>
    <tr>
      <td>Amount ($)<span class="red_color">*</span></td>
      <td>
        <input type="text" name="chargedAmount" value="<?php echo isset($chargedAmount)?$chargedAmount:'';?>" class="w325"/>
        <?php if (isset($error['chargedAmount'])) {?><span class="red_color"><?php echo $error['chargedAmount'];?></span><?php }?>
      </td>
    </tr>
    
    <tr id="tr_use_previous_creditcard">
      <td>Store <span class="red_color">*</span></td>
      <td>
      	<?php echo form_dropdown('store', $option_store, (isset($store)?$store:''), 'class="w327"');?>
      	<?php if (isset($error['store'])) {?><span class="red_color"><?php echo $error['store'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Comment</td>
      <td><textarea class="w325" name="comment" cols="45" rows="5"><?php echo isset($comment)?$comment:'';?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <a class=" bt_check m5r" href="javascript:void(0);" onclick="$('#adjust_payment').submit();">Submit</a>
      </td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
</div>

<?php include '_footer.php';?>

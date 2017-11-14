<div class="bg_title_content">Add New Account</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/recharge-account'), array('id'=>'form_account_recharge', 'name'=>'form_account_recharge'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Account ID (i_account)</td>
		<td>
			<?php echo form_dropdown('id', $accountList, (isset($id)?$id:''), 'class="w327" id="id"');?>
			<?php if (isset($error['id'])) {?><span class="red_color"><?php echo $error['id'];?></span><?php }?>
		</td>
	</tr>			
	<tr>
		<td>Amount</td>
		<td>
			<input name="amount" value="<?php echo isset($amount)?$amount:'';?>" type="text" class="w325" />
			<?php if (isset($error['amount'])) {?><span class="red_color"><?php echo $error['amount'];?></span><?php }?>
		</td>
	</tr>
	   
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_account_recharge').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

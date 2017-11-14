		
<div class="bg_title_content">Get Account Details</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/account-info'), array('id'=>'form_account_info', 'name'=>'form_account_info'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Account ID</td>
		<td>
			<?php echo form_dropdown('id', $accountList, (isset($id)?$id:''), 'class="w327" id="id"');?>
			<?php if (isset($error['id'])) {?><span class="red_color"><?php echo $error['id'];?></span><?php }?>
		</td>
	</tr>
	   
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_account_info').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<?php if(isset($account_info)) {?>
<div class="bg_title_content">Account Details</div>
<div class="form_addcustomer p15t">
  <?php echo '<pre>';print_r($account_info);?>
</div>
<?php }?>
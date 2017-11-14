<div class="bg_title_content">Add Line (Alias)</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/add-line'), array('id'=>'form_add_line', 'name'=>'form_add_line'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Parent Account ID</td>
		<td>
			<?php echo form_dropdown('parentAccount', $accountList, (isset($parentAccount)?$parentAccount:''), 'class="w327" id="id"');?>
			<?php if (isset($error['parentAccount'])) {?><span class="red_color"><?php echo $error['parentAccount'];?></span><?php }?>
		</td>
	</tr>
	<tr>
      <td width="319">Country Code</td>
      <td width="905">
        <input name="country" maxlength="3" style="width: 50px;" value="<?php echo isset($country)?$country:'';?>" type="text" class="w325" />
        <?php if (isset($error['country'])) {?><span class="red_color"><?php echo $error['country'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Phone Number</td>
      <td>
        <input type="text" name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="w325" />
        <?php if (isset($error['phone'])) {?><span class="red_color"><?php echo $error['phone'];?></span><?php }?>
      </td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_add_line').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

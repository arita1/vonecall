<div class="bg_title_content">Import Contacts</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('contact-import'), array('id'=>'contacts_import_form', 'name'=>'contacts_import_form'))?>
  <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
    </tr>    
    <tr>
      <td>Select Group <span class="red_color">*</span></td>
      <td>
         <?php echo form_dropdown('group', $option_groups, (isset($group)?$group:''), 'class=""');?>
        <?php if (isset($error['group'])) {?><span class="red_color"><?php echo $error['group'];?></span><?php }?>
      </td>
    </tr>
    <tr>
	    <td width="30%">Import File (.csv OR .xls) <span class="red_color">*</span></td>
	    <td>
	    	<input type="file" name="contactFile" />
	    	<?php if (isset($error['contactFile'])) {?><span class="red_color"><?php echo $error['contactFile'];?></span><?php }?>
	    </td>
	</tr>
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#contacts_import_form').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

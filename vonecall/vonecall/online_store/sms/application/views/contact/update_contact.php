<div class="bg_title_content">Update Contact</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('update-contact/'.$contactID), array('id'=>'contacts_form', 'name'=>'contacts_form'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
    </tr>    
    <tr>
      <td>Select Group <span class="red_color">*</span></td>
      <td>
      	<select name="group">
      		<?php foreach($option_groups as $grpID=>$groupName) { ?> 
      			<option value="<?php echo $grpID?>" <?php echo ($grpID == $groupID) ? 'selected="selected"' : '' ?> > <?php echo $groupName?> </option>
      		<?php } ?>
      	</select>        
        <?php if (isset($error['group'])) {?><span class="red_color"><?php echo $error['group'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Phone Number <span class="red_color">*</span></td>
      <td>
        <input type="text" name="phoneNumber" value="<?php echo isset($phoneNumber)?$phoneNumber:'';?>" class="w325" />
        <?php if (isset($error['phoneNumber'])) {?><span class="red_color"><?php echo $error['phoneNumber'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td> Opt-Out <span class="red_color">*</span></td>
      <td>
        <input type="checkbox" name="optout" value="1" <?php echo isset($optout)?'checked=""':'';?> class="" />
        <?php if (isset($error['optout'])) {?><span class="red_color"><?php echo $error['optout'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#contacts_form').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

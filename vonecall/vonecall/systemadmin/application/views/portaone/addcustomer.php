
<div class="bg_title_content">Add New Customer</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/new-customer'), array('id'=>'form_add_customer', 'name'=>'form_add_customer'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
   
    <tr>
      <td width="319">Customer Name</td>
      <td width="905">
        <input name="name" value="<?php echo isset($name)?$name:'';?>" type="text" class="w325" />
        <?php if (isset($error['name'])) {?><span class="red_color"><?php echo $error['name'];?></span><?php }?>
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
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_add_customer').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
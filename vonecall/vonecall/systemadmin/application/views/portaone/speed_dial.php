<div class="bg_title_content">Add Speed dial</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/speed-dial'), array('id'=>'form_speed_dial', 'name'=>'form_speed_dial'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Account ID</td>
		<td>
			<?php echo form_dropdown('id', $accountList, (isset($id)?$id:''), 'class="w327" id="id"');?>
			<?php if (isset($error['id'])) {?><span class="red_color"><?php echo $error['id'];?></span><?php }?>
		</td>
	</tr>
	<tr>
      <td width="319">Contact Name</td>
      <td width="905">
        <input name="contact_name" value="<?php echo isset($contact_name)?$contact_name:'';?>" type="text" class="w325" />
        <?php if (isset($error['contact_name'])) {?><span class="red_color"><?php echo $error['contact_name'];?></span><?php }?>
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
      <td width="319">Contact Type (work, home, mobile, other)</td>
      <td width="905">
      	<select name="contact_type" class="w327 float_left">
      		<option value="work">Work</option>
      		<option value="home">Home</option>
      		<option value="mobile">Mobile</option>
      		<option value="other">Other</option>
      	</select>
        <!--<input name="contact_type" value="<?php echo isset($contact_type)?$contact_type:'';?>" type="text" class="w325" />-->
        <?php if (isset($error['contact_type'])) {?><span class="red_color"><?php echo $error['contact_type'];?></span><?php }?>
      </td>
    </tr>
    <!--
    <tr>
      <td width="319">Dial ID</td>
      <td width="905">
        <?php echo form_dropdown('dialID', $dial_id, (isset($dialID)?$dialID:''), 'class="w327 float_left"');?>
        <?php if (isset($error['dialID'])) {?><span class="red_color"><?php echo $error['dialID'];?></span><?php }?>
      </td>
    </tr>
    -->
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_speed_dial').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

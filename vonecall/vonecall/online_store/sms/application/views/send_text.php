<?php include '_header.php';?>
<script>
	$(document).ready(function () {
	    $('#text_message').characterCounter({
	        maximumCharacters: 160
	    });
	});
</script>

<div id="main">
  <div class="bg_title">Send Text Message</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="170" valign="top"class="col_left" >
      	<div class="nav_left"> </div>
      </td>
      <td align="left" valign="top">
      	<div class="p20l p20r p13b">
	      <div class="bg_title_content">Send Message</div>
			<div class="form_addcustomer p15t">
			  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
			  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
			  <?php echo form_open_multipart(site_url('send-text-message'), array('id'=>'form_send_text', 'name'=>'form_send_text'))?>
			  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
			    <tr>
			      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
			    </tr>
			    <tr>
			      <td width="319">Select Group</td>
			      <td width="905">
			        <?php echo form_dropdown('group', $option_groups, (isset($group)?$group:''), 'class=""');?>
			        <?php if (isset($error['group'])) {?><span class="red_color"><?php echo $error['group'];?></span><?php }?>
			      </td>
			    </tr>
			    <tr>
			      <td width="319">Message</td>
			      <td width="905">
			        <textarea cols="50" rows="5" maxlength="160" name="text_message" id="text_message"> <?php echo isset($text_message) ? $text_message : ''?> </textarea>
			        <?php if (isset($error['text_message'])) {?><span class="red_color"><?php echo $error['text_message'];?></span><?php }?>
			      </td>
			    </tr>			    		    		    		    		    	    
			    <tr>
			      <td>&nbsp;</td>
			      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_send_text').submit();">Send</a></td>
			    </tr>
			  </table>
			  <?php echo form_close();?>
			</div>       
        </div>
      </td>
    </tr>
  </table>
  <div class="cb"></div>
</div>

<?php include '_footer.php';?>
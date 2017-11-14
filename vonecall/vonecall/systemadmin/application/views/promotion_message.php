<?php include '_header.php';?>
<div id="main" class="mh730">
  <div class="bg_title">Promotion Message</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['comment'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['comment'];?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('promotion-message'), array('id'=>'form_message', 'name'=>'form_message'));?>
      <table border="0" align="left" cellpadding="0" cellspacing="0" class="font8">
        <tr>
          <td valign="top">Message:</td>
          <td><textarea style="width:600px; height:120px;" name="comment" id="comment" cols="45" rows="5"><?php echo isset($comment)?$comment:'';?></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <a class=" bt_check m5r" href="javascript:void(0);" onclick="$('#form_message').submit();">Submit</a>
          </td>
        </tr>
      </table>          
      <?php echo form_close();?>
    </div>
  </div>
</div>
<?php include '_footer.php';?>
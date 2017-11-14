
  <div class="bg_title_content"> <?php echo ($bannerCat=='Dist-Banner')? 'Distributor' : 'Store'?> Banner Message</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['comment'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['comment'];?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('banner-message'), array('id'=>'form_message', 'name'=>'form_message'));?>
      <table border="0" align="left" cellpadding="0" cellspacing="0" class="font8">
        <tr>
          <td valign="top">Select Banner for:</td>
          <td>
          	<?php echo form_dropdown('bannerCat', $this->config->item('banner_category'), (isset($bannerCat)?$bannerCat:''), 'class="w327 m5r float_left" onchange="redirect_by_cat(this.value);"');?>
    		<?php if (isset($error['bannerCat'])) {?><span class="red_color"><?php echo $error['bannerCat'];?></span><?php }?>
          </td>
        </tr>
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

<script>

function redirect_by_cat(id){
	window.location.href = "<?php echo site_url('banner-message')?>/"+id;
}
    
</script>
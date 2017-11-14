<div class="bg_title_content"> RYD Admin Settings </div>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	
	<?php echo form_open_multipart(site_url('ryd-admin'), array('id'=>'ryd_admin', 'name'=>'ryd_admin'));?>    
      <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      	<tr>
      		<td> RYD Email: </td>
      		<td> 
      			<input type="text" name="rydEmail" value="<?php echo isset($rydEmail)?$rydEmail:'' ?>" /> 
      			<?php if (isset($error['rydEmail'])) {?><span class="red_color"><?php echo $error['rydEmail'];?></span><?php }?>
      		</td>
      	</tr>
      	<tr>
	    	<td>&nbsp;</td>
	    	<td><a onclick="$('#ryd_admin').submit();" href="javascript:void(0);" class="bt_save">Submit</a></td>
	  	</tr>
      </table>
    <?php echo form_close();?>
  </div>
</div>


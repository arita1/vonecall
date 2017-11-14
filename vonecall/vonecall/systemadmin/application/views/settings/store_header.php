<div class="bg_title_content"> Store Settings </div>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	
	<?php echo form_open_multipart(site_url('store-header'), array('id'=>'store_header', 'name'=>'store_header'));?>    
      <table width="40%" cellspacing="0" cellpadding="0" border="0" align="center">
      	<tr>
      		<td> Customer Service Number: </td>
      		<td> 
      			<input type="text" name="serviceNumber" value="<?php echo isset($serviceNumber)?$serviceNumber:'' ?>" /> 
      			<?php if (isset($error['serviceNumber'])) {?><span class="red_color"><?php echo $error['serviceNumber'];?></span><?php }?>
      		</td>
      	</tr>
      	<tr>
	    	<td>&nbsp;</td>
	    	<td><a onclick="$('#store_header').submit();" href="javascript:void(0);" class="bt_save">Submit</a></td>
	  	</tr>
      </table>
    <?php echo form_close();?>
  </div>
</div>


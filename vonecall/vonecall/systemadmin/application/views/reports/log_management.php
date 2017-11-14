<div class="bg_title_content"> Log Management </div>
<script>	
 $(function() {
    $('input[name=fromDate]').datetimepicker({
		format: 'm/d/Y',
		timepicker:false,
	}).on('change', function(e){ $(this).datetimepicker('hide'); });	
	$('input[name=toDate]').datetimepicker({
		format: 'm/d/Y',
		timepicker:false,
	}).on('change', function(e){ $(this).datetimepicker('hide'); });		
  });
</script>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	<div class="bg_title_content2">Delete Log</div>
	<?php echo form_open_multipart(site_url('log-management'), array('id'=>'log_management', 'name'=>'log_management'));?>    
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">	   
	    <tr>
	      <td>From:</td>
	      <td>
	        <input type="text" id="fromDate" name="fromDate" value="<?php echo isset($fromDate) ? $fromDate : date('m/d/Y');?>" />
	        <?php if (isset($error['fromDate'])) {?><span class="red_color"><?php echo $error['fromDate'];?></span><?php }?>
	      </td>
	    </tr>
	    <tr>
	      <td>To:</td>
	      <td>
	        <input type="text" name="toDate" value="<?php echo isset($toDate) ? $toDate : date('m/d/Y');?>" />
	        <?php if (isset($error['toDate'])) {?><span class="red_color"><?php echo $error['toDate'];?></span><?php }?>
	      </td>
	    </tr>	    
	    <tr>
	      <td>&nbsp;</td>
	      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#log_management').submit();">Delete</a></td>
	    </tr>
	  </table>
	  <?php echo form_close();?> 
    
  </div>
</div>

<script>
function paging(num) {
	$('#excel').val(0);
	$('input[name=page]').val(num);
	$('#admin_log_report').submit();
}

$(document).ready(function(){
    $('#print_log').on('click', function() {
     	window.print();
    });
});
</script>
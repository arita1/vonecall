<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker({format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>

<div class="bg_title_content">Get All CDR details</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/cdr-reports'), array('id'=>'form_cdr_reports', 'name'=>'form_cdr_reports'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
   
    <tr>
		<td>From Date</td>
		<td>
			<input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/>
			<?php if (isset($error['from_date'])) {?><span class="red_color"><?php echo $error['from_date'];?></span><?php }?>
		</td>
	</tr>
	<tr>
		<td>To Date</td>
		<td>
			<input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/>
			<?php if (isset($error['to_date'])) {?><span class="red_color"><?php echo $error['to_date'];?></span><?php }?>
		</td>
	</tr>     
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_cdr_reports').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<?php /*if(isset($cdr_details)) {?>
<div class="bg_title_content">Cdr Details</div>
<div class="form_addcustomer p15t">
  <h2 style="width: 20%;">CDR Details: </h2>
  <?php if($cdr_details){
  			echo '<pre>';print_r($cdr_details); 
  		} else {
  			echo '<h2>No record found</h2>';
		}	
  ?>
</div>
<?php }*/?>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($cdr_details)) {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">Calling From</td>
      <td class="bg_table boder_right">Called To</td>
      <td class="bg_table boder_right">Description</td>
      <td class="bg_table boder_right">Connect Time</td>
      <td class="bg_table boder_right">Duration</td>
      <td class="bg_table boder_right">Status</td>      
    </tr>
    <?php if(count($cdr_details)>0) {?>
    <?php $i=1;?>
    <?php foreach($cdr_details as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $item->CLI;?> </td>
      <td class="boder_right"> <?php echo $item->CLD;?> </td>
      <td class="boder_right"> <?php echo $item->description;?> </td>
      <td class="boder_right"> <?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?> </td>
      <td class="boder_right"> <?php echo $item->charged_quantity;?> </td> 
      <td class="boder_right"> <?php echo $item->disconnect_reason;?> </td>   
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="8">Record Not Found</td>
    </tr>
    <?php }?>
  </table>
<?php }?>


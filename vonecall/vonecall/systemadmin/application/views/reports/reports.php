<div class="bg_title_content"> View logs </div>
<div class="form_addcustomer p15t">
	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
	<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
	
	<?php echo form_open_multipart(site_url('admin-logs'), array('id'=>'admin_log_report', 'name'=>'admin_log_report'));?>    
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
      <input type="hidden" id="excel" name="excel" value="0"/>
    <?php echo form_close();?>
    <div class="box_phonenumber p12t p13b">
      <?php if (isset($results)) {?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right">No</td>
          <td align="center" class="bg_table boder_right">Admin</td>
          <td align="center" class="bg_table boder_right">Action</td>
          <td align="center" class="bg_table boder_right">Time</td>          
        </tr>        
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right" align="center"><?php echo $i;?></td>          
          <td class="boder_right"><?php echo $item->firstName;?></td>
          <td class="boder_right"><?php echo $item->action;?></td>
          <td class="boder_right"><?php echo date('d-m-Y H:i A', strtotime($item->date_time));?></td>          
        </tr>
        <?php $i++;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="9"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
        <?php echo isset($paging)?$paging:'';?>
      <?php }?>
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
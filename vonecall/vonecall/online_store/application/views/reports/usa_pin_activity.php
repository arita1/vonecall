<?php include APPPATH.'views/_header.php';?>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.box_makepayment .label1 {width: 200px;}
.btn_checkbox {margin-left:205px; width: 330px;}
.box_makepayment .label_short1 {width: 80px;}
.add_phone_text3 {width: 39px;}
</style>
<?php } else {?>
<style>
.box_makepayment .label1 {width: 160px;}
</style>
<?php } ?>
<style>
.refund_button{ 
	background-color: #039dcf;
    border: 1px solid #039dcf;
    border-radius: 4px;
    color: #fff;
    font-size: 12px;
    padding: 3px 6px; 
}
.refund_button:hover{
	background-color: #DEDEDE;
    border: 1px solid #4b4b4b;
    border-radius: 4px;
    color: #4b4b4b;
    text-decoration: none;
}
</style>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('usa-pin_activity');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <?php if (isset($error['email_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['email_error'];?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <div class="box_makepayment">
      	<?php echo form_open_multipart(site_url('usa-pin-activity'), array('id'=>'pin_activity_report', 'name'=>'pin_activity_report'));?>
        <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['error_date'];?></span><?php }?>
        <label class="label_short1"><?php echo $this->lang->line('from_date');?>:</label>
        <input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="box_makepayment_txt w105 float_left" readonly/>
        <label class="label_short1 float_left m10l"><?php echo $this->lang->line('to_date');?>:</label>
        <input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="box_makepayment_txt w105 float_left" readonly/>
        
        <a class="bt_submit4 float_left m10l" href="javascript:void(0);" onclick="$('#pin_activity_report').submit();"><?php echo $this->lang->line('submit');?></a>
        <br class="cb">    
        <?php echo form_close();?>      
      </div>        
      <!-- Display Results -->
      <?php if (isset($results)) {?>
      <div class="box_phonenumber p10l p10r p10t">
        <table width="50%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
              <td width="20%" align="left" class="boder_right"><strong><?php echo $this->lang->line('date');?></strong></td>
              <td width="12%" align="left" class="boder_right"><strong>PIN</strong></td>
              <td width="12%" align="left" class="boder_right"><strong>Customer Phone</strong></td>                          
            </tr>
            <?php if(count($results)>0) { $i=1;?>
            <?php foreach($results as $item) { ?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td class="boder_right" align="left" ><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
              <td class="boder_right"><?php echo $item->pin_number?></td>
              <td align="right" class="boder_right"><?php echo format_phone_number($item->phoneNumber)?></td>
            </tr>          
	        <?php $i++;}?>
	        
            <?php } else {?>
            <tr>
              <td colspan="3"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <div class="cb"></div>
      </div>
      <?php }?>
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
 
<?php include APPPATH.'views/_footer.php';?>
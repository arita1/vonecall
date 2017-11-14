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
.box_makepayment .label1 {
    line-height: 15px;
}
</style>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"> Pinless Refund </div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <?php if (isset($error['email_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['email_error'];?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <div class="box_makepayment ">
        <!-- Refund form -->
        <?php echo form_open_multipart(site_url('pinless-refund/'.$customerId.'/'.$seq), array('id'=>'refund_form', 'name'=>'refund_form'));?>        
        
        <label class="label1">Current balance:</label>
        <?php echo format_price($balance);?>
        <input type="hidden" name="i_account" value="<?php echo isset($i_account)?$i_account:''?>" />
        <input type="hidden" name="current_balance" value="<?php echo isset($balance)?$balance:''?>" />
        <input type="hidden" name="refund_amount" value="<?php echo $results->chargedAmount?>" />
        <div class="cb"></div>
        
       	<?php //echo '<pre>';print_r($results);die;?>
		<label class="label1">Account:</label>
        <?php echo format_phone_number($results->phoneNumber);?>
        <div class="cb"></div>
        
        <label class="label1">Transfer Date:</label>
        <?php echo date('m/d/Y H:i A',strtotime($results->createdDate));?>
        <div class="cb"></div>
        
        <label class="label1">Store:</label>
        <?php echo $results->enteredBy;?>
        <div class="cb"></div>
        
        <label class="label1">Amount:</label>
        <?php echo format_price($results->chargedAmount);?>
        <div class="cb"></div>
       
        <?php if (isset($error['refundReason'])) {?><span class="p155l red_color"><?php echo $error['refundReason'];?></span><div class="cb"></div><?php }?>
        <label class="label1">Reason for refund:<span class="red_color">*</span></label>
        <?php echo form_dropdown('refundReason', $option_reasons, (isset($refundReason)?$refundReason:''), 'class="w264 float_left" onchange="show_other($(this).val())"');?>
        <div class="cb"></div>
        
        <label class="label1">&nbsp;</label>
        <input type="text" name="other_reason" id="other_reason" style="display: none;" class="box_makepayment_txt w264 float_left"/>
        <div class="cb"></div>
        
        <label class="label1">&nbsp;</label>
        <div class="cb"></div>
        
        <label class="label1">&nbsp;</label>
        <a class="bt_submit float_left" href="javascript:void(0);" onclick="$('#refund_form').submit();"><?php echo $this->lang->line('submit');?></a>
        <div class="cb"></div>
        <?php echo form_close();?>         
      </div>           
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
 
<script>
	function show_other(val){
		if(val=='yes')
			$('#other_reason').show();
		else
			$('#other_reason').hide();
	}
</script> 

<?php include APPPATH.'views/_footer.php';?>
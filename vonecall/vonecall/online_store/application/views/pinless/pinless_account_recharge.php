  <style>
  	.label1{
		font-size: 14px;
	}
  </style>
  
  <div class="bg_tt_page"><div class="ac">Recharge Account</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
    	<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <div class="box_makepayment ">        	
            <?php echo form_open_multipart(site_url('pinless-recharge/'.$phone), array('id'=>'recharge_form', 'name'=>'recharge_form'));?>
            
            <label class="label1"> Recharge amount <span class="red_color">*</span></label>
            <input name="amount" value="<?php echo isset($amount)?$amount:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
            <?php if (isset($error['amount'])) {?><span class="red_color"><?php echo $error['amount'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="confirmSubmit('<?php echo format_phone_number($phone);?>');"><?php echo $this->lang->line('submit');?></a>
            <a class="bt_submit4 float_left" href="<?php echo site_url('pinless-recharge/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
            <?php echo form_close();?>
            
            <div class="cb"></div>            
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>


<script type="text/javascript">
function confirmSubmit(phone){
	// If User Select 1st radio box
	var rechargeAmount = ($('input[name=amount]').val());
	if(confirm('You are about to recharge phone number '+phone+' with amount $'+rechargeAmount)){
			$('.loading').show();
			$('#recharge_form').submit()
	}else{
		return false;
	}	
}
</script>



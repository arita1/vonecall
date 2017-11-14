<?php include APPPATH.'views/_header.php';?>
<style>
.flag-block dt {background: none; padding-left: 0;}
.red_color{ padding-left: 10px; line-height: 30px; }
</style>
<div id="main" class="p5t p10b">
	<div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('usa_pin_purchase');?></div></div>
	<div class="center_page_afterlogin">
		<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
		<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
		<div class="col_big">
			<div class="box_makepayment">
								
				<div class="cb"></div>
				<?php echo form_open_multipart(site_url('topup-usa-pin'), array('id'=>'topup_usa_pin_form', 'name'=>'topup_usa_pin_form'));?>
				<input type="hidden" name="_step" value="1"/>
				<input type="hidden" name="_type" value="international"/>
				
				<label class="label1">Plan</label>
				<?php echo form_dropdown('pinProduct', $option_products, (isset($pinProduct)?$pinProduct:''), 'class="w268 float_left" id="rtr_product"');?>
				<?php if (isset($error['pinProduct'])) {?><span class="p155l red_color"><?php echo $error['pinProduct'];?></span><div class="cb"></div><?php }?>
				<div class="cb"></div>
				
				<label class="label1"><?php echo $this->lang->line('amount');?></label>
				<?php echo form_dropdown('amount', $option_amount, (isset($amount)?$amount:''), 'class="w268 float_left" id="amount"');?>
				<?php if (isset($error['amount'])) {?><span class="p155l red_color"><?php echo $error['amount'];?></span><div class="cb"></div><?php }?>
				<div class="cb"></div>
				
				<label class="label1">Recipient Mobile</label>
				<input name="phone" id="phone" value="<?php echo isset($phone)?$phone:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
				<?php if (isset($error['phone'])) {?><span class="p155l red_color"><?php echo $error['phone'];?></span><div class="cb"></div><?php }?>
				<div class="cb"></div>
				
				<div class="p155l">
					<a class="bt_submit4 float_left" href="javascript:void(0);" onclick="topup_usa_pin_submit();">CHARGE</a>
					<a class="bt_submit4 float_left" href="<?php echo site_url('topup-usa-pin');?>">CANCEL</a>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
	<div class="cb"></div>
	<div class="bottom_pages_afterlogin2"></div>
	<div class="cb"></div>
</div>
<script>
function topup_usa_pin_submit() {
	if ($('#phone').val() != '') {
		if (confirm('Are you sure you want to perform this transaction')) {
			$('#topup_usa_pin_form').submit();
		}
	} else {
		$('#topup_usa_pin_form').submit();
	}
	
}
</script>
<?php include APPPATH.'views/_footer.php';?>
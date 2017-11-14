<?php include APPPATH.'views/_header.php';?>
<style>
.label1{
	/*font-size: 18px;*/
}
.box_makepayment .label1 {
    margin: 3px;
    max-width: 300px;
    min-width: 220px;
   /* padding: 2px;*/
}
.box_makepayment { padding-left: 4px; padding-top: 15px; }
.sale_column_right { width: 480px; }
.sale_column_left { width: 470px !important; }
.font{ font-size: 14px }
.col_big{min-height: 315px;}
.pinless_form label {
   font-size: 14px; width: 180px !important;
}
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('pinless');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
    	<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <div class="box_makepayment ">
        	<div class="sale_column_right" style="background-color: #fff;"> 
        		
        		<div class="pinless_heading"> <h3> Add / Manage an account </h3> </div>
        		<div class="pinless_form">
	            	<label class="label1 font" style="min-width: 100px !important;">Phone Number:</label>
		            <label class="label1 font"><?php echo format_phone_number($login);?></label>
		            <div class="cb"></div>
		            
		            <label class="label1 font" style="min-width: 100px !important;">Account Balance is:</label>
		            <label class="label1 font"><?php echo format_price($balance);?></label>
		            <div class="cb"></div>
		            
		            <label class="label"> &nbsp; </label>
					<a class="bt_submit4 float_left" onclick="$('.loading').show();" href="<?php echo site_url('pinless-manage/'.$login);?>">Manage</a>
					<a class="bt_submit4 float_left" onclick="$('.loading').show();" href="<?php echo site_url('pinless-recharge/'.$login);?>"><?php echo $this->lang->line('recharge');?></a>
					<div class="cb"></div>
				</div>
			</div>
			
			<div class="sale_column_left">
	            <label class="label1"><?php echo $this->lang->line('store_code');?>:</label>
	            <label class="text_right"><?php echo substr($info->securityCode, -7);?></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('current_balance');?>:</label>
	            <label class="text_right"><?php echo format_price($info->balance);?></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('time_period');?>:</label>
	            <?php echo form_dropdown('time_period', $option_time_period, 'today', 'class="w187 float_left" onchange="calculate_agent(this.value);"');?>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('customer_sales');?>:</label>
	            <label class="text_right" id="agent_sale"></label>
	            <div class="cb"></div>
	            
	            <label class="label1"><?php echo $this->lang->line('gross_commission');?>:</label>
	            <label class="text_right" id="agent_commission"></label>
	            <div class="cb"></div>
	        </div>
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
</div>

<script>
// Calculate Commission
function calculate_agent(time_period) {
	$.ajax({
		url: '<?php echo site_url('customer/calculate_agent');?>',
		type: 'POST',
		cache: false,
		data:{time_period:time_period},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				$("#agent_sale").html(data.Sale);
				//$("#agent_payment").html(data.Payment);
				$("#agent_commission").html(data.Commission);
			} else {
				alert('<?php echo $this->lang->line('please_try_again');?>');
			}
		},
		error: function () {
			alert('<?php echo $this->lang->line('ajax_cannot_get_store');?>');
		}
	});
	return;
}
calculate_agent('today');
</script>
<?php include APPPATH.'views/_footer.php';?>
<?php include APPPATH.'views/_header.php';?>
<style>
.p155l { margin-left: 73px;	}
.box_makepayment { padding-left: 4px; padding-top: 15px; }
.sale_column_right { width: 480px; }
.sale_column_left { width: 470px !important; }
.col_big{min-height: 315px;}
#main { min-height: 350px; }
.pinless_form label {
   font-size: 14px; width: 210px !important;
}
</style> 
<div id="main" class="p5t p10b">
  <div class="bg_tt_page">
    <div class="ac" style="width:100%;">
      <div style="width:50%; float:left;">Pinless Management</div>
      <div style="width:50%; float:right;"><?php echo $this->lang->line('account_summary');?></div>
    </div>
  </div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
    	<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <div class="box_makepayment ">  
          
          <div class="sale_column_right" style="background-color: #fff;">
          	<div class="pinless_heading"> <h3> Add / Manage an account </h3> </div>      	
           <!--            
            <div class="pinless_link"> 
            	<a href="javascript:void(0)" id="add_pinless" onclick="pinless_function('add')" class="bt_submit4 active">Add<span></span></a> 
            	<a href="javascript:void(0)" id="manage_pinless" onclick="pinless_function('manage')" class="bt_submit4">Manage<span></span></a>
            	<div class="clear"></div> 
            </div>
           -->
            <div class="pinless_form">
            	<?php echo form_open_multipart(site_url('pinless'), array('id'=>'pinless_form', 'name'=>'pinless_form'));?>
	            <!-- <input type="hidden" name="pinless_method" value="add" /> -->
	            <?php if (isset($error['phone_number'])) {?><span class="p155l red_color"><?php echo $error['phone_number'];?></span><div class="cb"></div><?php }?>
	            <label class="label">Enter Phone Number:<span class="red_color">*</span></label>
	            <input name="phone_1" id="phone_1" value="<?php echo isset($phone_1)?$phone_1:'';?>" maxlength="3" class="add_phone_text3" type="text"/>
	            <input name="phone_2" id="phone_2" value="<?php echo isset($phone_2)?$phone_2:'';?>" maxlength="3" class="add_phone_text3" type="text"/>
	            <input name="phone_3" id="phone_3" value="<?php echo isset($phone_3)?$phone_3:'';?>" maxlength="4" class="add_phone_text3" type="text"/>
	            <div class="cb"></div>
	            
	            <label class="label"> &nbsp; </label>
	            <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="$('.loading').show();$('#pinless_form').submit();"><?php echo $this->lang->line('next');?></a>            
	            <div class="cb"></div>	            
	            <?php echo form_close();?>            	
            </div>           
            <div class="cb"></div> 
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
$(document).ready(function(){
	WireAutoTab('phone_1','phone_2', 3);
	WireAutoTab('phone_2','phone_3', 3);
      
	$("#phone_1").keyup(function () {
		if($(this).val().match(/\d{3}/)){
			$(this).removeClass('tx_red');
		}
	});

	$("#phone_2").keyup(function () {
		if($(this).val().match(/\d{3}/)){
			$(this).removeClass('tx_red');
		}
	});

	$("#phone_3").keyup(function () {
		if($(this).val().match(/\d{4}/)){
			$(this).removeClass('tx_red');
		}
	});
	$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
});

/***** Show / Hide Pinless box *****/
function pinless_function(method){
	if(method=='manage'){
		$('#add_pinless').removeClass('active');
    	$('#manage_pinless').addClass('active');
    	
    	$("input[name=pinless_method]").val('manage');
	}else{
		$('#manage_pinless').removeClass('active');
    	$('#add_pinless').addClass('active');
    	
    	$("input[name=pinless_method]").val('add');
	}
} // Show / Hide Pinless box END 

/***** Search carrier by Country *****/
$( "select[name=countryCode]" ).change(function() {
	var countryCode = $(this).val();
	$.ajax({
		url: '<?php echo site_url('get-product-by-country/');?>/'+countryCode,
		type: 'POST',
		cache: false,
		data:{},		
		success: function(data) {
			$("select[name=carrier]").html(data);
		}
	});
});
/***** Search amount by carrier *****/
$( "select[name=carrier]" ).change(function() {
	var carrier = $(this).val();
	$.ajax({
		url: '<?php echo site_url('customer/get_amount_by_carrier');?>/'+carrier,
		type: 'POST',
		cache: false,
		data:{},		
		success: function(data) {
			$("select[name=amount]").html(data);
		}
	});
});

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
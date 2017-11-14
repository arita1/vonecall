<?php include '_header.php';?>
<script type="text/javascript">
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
</script>
<style>
.label1 {width: 275px !important; padding-left: 10px !important;}

.sale_tt_box {color: #fff; font-size: 14px; font-weight: bold; line-height: 37px; text-transform: uppercase; padding: 0 0 0 10px;}
.innerbox { border:1px solid #d8d8d8; border-radius:3px 3px 3px 3px; float: left; margin: 0 0 10px 20px; width:43%; height:150px; }
.innerbox h1 {
    background: none repeat scroll 0 0 #FF0000;
    border-radius: 3px 3px 0 0;
    color: #FFFFFF;
    font-size: 18px;
    font-weight: bold;
    padding:8px 5px;
}
.col_big { min-height: 275px !important; }

.msg{ height: 160px }
.sale_column_right{ min-height: 160px; }

#main { min-height: 300px; }


	
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page">
    <div class="ac" style="width:100%;">
      <div style="width:50%; float:left;"><?php echo $this->lang->line('message');?></div>
      <div style="width:50%; float:right;"><?php echo $this->lang->line('account_summary');?></div>
    </div>
  </div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
        <div class="box_makepayment" style="padding:0;">
          <div class="sale_column_right" style="background-color: #fff;">
            
            <div class="msg"><?php echo nl2br($promotion_message);?></div>
            		
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
          <div class="slide_message">
          	<marquee width="100%"> <?php echo $banner_message; ?> </marquee>
          </div>
          <div class="cb"></div>
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>
<script>
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

// Commission PopUp ============
function commissionRate(){	
	$.colorbox({ href: "<?php echo site_url('popup-commission-rate');?>", width:"80%", height:"80%", iframe: true, scrolling: true });
}
</script>
<?php include '_footer.php';?>
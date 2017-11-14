<?php include '_header.php';?>
<script>
function commissionRate(){	
	$.colorbox({ href: "<?php echo site_url('commission-rate');?>", width:"80%", height:"80%", iframe: true, scrolling: true });
}
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
.home_div_left {
	border-right: 1px solid #d8d8d8;
    float: left;
    max-height: 500px;
    overflow: hidden;
    padding: 1%;
    width: 46%;
}
.home_div_right {
    float: right;
    max-height: 500px;
    overflow: hidden;
    padding: 1%;
    width: 46%;
}
.home_div_right font {
    color: #fd9b02;
    font-size: 14px;
    font-weight: bold;
}
.msg {
    background-color: #eaeaed;
    font-size: 15px;
    height: 250px;
    line-height: 25px;
    overflow-x: hidden;
    overflow-y: scroll;
    padding: 5px;
    text-align: justify;
}
.msg p {
    -moz-background-inline-policy: continuous;
    background: -moz-linear-gradient(center top , #cfd3d6, #fff) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 3px solid white;
    font-weight: bold;
    margin: 0 5px 0 0;
    padding: 10px;
}
.sale_tt_box {
	background-color: #6A7F85;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    line-height: 37px;
    margin-bottom: 15px;
    padding: 0 0 0 10px;
    text-transform: uppercase;
    clear: both;
}
.form_addcustomer label {
    font-size: 14px;
    font-weight: bold;
}
.text_right {
    float: right !important;
}
.iframe{
	font-size: 14px;
    font-weight: bold;
}
.banner_text {
	float: left;
	line-height: 70px;
	width: 100%;
}
</style>
<?php //echo '<pre>';print_r($info->balance);die;?>
<div id="main">
  <!--<div class="bg_title">Home</div>-->
   <div class="p20l p20r p13b">  
	<div class="bg_title">
		<div class="ac" style="width:100%;">
	      <div style="width:50%; float:left;"> Message </div>
	      <div style="width:50%; float:right;"> Account Details </div>
	    </div>
	</div>
	<div class="home_div_left" style="background-color: #fff;">	            
		<div class="msg"><?php echo nl2br($promotion_message);?></div>	    		
	</div>
	<div class="home_div_right form_addcustomer">
		<label class="label">Login ID:</label>
        <label class="text_right"> 123456 </label>
        <div class="cb"></div>
       
        <?php if($info->agentTypeID != 4){ ?>
        <label class="label">Current Balance:</label>
        <label class="text_right"><?php echo format_price($info->balance);?></label>
        <div class="cb"></div>
        <?php } ?>
        
        <label class="label">Time Period:</label>
        <?php echo form_dropdown('time_period', $option_time_period, 'today', 'class="w187 float_left" onchange="calculate_stores(this.value);" style="width: 223px;"');?>
        <div class="cb"></div>
        
        <label class="label">Store Sales:</label>
        <label class="text_right" id="store_sale"></label>
        <div class="cb"></div>
        <?php /*if($info->agentTypeID != 4){ ?>
        <label class="label">Gross Commission:</label>
        <label class="text_right" id="dist_commission"></label>
        <div class="cb"></div>
		
		<div class="sale_tt_box">Commission Rate</div>
		<div class="cb"></div>
		<font> Get Your Commission Rate: </font><a href="javascript:void(0)" class="iframe" onclick="commissionRate()"> Click Here </a>
		<div class="cb"></div>
		<?php }*/?>
	</div>
	
	<div class="banner_text">
		<marquee direction="left"> <?php echo $banner_message;?> </marquee>
	</div>
	
   </div>
  <div class="cb"></div>
</div>

<script>
function calculate_stores(time_period) {
	$.ajax({
		url: '<?php echo site_url('agent/calculate_stores');?>',
		type: 'POST',
		cache: false,
		data:{time_period:time_period},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				$("#store_sale").html(data.Sale);
				$("#dist_commission").html(data.Commission);
			} else {
				alert('Please try again!');
			}
		},
		error: function () {
			alert('Cannot retrieve infomation. Please check your connection!');
		}
	});
	return;
}
calculate_stores('today');

</script>

<?php include '_footer.php';?>
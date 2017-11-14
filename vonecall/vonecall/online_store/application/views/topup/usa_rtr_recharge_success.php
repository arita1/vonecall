<?php include APPPATH.'views/_header.php';?>
<style>
.flag-block dt {background: none; padding-left: 0;}
.box_makepayment .label1 {width: 200px;}
.box_makepayment .label2 {width: 500px; color: #000;}
.bt_submit4{
	background: none;
	background-color: #049ed0;
    border: medium none;
    border-radius: 7px;   
    line-height: 30px;    
    width: 100%;
}
.bt_submit4:hover, .bt_submit4:active, .bt_submit4:focus {
	background: none;
    background-color: #9D9D9D;
    color: #fff;
    text-decoration: none;
    line-height: 30px; 
}
html, body { height: auto; }
</style>
<div id="main" class="p5t p10b">
	<div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('usa_rtr');?></div></div>
	<div class="center_page_afterlogin">
		<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
		<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
		<div class="col_big">
			<div class="box_makepayment">
				<div class="flag-block">
					<dl>
						<dt>Successful!</dt>
					</dl>
				</div>
				<div class="cb"></div>
				
				<label class="label1">Carrier Name</label>
				<label class="label2"><?php echo $operator;?></label>
				<div class="cb"></div>
				
				<label class="label1">Mobile Number</label>
				<label class="label2"><?php echo $phone;?></label>
				<div class="cb"></div>
				
				<label class="label1">Sender Mobile</label>
				<label class="label2"><?php echo $senderNumber;?></label>
				<div class="cb"></div>
				
				<label class="label1">Amount</label>
				<label class="label2"><?php echo format_price($amount);?></label>
				<div class="cb"></div>
				
				<label class="label1"> <a href="<?php echo site_url('topup-usa-rtr');?>" class="bt_submit4" > Return to Home Page </a> </label>
				<label class="label2"> <input type="button" value="Print" class="bt_submit4" style="width: 15%;" onclick="print_page();" /> </label>
				<div class="cb"></div>
			</div>
		</div>
	</div>
	<div class="cb"></div>
	<div class="bottom_pages_afterlogin2"></div>
	<div class="cb"></div>
</div>

<script>
	<?php if(isset($success)){ ?>
		alert('The recharge request has been completed successfully!');
	<?php }?>
</script>

<script type="text/javascript">
    function print_page(){
       window.print();
        setTimeout("closePrintView()", 2000);
    };
    function closePrintView() {
        document.location.href = '<?php echo site_url('topup-usa-rtr');?>';
    }
</script>

<?php include APPPATH.'views/_footer.php';?>
<?php include APPPATH.'views/_header.php';?>
<style>
.flag-block dt {background: none; padding-left: 0;}
.label1 {width: 300px;}
.label2 {width: 500px; color: #000;}
.box_makepayment .label1{ width: 180px !important; }
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
	<div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('usa_pin_purchase');?></div></div>
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
				
				<label class="label1">PIN Number</label>
				<label class="label2"><?php echo $pinNumber;?></label>
				<div class="cb"></div>
				
				<label class="label1">Recipient Mobile</label>
				<label class="label2"><?php echo $phone;?></label>
				<div class="cb"></div>
				
				<label class="label1">To Replenish Your Account</label>
				<label class="label2"><?php echo $instructions;?></label>
				<div class="cb"></div>
				
				<label class="label1"> <a href="<?php echo site_url('topup-usa-pin');?>" class="bt_submit4" > Return to Home Page </a> </label>
				<label class="label2"> <input type="button" value="Print" class="bt_submit4" onclick="print_page();" /> </label>
				<div class="cb"></div>
			</div>
		</div>
	</div>
	<div class="cb"></div>
	<div class="bottom_pages_afterlogin2"></div>
	<div class="cb"></div>
</div>

<script>
	<?php if(isset($pinNumber)){ ?>
		var pinNumber = '<?php echo $pinNumber?>';
		alert('Pin '+pinNumber+' created successfully');
	<?php }?>
</script>

<script type="text/javascript">
    function print_page(){
       window.print();
        setTimeout("closePrintView()", 2000);
    };
    function closePrintView() {
        document.location.href = '<?php echo site_url('topup-usa-pin');?>';
    }
</script>
<?php include APPPATH.'views/_footer.php';?>
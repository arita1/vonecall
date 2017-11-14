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
	});
</script>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('sale');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <div class="box_phonenumber">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top" class="boder_right">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/profile/'.$customerID);?>"><?php echo $this->lang->line('customer_information');?></a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
            <tr>
              <td width="20%"><?php echo $this->lang->line('login_id');?>:</td>
              <td width="80%"><?php echo $info->loginID;?></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('current_balance');?>:</td>
              <td><?php echo format_price($balance);//echo format_price($info->balance);?></td>
            </tr>
            </table>
          </td>
          <td width="50%" valign="top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/add-modify-phone/'.$customerID);?>"><?php echo $this->lang->line('phone_numbers');?></a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="white_color">
                <td align="center" class="bg_table boder_right" width="30"><?php echo $this->lang->line('no');?></td>
                <td class="bg_table"><?php echo $this->lang->line('phone_number');?></td>
                <td align="center" class="bg_table" width="100">Action</td>
              </tr>
              <?php $i=1;?>
			  <?php if(isset($get_ani_list)){ ?>
              <?php //foreach($phone_numbers as $item) {
				for($j=0; $j<count($get_ani_list)-1; $j++){
			  ?>
              <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td align="center" class="boder_right"><?php echo $i;?></td>
                <td class=""><?php echo format_phone_number($get_ani_list[$j]->ani);?></td>
                <td align="center"><a class="bt_delete" onclick="return confirm_delete(<?php echo $get_ani_list[$j]->ani;?>, <?php echo $get_ani_list[$j]->pin;?>);">Delete</a></td>
              </tr>
              <?php $i++;?>
              <?php }?>
			  <?php }else{ ?>
			  <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td align="center" class="boder_right" Colspan="3"> No Record Found </td>                
              </tr>
			  <?php }?>
            </table>
            <div class="p13b"></div>
          </td>
        </tr>
        <tr>
          <td valign="top" class="boder_right boder_top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/payment-history/'.$customerID);?>">Payment History</a></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="white_color">
                <td class="bg_table boder_right"><?php echo $this->lang->line('date');?></td>
                <td class="bg_table boder_right"><?php echo $this->lang->line('payment_method');?></td>
                <td align="right" class="bg_table boder_right"><?php echo $this->lang->line('amount');?></td>
                <td class="bg_table"><?php echo $this->lang->line('note');?></td>
              </tr>
              <?php $i=1;$total=0;?>              
			  <?php //foreach($payment_histories as $item) {
				for($j=0; $j<count($payment_histories_api)-1; $j++){ ?>
				<?php if($payment_histories_api[$j]->Amount < 0 ){
						$transactionType = 'Refund';
						$finalAmount	 = str_replace('-','',$payment_histories_api[$j]->Amount);
					  }else{
						$transactionType = $payment_histories_api[$j]->TransType;
						$finalAmount     = $payment_histories_api[$j]->Amount;
					  }
				?>
              <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($payment_histories_api[$j]->TransDate));?></td>
                <td class="boder_right"><?php //echo $payment_histories[$j]->paymentMethod;?></td>
                <td align="right" class="boder_right"><?php echo format_price($finalAmount);?></td>
                <td><?php echo $transactionType;?></td>
              </tr>
              <?php $i++;$total+=$payment_histories_api[$j]->Amount;?>
              <?php }?>
            </table>
            <div class="p13b"></div>
          </td>
          <td valign="top">
            <div class="p5t p5b" style="font-weight:bold;"><a href="<?php echo site_url('customer/add-modify-phone/'.$customerID);?>"><?php echo $this->lang->line('add_new_phone');?></a></div>
            <?php echo form_open_multipart(site_url('customer/add-modify-phone/'.$customerID), array('id'=>'form_phone_add_edit', 'name'=>'form_phone_add_edit'))?>
            <input type="text" name="phone_1" id="phone_1" value="" class="add_phone_text" maxlength="3"/>
            <input type="text" name="phone_2" id="phone_2" value="" class="add_phone_text" maxlength="3"/>
            <input type="text" name="phone_3" id="phone_3" value="" class="add_phone_text" maxlength="4"/>
			<input type="hidden" name="customer_phone" id="customer_phone" value="<?php echo $customer_phone?>" />
            <a class="bt_submit2 float_left" href="javascript:void(0);" onclick="add_phone_number();"><?php echo $this->lang->line('submit');?></a>
            <?php echo form_close();?>
            <div class="p13b"></div>
          </td>
        </tr>
      </table>  
      <div class="cb"></div>
      </div>
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
<script>
function deletePhone(id) {
	if (confirm("<?php echo $this->lang->line('confirm_delete_phone');?>")) {
		$.ajax({
			url: '<?php echo site_url('customer/delete_phone');?>',
			type: 'POST',
			cache: false,
			data:{id:id},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					window.location.reload();
				} else {
					alert(data.text);
				}
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}
function add_phone_number() {
	if(check_phone_number("phone_1","phone_2","phone_3")) {
		phone_1 = $('#phone_1').val();
		phone_2 = $('#phone_2').val();
		phone_3 = $('#phone_3').val();
		customer_phone = $('#customer_phone').val();
		$.ajax({
			url: '<?php echo site_url('customer/add_phone_number/'.$customerID);?>',
			type: 'POST',
			cache: false,
			data:{phone_number:phone_1+phone_2+phone_3, customer_phone: customer_phone},
			dataType: "json",
			success: function(data) {
				alert(data.text);
				if (data.success) {
					window.location.reload();
				}
			},
			error: function (){
				alert('<?php echo $this->lang->line('please_try_again');?>');
			}
		});
	} else {
		return false;
	}
}

//Delete phone by API
function confirm_delete(ani, pin){		
		var Conf = confirm('Do you really want to remove this ANI');
		if(Conf == true){
			$.ajax({
				url: '<?php echo site_url('customer/remove_ani');?>',
				type: 'POST',
				cache: false,
				data:{pin:pin, ani:ani},
				dataType: "json",
				success: function(data) { 
					alert(data.text);
					if (data.success) {
						window.location.reload();
					}
				},
				error: function (){
					alert('Please try again!');
				}
			});
		}else{
			return false;
		}
	}
</script>
<?php include '_footer.php';?>
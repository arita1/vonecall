<?php include APPPATH.'views/_header.php';?>

<style>
	.label{ width: 222px !important; }
	
	.p155l {
	    line-height: 25px;
	    margin-left: -150px;
	}	
</style>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });

	// Phone number
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
  <div class="bg_tt_page"><div class="ac"> Calling Card History</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      
      <!-- Form Start -->
      <div class="box_makepayment ">        	
        <div class="cb"></div>
        <?php echo form_open_multipart(site_url('calling-card-history'), array('id'=>'transaction_form', 'name'=>'transaction_form'));?>
        
        <label class="label1">From Date<span class="red_color">*</span></label>
        <input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" class="box_makepayment_txt w264 float_left" type="text"/>
        <?php if (isset($error['from_date'])) {?><span class="p155l red_color"><?php echo $error['from_date'];?></span><div class="cb"></div><?php }?>
        <div class="cb"></div>
        
        <label class="label1">To Date<span class="red_color">*</span></label>
        <input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" class="box_makepayment_txt w264 float_left" type="text"/>
        <?php if (isset($error['to_date'])) {?><span class="p155l red_color"><?php echo $error['to_date'];?></span><div class="cb"></div><?php }?>
        <div class="cb"></div>
        
        <label class="label1">Calling Card PIN<span class="red_color">*</span></label>
        <input name="card_pin" value="<?php echo isset($card_pin)?$card_pin:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
        <?php if (isset($error['card_pin'])) {?><span class="p155l red_color"><?php echo $error['card_pin'];?></span><div class="cb"></div><?php }?>
        <div class="cb"></div>
        
        <label class="label1">&nbsp;</label>
        <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="$('.loading').show();$('#transaction_form').submit();"><?php echo $this->lang->line('submit');?></a>
        <a class="bt_submit4 float_left" href="<?php echo site_url('speed-dial/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
        <?php echo form_close();?>           
        <div class="cb"></div>            
      </div>
      <!-- Form END -->  
      
      <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
      <!-- Records -->
      <div class="box_phonenumber p10l p10r p10t">
	      <table width="100%" cellspacing="0" cellpadding="0" border="0">
	          <tbody>
	            <tr class="bg_table">
	              <td width="5%" align="left" class="boder_right"><strong>Calling From</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Called To</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Description</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Connect Time</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Duration</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Call Cost</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Status</strong></td>
	            </tr>
	            <?php if(count($cdr_details) > 0){
	            	$i=1;
	            	foreach($cdr_details as $item){ ?>
		            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
		              <td align="left" class="boder_right"><?php echo $item->CLI;?></td>
		              <td align="center" class="boder_right"><?php echo $item->CLD;?></td>
		              <td align="center" class="boder_right"><?php echo $item->description;?></td>
		              <td align="center" class="boder_right"><?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?></td>
		              <td align="center" class="boder_right"><?php echo $item->charged_quantity;?></td>
		              <td align="center" class="boder_right"><?php echo format_price($item->charged_amount);?></td>
		              <td align="center" class=""><?php echo $item->disconnect_reason;?></td>
		            </tr>	
	            <?php } ?>
	            	
	            <?php }else{ ?>
	            <tr>
	              <td colspan="6">No Data Match Your Query.</td>
	            </tr>
	            <?php }?>
	          </tbody>
	        </table>
          <div class="cb"></div>
        </div>
      <!-- Records END -->
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>
</div>  
<script>
jQuery(document).ready(function($){
		$('#accessnumber_table').dataTable({
			"sPaginationType" : "full_numbers",
			"aaSorting" : [[0, "asc"]],			
		});
	} );

function paging(num) {
	$('input[name=page]').val(num);
	$('#rate_form').submit();
}
</script>

<?php include APPPATH.'views/_footer.php';?>

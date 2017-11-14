<style>
	.label{ width: 222px !important; }
	.center_page_afterlogin { width: 779px;}
	.p155l {
	    line-height: 25px;
	    margin-left: -150px;
	}	
</style>

  <div class="bg_tt_page"><div class="ac">Speed Dial</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
      <div class="col_big">
      	<!-- Form -->
        <div class="box_makepayment ">        	
            <div class="cb"></div>
            <?php echo form_open_multipart(site_url('speed-dial/'.$phone), array('id'=>'speed_dial_form', 'name'=>'speed_dial_form'));?>
            
            <label class="label1">Contact Name</label>
            <span style="margin-right: 10px;">&nbsp;&nbsp;</span><input name="contactName" value="<?php echo isset($contactName)?$contactName:'';?>" class="box_makepayment_txt w264 " type="text"/>
            <?php if (isset($error['contactName'])) {?><span class="p155l red_color"><?php echo $error['contactName'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">Phone Number</label>
            <span style="margin-right: 2px;">+1</span><input name="phoneNumber" value="<?php echo isset($phoneNumber)?$phoneNumber:'';?>" class="box_makepayment_txt w264 " type="text"/>
            <?php if (isset($error['phoneNumber'])) {?><span class="p155l red_color"><?php echo $error['phoneNumber'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">Contact Type</label>
            <span style="margin-right: 10px;">&nbsp;&nbsp;</span><?php echo form_dropdown('contactType', $option_type, (isset($contactType)?$contactType:''), 'class="box_makepayment_txt w264"');?>
            <?php if (isset($error['contactType'])) {?><span class="p155l red_color"><?php echo $error['contactType'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="$('.loading').show();$('#speed_dial_form').submit();"><?php echo $this->lang->line('submit');?></a>
            <a class="bt_submit4 float_left" href="<?php echo site_url('speed-dial/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
            <?php echo form_close();?>           
            <div class="cb"></div>            
        </div>
        
        <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <!-- Records -->
        <div class="box_phonenumber p10l p10r p10t">
	        <table width="100%" cellspacing="0" cellpadding="0" border="0">
	          <tbody>
	            <tr class="bg_table">
	              <td width="5%" align="left" class="boder_right"><strong>Dial ID</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Contact Name</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Contact Number</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Contact Type</strong></td>
	              <td width="15%" align="center"><strong>Action</strong></td>
	            </tr>
	            <?php if(count($results) > 0){
	            	$i=1;
	            	foreach($results as $item){ ?>
		            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
		              <td align="left" class="boder_right"><?php echo $item->dial_id;?></td>
		              <td align="center" class="boder_right"><?php echo $item->name;?></td>
		              <td align="center" class="boder_right"><?php echo $item->phone_number;?></td>
		              <td align="center" class="boder_right"><?php echo $item->phone_type;?></td>
		              <td align="center"><a class="bt_delete" onclick="deleteSpeeddial(<?php echo $item->i_account_phonebook;?>);">Delete</a></td>
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
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
</div>

<script>
function deleteSpeeddial(id) {
	if (confirm("Are you sure want to delete this Speed dial?")) {
		$('.loading').show();
		$.ajax({
			url: '<?php echo site_url('customer/delete_speed_dial');?>',
			type: 'POST',
			cache: false,
			data:{id:id},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					$('.loading').hide();
					alert("You have been delete speed dial successfully!");
					window.location.reload();
				} else {
					$('.loading').hide();
					alert('Please try again!');
				}
			},
			error: function (){
				$('.loading').hide();
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}
</script>

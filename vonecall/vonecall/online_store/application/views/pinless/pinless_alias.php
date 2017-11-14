<style>
	.label{ width: 222px !important; }
	.center_page_afterlogin { width: 779px;}
	.p155l {
	    line-height: 25px;
	    margin-left: -150px;
	}
	.note_msg {margin-left: 5px;}
	.bg_tt_page{ width: 779px; }
	.bg_tt_page .ac{ width: 100%; }
	
</style>

  <div class="bg_tt_page">
  	<div class="ac">Associated Numbers </div>
  	<span class="red_color note_msg">Store is responsible verifying account owner's identity before adding new numbers</span>  
  </div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
      <div class="col_big">
      	<!-- Form -->
        <div class="box_makepayment ">        	
            <div class="cb"></div>
            <?php echo form_open_multipart(site_url('pinless-manage/'.$phone), array('id'=>'alias_form', 'name'=>'alias_form'));?>
            
            <label class="label1">Phone Number</label>
            +1<input name="phoneNumber" value="<?php echo isset($phoneNumber)?$phoneNumber:'';?>" class="box_makepayment_txt w264" type="text"/>
            <?php if (isset($error['phoneNumber'])) {?><span class="p155l red_color"><?php echo $error['phoneNumber'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="$('.loading').show();$('#alias_form').submit();"><?php echo $this->lang->line('submit');?></a>
            <a class="bt_submit4 float_left" href="<?php echo site_url('pinless-manage/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
            <div class="cb"></div>    
            <?php echo form_close();?>
            
            <label class="label1">&nbsp;</label> 
                    
            <div class="cb"></div>            
        </div>
        
        <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <!-- Records -->
        <div class="box_phonenumber p10l p10r p10t">
	        <table width="60%" cellspacing="0" cellpadding="0" border="0">
	          <tbody>
	            <tr class="bg_table">
	              <td width="5%" align="left" class="boder_right"><strong>No</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Number</strong></td>
	              <td width="15%" align="center"><strong>Action</strong></td>
	            </tr>
	            <?php if(count($results) > 0){ 				
	            	$i=1;
	            	foreach($results as $item){ 
	            		if (strpos($item->id,'old') == false) { ?> <!-- Check If contact is already deleted or in OLD category -->
						   <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
				              <td align="left" class="boder_right"><?php echo $i?></td>
				              <td align="center" class="boder_right"><?php echo str_replace('ani', '', $item->id);?></td>
				              <td align="center"><a class="bt_delete" onclick="deleteAlias(<?php echo $item->i_account?>, <?php echo $phone;?>,'<?php echo $item->id?>');">Delete</a></td>
				           </tr> 
				<?php } ?>
	            <?php $i++; } ?>
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
function deleteAlias(alias_account, parentAccount, alias_id) {
	if (confirm("Are you sure want to delete this record?")) {
		$('.loading').show();
		$.ajax({
			url: '<?php echo site_url('customer/delete_alias');?>',
			type: 'POST',
			cache: false,
			data:{id:alias_account, parentAccount:parentAccount, aliasID: alias_id},
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

<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Upload Calling Cards</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['image_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['image_error'];?></li></ul></dd></dl><?php }?>
  
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('upload-local-access'), array('id'=>'upload_local_access', 'name'=>'upload_local_access'));?>
      	  	                
      <label>Upload Local Access: </label>
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/> 
      <input type="file" name="callingCardsAccessNumber" id="file" />
      <?php if (isset($error['callingCardsAccessNumber'])) {?><span class="red_color"><?php echo $error['callingCardsAccessNumber'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <strong style="margin: 0 10px 0 0;"> Overwrite </strong> <input type="radio" name="importType" value="overwrite" />
      <strong style="margin: 0 10px 0 20px;"> Append </strong> <input type="radio" name="importType" value="append" checked="" />                 
      <?php if (isset($error['importType'])) {?><span class="red_color"><?php echo $error['importType'];?></span><?php }?>      
      <div class="cb"></div>
      
      <label> &nbsp; <!--<a class="bt_save float_left" href="javascript:void(0);" onclick="window.location.href = '<?php echo site_url('calling-card-batch') ?>'" >Add Batch</a>--></label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#upload_local_access').submit();" >Upload</a>
      <div class="cb"></div>
      <br>
      <?php echo form_close();?>
    </div>
    <?php if(isset($results)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="20%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> No. </td>
          <td align="center" class="bg_table boder_right"> Access Number </td>                   
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo format_phone_number($item->accessNumber);?></td>          
        </tr>
        <?php $i++;?>
        <?php }?> 
        <?php } else {?>
        <tr>
          <td colspan="2"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
      <?php echo isset($paging)?$paging:'';?>             
    </div>
    <?php }?>
  </div> 
</div>

<script>
function paging(num) {
	$('input[name=page]').val(num);
	$('#upload_calling_card').submit();
}

function confirmReset(){
	if(confirm('All the records of this Ratesheet will be deleted, would you like to proceed?')){ 		
		$('#reset').val(1);
		$('#import_products').submit();
	}else{
		return false;
	}
}

function confirmImport(){
	if(confirm('New records ready to import, would you like to proceed?')){ 		
		$('input[name=page]').val('')
		$('#import_products').submit();
	}else{
		return false;
	}
}
</script>

<?php include '_footer.php';?>
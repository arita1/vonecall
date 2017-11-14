<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Upload Calling Cards</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['image_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['image_error'];?></li></ul></dd></dl><?php }?>
  
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('calling-card-upload'), array('id'=>'upload_calling_card', 'name'=>'upload_calling_card'));?>
      
      <label>Batch:</label>
	  <?php echo form_dropdown('batchID', $option_batch, (isset($batchID)?$batchID:''), 'class="w327" id="productType"');?>
	  <?php if (isset($error['batchID'])) {?><span class="red_color"><?php echo $error['batchID'];?></span><?php }?>
	  <div class="cb"></div>
	  	                
      <label>Upload Calling Card: </label>
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/> 
      <input type="file" name="callingCards" id="file" />
      <?php if (isset($error['callingCards'])) {?><span class="red_color"><?php echo $error['callingCards'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <strong style="margin: 0 10px 0 0;"> Overwrite </strong> <input type="radio" name="importType" value="overwrite" />
      <strong style="margin: 0 10px 0 20px;"> Append </strong> <input type="radio" name="importType" value="append" checked="" />                 
      <?php if (isset($error['importType'])) {?><span class="red_color"><?php echo $error['importType'];?></span><?php }?>      
      <div class="cb"></div>
      
      <label> &nbsp; <!--<a class="bt_save float_left" href="javascript:void(0);" onclick="window.location.href = '<?php echo site_url('calling-card-batch') ?>'" >Add Batch</a>--></label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#upload_calling_card').submit();" >Upload</a>
      <div class="cb"></div>
      <br>
      <?php echo form_close();?>
    </div>
    <?php if(isset($results)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Batch </td>
          <td align="center" class="bg_table boder_right"> PIN </td>
          <td align="center" class="bg_table boder_right"> Denominaiton </td>
          <td align="center" class="bg_table boder_right"> Purchase Date </td>
          <td align="center" class="bg_table"> Purchase Store </td>           
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->batchName;?></td>
          <td class="boder_right"><?php echo $item->callingCardPin;?></td>
          <td class="boder_right"><?php echo format_price($item->batchAmount);?></td>
          <td class="boder_right"><?php echo ($item->callingCardPurchaseDate)?date('m/d/Y', strtotime($item->callingCardPurchaseDate)):'';?></td>
          <td class=""><?php echo $item->callingCardPurchaseStoreName;?></td>
        </tr>
        <?php $i++;?>
        <?php }?> 
        <?php } else {?>
        <tr>
          <td colspan="7"><?php echo $this->lang->line('empty_results');?></td>
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
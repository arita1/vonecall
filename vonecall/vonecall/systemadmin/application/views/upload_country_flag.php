<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Upload Country Flag</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php if (isset($error['image_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['image_error'];?></li></ul></dd></dl><?php }?>
  
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('country-flag-upload'), array('id'=>'upload_country_flag', 'name'=>'upload_country_flag'));?>
      <label>Product List:</label>
	  <?php echo form_dropdown('countryCode', $optionCountry, (isset($countryCode)?$countryCode:''), 'class="w327" id="countryCode"');?>
	  <?php if (isset($error['countryCode'])) {?><span class="red_color"><?php echo $error['countryCode'];?></span><?php }?>
	  <div class="cb"></div>
	                
      <label>Upload Image: </label>
      <input type="file" name="flag" id="file" />
      <?php if (isset($error['flag'])) {?><span class="red_color"><?php echo $error['flag'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#upload_country_flag').submit();" >Upload</a>
      <div class="cb"></div>
      <br>
      <?php echo form_close();?>
    </div>
    <?php if(isset($results)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Country </td>
          <td align="center" class="bg_table boder_right"> Country Flag </td>           
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->CountryName;?></td>
          <td class="boder_right"><img src="<?php echo $this->config->item('base_url')?>public/uploads/country_flag/<?php echo $item->countryFlag;?>" alt="<?php echo $item->countryFlag?$item->CountryName:'NA';?>" width="200px" /></td>                 
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
	$('#reset').val(0);
	$('input[name=page]').val(num);
	$('#import_products').submit();
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
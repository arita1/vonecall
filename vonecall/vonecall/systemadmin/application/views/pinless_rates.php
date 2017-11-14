<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Import Rates</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('pinless-rates'), array('id'=>'import_rates', 'name'=>'import_rates'));?>
                  
      <label>Upload File: (.csv)</label>
      <input type="hidden" name="import" value="1" />
      <input type="hidden" name="reset" id="reset" value="0" />
      <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/> 
      <input type="file" name="rates" id="rates" />
      <?php if (isset($error['rates'])) {?><span class="red_color"><?php echo $error['rates'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#import_rates').submit();$('input[name=page]').val('')">Import</a>
      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
   
    <!--<div class="bg_title_content"> Rate List </div>
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('pinless-rates'), array('id'=>'search_rates', 'name'=>'search_rates'));?>
       	<label>Country</label></td>
	    	<?php echo form_dropdown('country', $option_country, (isset($country)?$country:''), 'class="w132"');?>
	    	<div class="cb"></div>
	    <label> Balance </label>
	        <?php echo form_dropdown('balance', $option_balance, (isset($balance)?$balance:''), 'class="w132"');?>
	    	<div class="cb"></div>
	    <label>&nbsp;</label>
	    	<a class="bt_save float_left" href="javascript:void(0);" onclick="$('#search_rates').submit();">Search</a>
	      	<div class="cb"></div>
      <?php echo form_close();?>
    </div>
    -->
    <?php if(isset($get_rates)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="50" align="center" class="bg_table boder_right">ID</td>
          <td align="center" class="bg_table boder_right">Country Name</td>
          <td align="center" class="bg_table boder_right">City-Description</td>
          <td align="center" class="bg_table boder_right">Rate/Min</td>
          <td align="center" class="bg_table boder_right">  Effective Date </td>       
        </tr>
        <?php if(count($get_rates)>0) {?>
        <?php $i=1;?>
        <?php foreach($get_rates as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="center" class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo $item->destination;?></td>
          <td class="boder_right"><?php echo $item->city;?></td>
          <td class="boder_right"><?php echo $item->rate;?></td> 
          <td class="boder_right"><?php echo date('m-d-Y', strtotime($item->date_time));?></td>         
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
    <p>
    	<a onclick="window.location.href='<?php echo site_url('export-rates')?>';" href="javascript:void(0);" class="bt_atatement_address m7b">Export</a>
    	<a onclick="confirmReset()" href="javascript:void(0);" class="bt_atatement_address m7b">Reset</a>
    </p>
  </div> 
</div>

<script>
function paging(num) {
	$('#reset').val(0);
	$('input[name=page]').val(num);
	$('#import_rates').submit();
}

function confirmReset(){
	if(confirm('All the records of this Ratesheet will be deleted, would you like to proceed?')){ 		
		$('#reset').val(1);
		$('#import_rates').submit();
	}else{
		return false;
	}
}
</script>

<?php include '_footer.php';?>
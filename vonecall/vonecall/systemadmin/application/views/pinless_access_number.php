<?php include '_header.php';?>
<div id="main">
  <div class="bg_title">Import Access Number</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <?php echo form_open_multipart(site_url('pinless-access-number'), array('id'=>'import_access_number', 'name'=>'import_access_number'));?>
      <input type="hidden" name="export" id="export" value="0" />
                  
      <label>Upload File: (.csv)</label>
      <input type="file" name="accessNumber" />
      <?php if (isset($error['accessNumber'])) {?><span class="red_color"><?php echo $error['accessNumber'];?></span><?php }?>
      <div class="cb"></div>
      
      <label>&nbsp;</label>
      <strong style="margin: 0 10px 0 0;"> Overwrite </strong> <input type="radio" name="importType" value="overwrite" />
      <strong style="margin: 0 10px 0 20px;"> Append </strong> <input type="radio" name="importType" value="append" checked="" />                 
      <?php if (isset($error['importType'])) {?><span class="red_color"><?php echo $error['importType'];?></span><?php }?>      
      <div class="cb"></div>
           
      <label>&nbsp;</label>
      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#import_access_number').submit();">Import</a>

      <div class="cb"></div>
      <?php echo form_close();?>
    </div>
    
    <?php if(isset($get_access)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="50" align="center" class="bg_table boder_right">ID</td>
          <td align="center" class="bg_table boder_right">Access Number</td>
          <td align="center" class="bg_table boder_right">State</td>
          <td align="center" class="bg_table boder_right">City</td>
          <td align="center" class="bg_table boder_right">Language</td>
          <td align="center" class="bg_table boder_right">Note</td>         
        </tr>
        <?php if(count($get_access)>0) {?>
        <?php $i=1;?>
        <?php foreach($get_access as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="center" class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"><?php echo $item->AccessNumber;?></td>
          <td class="boder_right"><?php echo $item->State;?></td>
          <td class="boder_right"><?php echo $item->City;?></td> 
          <td class="boder_right"><?php echo $item->access_lang;?></td>
          <td class="boder_right"><?php echo $item->note;?></td>          
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
    <p><a onclick="$('#export').val(1);window.location.href='<?php echo site_url('export-access-record')?>';" href="javascript:void(0);" class="bt_atatement_address m7b">Export</a></p>
  </div>
</div>
<?php include '_footer.php';?>
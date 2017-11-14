<?php include APPPATH.'views/_header.php';?>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.box_makepayment .label1 {width: 200px;}
.btn_checkbox {margin-left:205px; width: 330px;}
.box_makepayment .label_short1 {width: 80px;}
.add_phone_text3 {width: 39px;}
</style>
<?php } else {?>
<style>
.box_makepayment .label1 {width: 160px;}
</style>
<?php } ?>

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac">Product List</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <?php if (isset($error['email_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['email_error'];?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      
      <!-- Search Form -->
      <div class="box_makepayment">
        <?php echo form_open_multipart(site_url('product-list'), array('id'=>'product_list', 'name'=>'product_list'));?>
        <?php if (isset($error['productName'])) {?><span class="red_color"><?php echo $error['productName'];?></span><?php }?>
        <label style="width: 161px;" class="label_short1">Search Product By Name: </label>
        <?php echo form_dropdown('productName', $option_productName, (isset($productName)?$productName:''), 'class="w327" style="float:left"');?>
        <a class="bt_submit4 float_left m10l" href="javascript:void(0);" onclick="$('#product_list').submit();">Search</a>
        <br class="cb">   
        
        <label style="width: 161px;" class="label_short1">&nbsp;</label>
        <a class="bt_submit4" href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('export-list')?>';" style="margin-bottom: 7px;">Download List</a>
        <?php echo form_close();?>
      </div>
      
       
      <!-- Display Results -->
      <?php if (isset($results)) {?>
      <div class="box_phonenumber p10l p10r p10t">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
              <td width="20%" align="left" class="boder_right"> <strong><?php echo $this->lang->line('product_name');?></strong></td>
              <td width="20%" align="left" class="boder_right"> <strong>Sku Name</strong></td>
              <td width="12%" align="right" class="boder_right"><strong><?php echo $this->lang->line('product_type');?></strong></td>
              <td width="12%" align="left" class="boder_right"><strong><?php echo $this->lang->line('commission_rate2');?></strong></td>              
            </tr>
            <?php if(count($results)>0) { ?>
            <?php $i=1; foreach($results as $item) { ?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td align="left" class="boder_right"><?php echo $item->vproductName;?></td>
              <td align="left" class="boder_right"><?php echo $item->vproductSkuName;?></td>
              <td align="right" class="boder_right"><?php echo $item->vproductCategory?></td>
              <td align="right" class="boder_right"><?php echo number_format($item->commissionRate, 2);?>%</td>              
            </tr>
            <?php $i++; }?>
	        <?php } else {?>
            <tr>
              <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <div class="cb"></div>
      </div>
      <?php }?>
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
 
<?php include APPPATH.'views/_footer.php';?>
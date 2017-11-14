<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang->line('header_title');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.1.11.1.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.colorbox.js"></script>
<style>
	#main {
    margin: 15px auto 0;
    min-height: 450px;
    overflow: hidden;
    padding: 0;
    width: 980px;
}
#header, #nav, #main, #footer {
    clear: both;
}
body{
	min-width: 100%;
}

.bg_tt_page .ac {
    color: #4b4b4b;
    float: left;
    font-size: 15px;
    font-weight: bold;
    line-height: 37px;
    margin-left: 28px; 
    padding-left: 12px;
    width: 786px;
}
</style>
<body style="background: none">
<div class="p10b" id="main" style="padding-top: 0;">
  <div class="bg_tt_page"><div class="ac">Commission Rates</div></div>
  <div class="center_page_afterlogin" style="border-left: 1px solid #B3D7E0; border-right: 1px solid #B3D7E0; border-bottom: 1px solid #B3D7E0; padding-bottom: 15px;">
   	  <div class="box_quicksearch" style="padding-left: 5px;">
          <?php echo form_open_multipart(site_url('popup-commission-rate'), array('id'=>'commission_rate_form', 'name'=>'commission_rate_form'));?>
          	<input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
          <?php echo form_close();?>
        </div>
      <div class="box_rate p10l p10r p10t">
        <div class="box_phonenumber p10l p10r p10t">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
              <td class="boder_right"><strong><?php echo $this->lang->line('product_name');?></strong></td>
              <td class="boder_right"><strong><?php echo $this->lang->line('product_type');?></strong></td>
              <td><strong><?php echo $this->lang->line('commission_rate2');?></strong></td>
            </tr>
          
            <?php if(count($results)>0) { ?>
            <?php $i=1;?>
            <?php foreach($results as $item) {?>
            <?php if ($item->productID != $this->config->item('product_topup')) {?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td class="boder_right"><?php echo $item->vproductName;?></td>
              <td class="boder_right"><?php echo $item->vproductCategory;?></td>
              <td><?php echo number_format($item->commissionRate, 2);?> %</td>
            </tr>
            <?php $i++;?>
            <?php }?>
            <?php }?>
            <?php } else {?>
            <tr>
              <td colspan="2"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <?php echo isset($paging)?$paging:'';?>
        <div class="cb"></div>
      </div>
      </div>
    
    <div class="cb"></div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
<script>
function paging(num) {
	$('input[name=page]').val(num);
	$('#commission_rate_form').submit();
}
</script>
</body>
</html>
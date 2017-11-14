<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang->line('header_title');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/dataTables.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function($){
		$('#accessnumber_table').dataTable({
			"sPaginationType" : "full_numbers",
			"aaSorting" : [[0, "asc"]],			
		});
	} );
</script>
<style>
	.even {background-color: #FFFFFF !important;}
	.odd {background-color: #DFDEDB !important;}
</style>

<body style="background: none">
<div class="p10b" id="main" style="padding-top: 0;">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('access_number');?></div></div>
  <div class="center_page_afterlogin" style="border-left: 1px solid #B3D7E0; border-right: 1px solid #B3D7E0; border-bottom: 1px solid #B3D7E0; padding-bottom: 15px;">
    <div class="box_phonenumber p10l p10r p10t">
        <!--<table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
			  <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('state');?></strong></td>
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('city');?></strong></td>
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('access_number');?> </strong></td>                
            </tr>
            <?php $i=1;foreach($results as $item) {?>
                  <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                    <td width="25%" class="boder_right"><?php echo $item->state;?></td>
                    <td width="25%" class="boder_right"><?php echo $item->city;?></td>
                    <td width="25%" class="boder_right"><?php echo format_phone_number($item->access_number);?></td>
                  </tr>
            <?php $i++;}?>
          </tbody>
        </table>-->
		
		<!-- Add table with Datatables 28_Feb -->
		  <table id="accessnumber_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="float: left;">
			<thead>
			<tr class="bg_table" style="text-align: center;">
				<th class="bg_table boder_right"><strong><?php echo $this->lang->line('access_number');?> </strong></th>
				<th class="bg_table boder_right"><strong><?php echo $this->lang->line('city');?></strong></th>
			  	<th class="bg_table boder_right" style="padding: 7px 10px;" class="bg_table boder_right"><strong><?php echo $this->lang->line('state');?></strong> </th>
			  	<th><strong><?php echo $this->lang->line('language');?></strong></th>		  
			</tr>
			</thead>
			<tbody>
			<?php if(count($results)>0) {?>
			<?php $i=1;foreach($results as $item) {?>
			<tr>
				<td class="boder_right"><?php echo format_phone_number($item->AccessNumber);?></td>
				<td class="boder_right"><?php echo $item->City;?></td>
			  	<td class="boder_right"><?php echo $item->State;?></td>
			  	<td class="boder_right"><?php echo $item->access_lang;?></td>		          
			</tr>
			<?php  $i++;}?>
			<?php } else {?>
			<tr>
			  <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
			</tr>
			<?php }?>
			</tbody>
		  </table>
		  <!-- END -->
		  
        <?php echo isset($paging)?$paging:'';?>
        <div class="cb"></div>
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
	$('#rate_form').submit();
}
</script>
</body>
</html>
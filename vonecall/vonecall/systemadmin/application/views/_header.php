<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('site_name')?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/dataTables.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.1.11.1.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/phone.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.datetimepicker.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/tiny_mce/common_editior.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.maskedinput.min.js"></script>

</head>
<!-- javascript coding -->
<script>
/*
$(document).ready(function(){
	//$(".lang_wrapper1 a").click(function () {
	$("#choose").click(function () {
		$(".choose_lang").slideToggle();
	});
	$("#nav_main li").hover(
		function () {
			$(this).addClass("hover");
		},
		function () {
			$(this).removeClass("hover");
		}
	);	
	
	// Phone Number Masking
	$("input[name=phone]").mask("(999) 999-9999");
	$("input[name=cellphone]").mask("(999) 999-9999");			
});*/
</script>

<body>
<div id="page_margins">
  <div id="header">
    <div class="w322 float_left p10t p40l" style="width: 210px;">
    	<img src="<?php echo $this->config->item('base_url')?>public/images/Logo.png" style="width:100%;" />
    </div>
    <div class="w322 float_right p40r">
      <div class="box_singup"><span><a href="<?php echo site_url('logout');?>">Logout</a></span></div>
      <div class="menu_admin_login font15">Welcome <span class="yellow_color"><?php echo $this->session->userdata('username');?></span></div>
      <div class="cb"></div>
      <div class="float_right  p20t">
        <div class="laguage_show">
          <a href="javascript:void(0);" id="choose">English</a>
          <div class="choose_lang" onclick="">Spanish</div>
        </div>
      </div>
    </div>
    <div class="cb"></div>
  </div>
  <div id="nav_top">
    <div id="nav_main">
      <div class="nav_home"><a href="<?php echo site_url('destributor-manager');?>"><img src="<?php echo $this->config->item('base_url')?>public/images/icon_home.png"/></a></div>
      <ul>   
      	<?php if($this->session->userdata('manage_admin') || $this->session->userdata('manage_distributor') || $this->session->userdata('manage_store') || $this->session->userdata('manage_product') || ($this->session->userdata('usertype')=='admin') ||  ($this->session->userdata('usertype') == 'super-admin') ){ ?>   	
      	<li <?php if($current_page=='rep' || $current_page=='admin' || $current_page=='agent' || $current_page=='sub-dist' || $current_page=='calling_card') echo 'class="current"';?>><a href="javascript:void(0);"><span> Manage </span> </a> 
      		<ul class="nav-2">      			
      			<li> <a href="javascript:void(0)"> Users </a> 
      				<ul class="nav-3">
		      			<?php if($this->session->userdata('manage_admin') || ($this->session->userdata('usertype')== ('admin' || 'super-admin') )) {?> 
		      				<li> <a href="<?php echo site_url('admin-manager');?>"> Admin </a> </li> 
		      			<?php }
						if($this->session->userdata('manage_distributor') || ($this->session->userdata('usertype')== ('admin' || 'super-admin') )) {?> 
		      				<li> <a href="<?php echo site_url('destributor-manager');?>"> Distributors </a> </li> 
		      				<!--<li> <a href="<?php echo site_url('sub-destributor');?>"> Sub-Distributors </a> </li>--> 
		      			<?php }
						if($this->session->userdata('manage_store') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?> 
		      				<li> <a href="<?php echo site_url('store-manager');?>"> Stores </a> </li> 
		      			<?php }
		      			if($this->session->userdata('usertype')=='admin') {?> 
		      				<li> <a href="<?php echo site_url('customer-manager');?>"> Customers </a> </li> 
		      			<?php }?>
	      			</ul>
      			</li>
      			<?php if($this->session->userdata('manage_product') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?> 
      				<li> <a href="javascript:void(0)"> Products </a> 
      					<ul class="nav-3">
      						<li> <a href="<?php echo site_url('product');?>"> All Products </a> </li>
      						<li> <a href="<?php echo site_url('product-type');?>"> Product List </a> </li>
      					</ul>
      				</li> 
      			<?php }?>
      			<?php if($this->session->userdata('manage_product') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?> 
      				<li> <a href="javascript:void(0)"> Calling Cards </a> 
      					<ul class="nav-3">
      						<li> <a href="<?php echo site_url('calling-cards');?>"> All Calling Cards </a> </li>
      						<li> <a href="<?php echo site_url('card-batch');?>"> Calling Card Batch </a> </li>
      						<li> <a href="<?php echo site_url('calling-cards-instructions');?>"> Calling Card Instructions </a> </li>
      						<li> <a href="<?php echo site_url('upload-local-access');?>"> Upload Local Access Number </a> </li>
      					</ul>
      				</li> 
      			<?php }?>
      		</ul>	
      	</li>
      	<?php }?>
      	<?php if($this->session->userdata('reports') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?>
      		<li <?php if($current_page=='reports') echo 'class="current"';?>> <a href="<?php echo site_url('reports/sales');?>"> Reports </a></li>
      	<?php }		
      	if($this->session->userdata('usertype')==('admin' || 'super-admin')) { ?>      	
      		<li <?php if($current_page=='payment') echo 'class="current"';?>> <a href="javascript:void(0)"> <span> Payment </span> </a> 
      			<ul class="nav-2">	      			
				    <li> <a href="<?php echo site_url('authorize-mode');?>"> Authorize.net Mode </a>				    
	      		</ul>
      		</li>
      	<?php } ?>
      		<li <?php if($current_page=='promotion') echo 'class="current"';?>> <a href="javascript:void(0)"> <span> Promotion </span> </a> 
      			<ul class="nav-2">	      			
				    <li> <a href="<?php echo site_url('promotion');?>"> All Promotions </a> 
				    <li> <a href="<?php echo site_url('promotion-message');?>"> Message </a>
	      		</ul>
      		</li>
      	<?php ?>      	      
      	<li <?php if($current_page=='portaone' || $current_page=='admin_password' || $current_page=='uploads' || $current_page=='testing' || $current_page=='admin') echo 'class="current"';?>><a href="javascript:void(0)"> <span> Admin </span> </a> 
      		<ul class="nav-2">
      			<li> <a href="javascript:void(0)"> Test </a> 
      				<ul class="nav-3">
		      			<li> <a href="<?php echo site_url('portaone');?>"> Pinless </a> </li>
		      			<li> <a href="<?php echo site_url('ppn');?>"> Topup </a> </li>
		      			<li> <a href="<?php echo site_url('portaone/account_reports');?>"> Reports </a> </li>	
		      			<li> <a href="<?php echo site_url('test-commission');?>"> Commission </a> </li>		      			
	      			</ul>
      			</li>
      			<?php if($this->session->userdata('admin_password') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?>
      				<li><a href="<?php echo site_url('admin-change-password');?>"> Change Password </a> </li>
      			<?php }
      			if($this->session->userdata('admin_log') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?>
      				<li> <a href="<?php echo site_url('admin-logs');?>"> Admin Logs </a> </li>
      			<?php }
      			if($this->session->userdata('admin_upload') || ($this->session->userdata('usertype')==('admin' || 'super-admin'))) {?>
      				<li> <a href="javascript:void(0)"> Uploads  </a> 
			      		<ul class="nav-3">
			      			<li> <a href="<?php echo site_url('calling-card-upload');?>"> Calling Cards </a> </li>
			      			<li> <a href="<?php echo site_url('country-flag-upload');?>"> Country Flag </a> </li>
			      			<li> <a href="<?php echo site_url('pinless-access-number');?>"> Pinless Access Numbers </a> </li>
			      			<li> <a href="<?php echo site_url('pinless-rates');?>"> Pinless Ratesheet </a> </li>			      			
			      			<li> <a href="<?php echo site_url('products-upload');?>"> Products </a> </li>	
			      			<li> <a href="<?php echo site_url('product-logo-upload');?>"> Product Logo </a> </li>		      					      			
			      		</ul>
			      	</li>
			    <?php }?>
			    <li> <a href="<?php echo $this->config->item('sms_url').'/sms/login?adminlogin=1&user='.$this->session->userdata('userid');?>"> SMS </a>
			    <li> <a href="<?php echo site_url('general-settings');?>"> General Settings </a> </li>
			    <?php if($this->session->userdata('usertype')==('super-admin')) {?>
			    <li> <a href="<?php echo site_url('add-funds');?>"> Add Fund </a> </li>
			    <?php }?> 
      		</ul>
      	</li>
      	
      </ul>
    </div>
    <div class="cb"></div>
  </div>
  <div id="page">
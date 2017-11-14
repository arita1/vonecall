<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('site_name');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/jquery.datepick.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/colorbox.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/dataTables.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.1.11.1.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.colorbox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.datetimepicker.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.datepick.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/phone.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.maskedinput.min.js"></script>
</head>
<!-- javascript coding -->
<script>
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
});
</script>

<body>
<div id="page_margins">
  <div id="header">
    <div class="w322 float_left p10t p40l" style="width: 210px;" >
    	<img src="<?php echo $this->config->item('base_url')?>public/images/Logo.png" style="width:100%;"/>
    </div>
    <div class="w322 float_right p40r">
      <div class="box_singup"><span><a href="<?php echo site_url('logout');?>">Logout</a></span></div>
      <div class="menu_admin_login font15">Welcome <span class="yellow_color"><?php echo $this->session->userdata('rep_username');?></span></div>
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
      <div class="nav_home"><a href="<?php echo site_url('home');?>"><img src="<?php echo $this->config->item('base_url')?>public/images/icon_home.png"/></a></div>
      <ul>
        <li <?php if($current_page=='agent') echo 'class="current"';?>><a href="javascript:void(0);"><span>Store</span></a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('add-new-store');?>">Add New Store</a></li>
            <li><a href="<?php echo site_url('store-account-manager');?>">Store Account Manager</a></li>
          </ul>
        </li>
        <?php if($this->session->userdata('rep_usertype') != 'Sub-Distributor'){ ?>
        <li <?php if($current_page=='subDis') echo 'class="current"';?>><a href="javascript:void(0);"><span>Sub-Distributor</span></a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('add-new-distributor');?>">Add New Sub-distributor</a></li>
            <li><a href="<?php echo site_url('sub-distributor-account-manager');?>">Sub-distributors Account Manager</a></li>
            <li><a href="<?php echo site_url('sub-distributor-stores');?>">Sub-distributors Store Manager</a></li>
          </ul>
        </li>
        <?php }?>
        
        <li <?php if($current_page=='reports') echo 'class="current"';?>><a href="<?php echo site_url('reports');?>"> Reports </a></li>
        
        <?php if($this->session->userdata('rep_usertype') != 'Sub-Distributor'){ ?>
        <li <?php if($current_page=='payment') echo 'class="current"';?>><a href="javascript:void(0);"> <span> Payment </span> </a>
        	<ul class="nav-2">
	            <li><a href="<?php echo site_url('payment');?>"> Fund Your Account </a></li>
	            <li><a href="<?php echo site_url('payment-adjust');?>"> Transfer Fund to Store </a></li>
	        </ul>
        </li>
       
        <li <?php if($current_page=='admin') echo 'class="current"';?>><a href="javascript:void(0);"><span>admin</span></a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('change-password');?>">Change Password</a></li>
            <li><a href="<?php echo site_url('profile');?>">My Profile</a></li>            
          </ul>
        </li>
        <?php }?>
        
        <!-- If Super admin logged in to account -->
        <?php if($this->session->userdata('redirect')){ ?>
        	<li><a href="<?php echo site_url('return-to-admin');?>"> Return To Admin </a> </li>			
        <?php } ?>
        
      </ul>
    </div>
    <div class="cb"></div>
  </div>
  <div id="page">
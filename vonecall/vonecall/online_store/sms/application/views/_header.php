<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('site_name');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/jquery.datepick.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.1.11.1.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.datepick.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/phone.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/charecterCounter.js"></script>
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
});
</script>

<body>
<div id="page_margins">
  <div id="header">
    <div class="w322 float_left p10t p40l" style="width: 210px;" >
    	<img src="<?php echo $this->config->item('base_url')?>public/images/logo.png" style="width:100%;"/>
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
      <div class="nav_home"><a href="<?php echo site_url('home');?>"><img src="<?php echo $this->config->item('base_url')?>public/images/icon_home.png"/></a></div>
      <ul>
        <li <?php if($current_page=='group') echo 'class="current"';?>><a href="javascript:void(0);"><span>Groups</span></a>
          <ul class="nav-2">            
            <li><a href="<?php echo site_url('all-groups');?>">All Groups</a></li>
          </ul>
        </li>
        
        <li <?php if($current_page=='contact') echo 'class="current"';?>><a href="javascript:void(0);"><span>Contacts</span></a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('contacts');?>">Contacts</a></li>           
          </ul>
        </li>
        <li <?php if($current_page=='send_text') echo 'class="current"';?>><a href="<?php echo site_url('send-text-message');?>"> Send Text </a></li>
        <li <?php if($current_page=='settings') echo 'class="current"';?>><a href="javascript:void(0);"><span>Settings</span></a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('text-sender');?>">Text Sender</a></li>           
          </ul>
        </li>
        <?php if($this->session->userdata('redirect')){?>
        	<li><a href="<?php echo $this->config->item('sms_url').'/systemadmin/login?user='.$this->session->userdata('sms_userid');?>"> Return to Admin </a></li>
        <?php }?>
      </ul>
    </div>
    <div class="cb"></div>
  </div>
  <div id="page">
  	
<!-- Header Start -->

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
<style>
	.opt_out{
		color: #fd9002;
	    float: inherit;
	    font-size: 30px;
	    padding: 2%;
	    text-align: center;
	    width: 96%;
	    margin-top: 90px;
	}
</style>

<body>
<div id="page_margins">
  <div id="header">
    <div class="w322 float_left p10t p40l" style="width: 210px;" >
    	<img src="<?php echo $this->config->item('base_url')?>public/images/logo.png" style="width:100%;"/>
    </div>
    <div class="w322 float_right p40r">
     
    </div>
    <div class="cb"></div>
  </div>
  <div id="page">

<!-- Header END -->
<div id="main">
  <div class="bg_title">Opt Out Success</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top">
      	<div class="p20l p20r p13b">
	      <div class="opt_out">
	      	<p> Your Contact number is now Opt Out from our server. </p>
	      </div>      
        </div>
      </td>
    </tr>
  </table>
  <div class="cb"></div>
</div>

<?php include '_footer.php';?>
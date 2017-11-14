<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('site_name');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.js"></script>
<body>
<div id="page_margins">
  <div id="page_login">
    <div class="box_login">
      <div class="log p60t"><?php if (isset($message)) {?><dl id="system-message" style=""><dd class="message error"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
      	
        <?php echo form_open_multipart(site_url('login'), array('id'=>'form-login', 'name'=>'form-login'))?>
        <label>Login ID</label>
        <input class="txtlogin" name="username" type="text" value="<?php echo isset($username)?$username:'';?>"/>
        <label>Password</label>
        <input class="txtlogin" name="password" type="password"/>
        <a href="javascript:void(0);" onclick="$('#form-login').submit();"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_login.jpg" alt="" /></a>
        <?php form_close();?>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
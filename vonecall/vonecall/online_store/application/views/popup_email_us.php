<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang->line('header_title');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.js"></script>
<style>
	.red_color{
		color: red;
		font-weight: bold;
	}
</style>
<body style="background: none">
<div class="p10b" id="main" style="padding-top: 0; width: 550px;">
  <div class="bg_tt_page"><div class="ac">Email Us</div></div>
  <div class="center_page_afterlogin" style="border-left: 1px solid #B3D7E0; border-right: 1px solid #B3D7E0; border-bottom: 1px solid #B3D7E0; padding-bottom: 15px;">
    
      <div class="box_rate p10l p10r p10t">
        <div class="box_quicksearch" style="padding-left: 5px;">
          <?php echo form_open_multipart(site_url('email-us/'.$emailTo), array('id'=>'email_us_form', 'name'=>'email_us_form'));?>
          <table width="100%" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td style="width: 75px;"> Subject </td>
                <td> <input type="text" name="subject" value="<?php echo isset($subject)?$subject:''?>" /> 
                     <?php if (isset($error['subject'])) {?><span class="red_color"><?php echo $error['subject'];?></span><div class="cb"></div><?php }?> 
                </td>
              </tr>
              <tr>
                <td> Message </td>
                <td> <textarea name="message" cols="30" rows="4" > <?php echo isset($message)?$message:''?> </textarea> 
                     <?php if (isset($error['message'])) {?><span class="red_color"><?php echo $error['message'];?></span><div class="cb"></div><?php }?>
              </tr>
              <tr>
                <td> &nbsp; </td>
              	<td colspan="2"> <a href="javascript:void(0);" class="bt_submit" onclick="$('#email_us_form').submit();"><?php echo $this->lang->line('submit');?></a> </td>
              </tr>
            </tbody>
          </table>
          <?php echo form_close();?>
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
	$('#rate_form').submit();
}
</script>
</body>
</html>
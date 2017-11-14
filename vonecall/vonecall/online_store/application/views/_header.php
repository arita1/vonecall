<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang->line('header_title');?></title>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/colorbox.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item('base_url')?>public/css/dataTables.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.1.11.1.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/combobox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.colorbox.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.datetimepicker.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/phone.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.validate.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.dataTables.js"></script>
<!-- javascript coding -->
<script type="text/javascript">
$(document).ready(function(){
  $("#choose").click(function () {
    $(".choose_lang1").slideToggle();
    $(".choose_lang2").slideToggle();
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
function set_language(lang) {
  $.post("<?php echo site_url('index/language');?>/"+lang, {},
      function(data) {
      window.location.reload();
      }
    );
}

function send_email(emailTo){
  
  $.colorbox({ href: "<?php echo site_url('email-us');?>/"+emailTo, width: "800px", height: "400px", iframe: true, scrolling: true });

}
</script>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.contact_line {width: 458px;}
.nav{ margin: 0;
    background: #fff;
    font-family: 'Open Sans', sans-serif;
}


</style>
<?php }?>
</head>

<body>
  
 
<div id="page_margins">
  <nav class="navbar navbar-fixed-top scrolled_header" style="
    margin: 0; width:100%; padding-left: 0;list-style: none;float: left;
    height:100px;font-size: 14px; padding-bottom:13px;
    line-height: 2.428571;
    color: #333;
    background-color: #fff;
    right: 0;
    left: 0;
    z-index: 1030;
    box-shadow: 0 20px 25px -12px rgba(0,0,0,.09);
    transition: 0.8s ease-in-out;margin-top: -3px;">
    <div class="container" style="padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;clear:both;">
   
      <div class="site-branding" style="box-sizing: border-box; background-color: white; float:left;display: inline-block;">
                    <a style=";display: inline-block;margin-bottom: -112px;padding-left: 0px;" href="https://www.vonecall.com/vonecall/" class="custom-logo-link" rel="home" itemprop="url">
                      <img width="240" height="119" src="https://www.vonecall.com/vonecall/wp-content/uploads/cropped-vonecall-logo-1.png" class="custom-logo" alt="" itemprop="logo"  style="padding-left:30px;"></a>                </div>
                    <div  style="display: inline-block;float:left;padding-top: 69px;
   padding-left: 18px;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Open Sans', sans-serif;">
        <ul  style="list-style-type: none; display: inline-flex;">
          <div style="margin-left: -35px;" >
            <ul  style="list-style-type: none; display: inline-flex;">

            <li ><a style=";display: block; color:##3f8ed9!important;    text-decoration: none;" href="https://www.vonecall.com/vonecall/" class="menu-image-title-after"><span class="menu-image-title">Home</span></a></li>

<li ><a style="display: block;color:#444!important;    text-decoration: none;" href="https://www.vonecall.com/vonecall/#rates-success" ><span >Rates</span></a></li>

<li ><a style="display: block;color:#444!important ;   text-decoration: none;    background-color: #fff;" href="https://www.vonecall.com/vonecall/#rates-success" ><span >Access Numbers</span></a></li>

<li ><a style="display: block; color:#444!important;    text-decoration: none;" href="https://www.vonecall.com/vonecall/how-it-works/" ><span >How It Works</span></a></li>

<li ><a style="display: block; color:#444!important;    text-decoration: none;" href="https://www.vonecall.com/vonecall/about/">About Us</span></a></li>

<li ><a style="display: block; color:#444!important;    text-decoration: none;" href="https://www.vonecall.com/vonecall/contact/" ><span >Contact Us</span></a></li>
</ul></div>        </ul>
        <span class="login-menu" style="display: inline-block;float:right; color: #444; padding-left:10px;">
             
         <ul id="log_menu" style="list-style-type: none;cursor:default; ">
        <div style="margin-left:107px;margin-bottom: 4px;">
          
    &nbsp;<b > Welcome  &nbsp;<span style="color:#0171ab;"><?php echo $this->session->userdata('store_username');?></span> store</b>  &nbsp;<img height="10" src="<?php echo $this->config->item('base_url')?>public/images/menu-down.jpg" alt=""/>
          <img height="25px" width="30px" style="margin-top:3px;float:left;" src="http://www.vonecall.com/vonecall//wp-content/uploads/store-page.png"/>
            <li style="display:none"><div style="
    height: 36px;
    background-color: rgb(0, 156, 204);
   
    width: 134px;float: right;">
        <span><a style="color:white;text-decoration: none;
    font-weight: bold;font-size:16PX !important;
    line-height: 35px;
    margin-left: 18px;" href="<?php echo site_url('logout'); ?>">Log Out</a></span>
        <img height="25" style="float: left;padding-left: 15px;
    padding-top: 5px;"  src="<?php echo $this->config->item('base_url')?>public/images/logout.png"/>
      </div></li>
</div> 
</ul>      
</span>
 </div>
   
  </div>
  </nav>

  <div id="container">
    
  <div id="header">
    <div class="w322 float_left p12t">
     <div style="margin-top: 41px;">
          <img src="<?php echo $this->config->item('base_url')?>public/images/icon_phone.png" alt="" class="float_left p10r"/>
            <div class="contact_line"><?php echo $this->lang->line('customer_services_24hr');?>:<span class="red_color"><strong> <?php echo format_phone_number($this->session->userdata('header_contact'))?></strong></span></div>
        </div></div>
   <!--  <div class="float_right">
      <div style="
    height: 36px;
    background-color: rgb(0, 156, 204);
   
    width: 134px;float: right;">
        <span><a style="color:white;text-decoration: none;
    font-weight: bold;font-size:16PX !important;
    line-height: 35px;
    margin-left: 18px;" href="<?php echo site_url('logout');?>">Log Out</a></span>
        <img height="25" style="float: left;padding-left: 15px;
    padding-top: 5px;"  src="<?php echo $this->config->item('base_url')?>public/images/logout.png"/>
      </div>
     <div class="box_singup"><img height="50" style="background:black;" src="logout.png"/> <span><a href="<?php echo site_url('logout');?>"><?php echo $this->lang->line('logout');?></a></span></div>-->
    <!--   <div class="menu_reseller_login font15"><?php echo $this->lang->line('welcome');?> <span class="yellow_color"><?php echo $this->session->userdata('store_username');?></span></div>
      <div class="cb"></div> -->
      <div class="float_right  p20t">
        <!-- Contact Phone Number line -->        
       <!--  <div class="laguage_show float_right"><a href="javascript:void(0);" id="choose"><?php echo $this->lang->line('lang_title');?></a>
          <div class="choose_lang1" onclick="set_language('<?php echo $this->lang->line('lang_key1');?>');"><?php echo $this->lang->line('lang_title1');?></div>
        </div>      
      </div>
 -->
   
       
        <!-- Contact Phone Number line END -->
        
        <div class="show" style="margin-top: 35px !important;">
          <!-- Contact With Email -->
          <select style="float: right; margin-right: 0; width: 138px;" onchange="send_email(this.value)">
            <option value="">-- Email To --</option>
            <option value="1">Distributor</option>
            <option value="2">Customer Service</option>
          </select>
                 
          <div class="float_right p10r p6t" style="width: 110px;">
            <img src="<?php echo $this->config->item('base_url')?>public/images/email_icon.png" alt="" class="float_left p10r" width="24px"/>
              <div class="contact_line_email">Email Us:<span class="red_color"><strong>  </strong></span></div>
          </div>
        <!-- Contact With Email END -->
        </div>
    
    </div>
    <div class="cb"></div>
  </div>
  <div id="nav_top">
    <div id="nav_main">
      <div class="nav_home"><a href="<?php echo site_url('home');?>"><img src="<?php echo $this->config->item('base_url')?>public/images/icon_home.png"/></a></div>
      <ul>
        <li <?php if($current_page=='pinless') echo 'class="current"';?>> <a href="<?php echo site_url('pinless');?>"> <?php echo $this->lang->line('pinless');?></a> </li>       
     <!--     <li <?php // if($current_page=='topup') echo 'class="current"';?>><a href="javascript:void(0);"><span><?php// echo $this->lang->line('topup');?></span></a>
          <ul class="nav-2"> -->
   <!--     <li><a href="<?php //echo site_url('topup');?>"><?php //echo $this->lang->line('international_top');?></a></li>  -->
         <!--    <li><a href="<?php //echo site_url('topup-usa-rtr');?>"><?php //echo $this->lang->line('usa_rtr');?></a></li>
            <li><a href="<?php //echo site_url('topup-usa-pin');?>"><?php //echo $this->lang->line('usa_pin');?></a></li> -->
        <!--   </ul>
        </li>  -->
       <!--   <li <?php if($current_page=='calling_card') echo 'class="current"';?>><a href="javascript:void(0);"><span><?php echo $this->lang->line('calling_card');?></span></a>
          <ul class="nav-2">
            <li><a href="<?php //echo site_url('buy-calling-card');?>"><?php //echo $this->lang->line('buy_calling_card');?></a></li>
            <li><a href="<?php //echo site_url('calling-card-history');?>"><?php //echo $this->lang->line('calling_card_history');?></a></li>
            <li><a href="<?php //echo site_url('calling-card-rate-sheet');?>"><?php //echo $this->lang->line('calling_card_rate');?></a></li>
          </ul>
        </li> -->
        <li <?php if($current_page=='report') echo 'class="current"';?>><a href="javascript:void(0);"> <span> <?php echo $this->lang->line('reports');?> </span> </a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('sales-report');?>"><?php echo $this->lang->line('sales_report');?></a></li>
            <li><a href="<?php echo site_url('commission-report');?>"><?php echo $this->lang->line('commission_report');?></a></li>
            <li><a href="<?php echo site_url('payment-report');?>"><?php echo $this->lang->line('payment_report');?></a></li>
            <li><a href="<?php echo site_url('product-list');?>"><?php echo $this->lang->line('product_list');?></a></li>
            <li><a href="<?php echo site_url('pinless-access-number');?>" class='popupBox'> Pinless Access Numbers </a></li>
            <li><a href="<?php echo site_url('pinless-rate');?>" class='popupBox' > Pinless Rate Sheet </a></li>
            <li><a href="<?php echo site_url('pinless-call-history');?>" class='popupBox'> Pinless Call History </a></li> 
            <li><a href="<?php echo site_url('pinless-activity');?>" class='popupBox'> <?php echo $this->lang->line('pinless_activity');?> </a></li>
            <li><a href="<?php echo site_url('usa-pin-activity');?>" class='popupBox'> <?php echo $this->lang->line('usa-pin_activity');?> </a></li>
          </ul>
        </li>        
        <li <?php if($current_page=='payment') echo 'class="current"';?>><a href="javascript:void(0);"> <span> <?php echo $this->lang->line('payment');?> </span> </a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('balance');?>"><?php echo $this->lang->line('store_balance');?></a></li>
            <li><a href="<?php echo site_url('payment');?>"><?php echo $this->lang->line('fund_account');?></a></li>
            <li><a href="<?php echo site_url('refund');?>"><?php echo $this->lang->line('request_refund');?></a></li>
               <li><a href="<?php echo site_url('remove');?>"><?php echo $this->lang->line('remove_cards');?></a></li>
          </ul>
        </li>
        <li <?php if($current_page=='admin') echo 'class="current"';?>><a href="javascript:void(0);"> <span> <?php echo $this->lang->line('admin');?> </span> </a>
          <ul class="nav-2">
            <li><a href="<?php echo site_url('update-password');?>"><?php echo $this->lang->line('update_password');?></a></li>
          </ul>
        </li>
        <?php if($this->session->userdata('redirect')){ ?>
          <!-- <li style="width: 150px;"><a href="<?php echo site_url('return-to-admin');?>"> Return To Admin </a> </li>      -->
        <?php } ?>
      </ul>
    </div>
    <div class="cb"></div>
  </div>
  <div id="page">
    <script type="text/javascript">
       $(document).scroll(function() {
        var fromTop = $(document).scrollTop();

        // change header background on scroll
        if (fromTop > 10) {
            $(".navbar-fixed-top").addClass("scrolled_header");
        } else {
            $(".navbar-fixed-top").removeClass("scrolled_header");
        }
    });

      $(document).ready(function(){
       
        $('#log_menu').hover(function(){
         
          $(this).find('li').css('display','flow-root');
        })
        $('#log_menu').mouseleave(function(){
         
          $(this).find('li').css('display','none');
        });


      })
    </script>
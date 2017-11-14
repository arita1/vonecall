<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

session_start();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />  

<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet" >

<!-- font awesome -->

<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet" >

<link href=" https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
 
<!-- jQuery library -->

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.1.1.min.js"></script>

<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.nicescroll.min.js"></script>

<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js " ></script>
    
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/custom.js"></script> -->
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header><!-- .site-header open -->
<div class="container">
  <nav class="navbar navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed nav-tog" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <div class="site-branding">
                    <?php twentysixteen_the_custom_logo(); ?>
                </div><!-- .site-branding -->     
 </div>
      <div id="navbar" class="navbar-collapse collapse navbar2">
        <ul class="nav navbar-nav">
          <?php wp_nav_menu( array('menu'=>'top-menu')); ?>
        </ul>
        <span class="login-menu" >
         <?php

          if(!$_SESSION['account_info']){  
           
            if(!empty($_SESSION['store'])){

           ?>
            <ul class="nav navbar-nav pull-right"  style=" margin-top: 31px;">
          
           <li > <a href="<?php echo WP_SITEURL.'online_store/home';?>" style="color:#3f8ed9; font-weight: 700;" ><span><img style="width:20px; margin: auto 10px; " src="<?php echo WP_SITEURL; ?>/wp-content/uploads/store-page.png"></span>My Store</a></li> <?php } else {?>
        <ul class="nav navbar-nav pull-right">
         
        <?php    wp_nav_menu( array('menu'=>'Reg_menu'));
          }
        }   else{  ?>
            <ul class="nav navbar-nav pull-right"  style=" margin-top: 31px;">
          
           <li > <a href="<?php echo WP_SITEURL.'/dashboard';?>" style="color:#3f8ed9; font-weight: 700;" ><span><img style="width:20px; margin: auto 10px; " src="<?php echo WP_SITEURL; ?>/wp-content/uploads/user-active.png"></span>My Account</a></li>
            <li > <a href="#logout" id="logout_header" style="color:#3f8ed9; font-weight: 700;" ><span><img style="width:20px; margin: auto 10px; " src="<?php echo WP_SITEURL; ?>/wp-content/uploads/logout.png"></span>Log Out</a></li>
           <?php // wp_nav_menu( array('menu'=>'my-account'));
         }
          ?>
        </ul>
        </span> <div id="snackbar">Login Successfully..</div></div>
    </div>
  </nav>
 </div>
</header>
<!-- .site-header -->

<div> <span class="social-network social-circle">
  <ul>
    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
    <li> <a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
    <li><a href="#" target="_blank"><i  class="fa fa-youtube"></i></a> </li>
  </ul>
  </span> </div>
<div id="content" class="site-content">

 
<!--popup-->

<!--<div class="modal fade confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="myForgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span style="font-size: 36px; color: #fff; " aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
                <h2 class="modal-title text-center"> Confirmation </h2>
            </div>
            <div class="modal-body">
            <h4>Enter your phone number to receive a link for the app -OR- <br>  <a style="cursor:pointer" target="_blank" href="">click here</a> to go to the page instead.</h4>
            <div class="form-group">
                        <label> Phone Number </label>
                        <input type="text" name="phone_number" id="phone-number" class="form-control" placeholder="" required="">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="hvr-shutter-out-horizontal" data-dismiss="modal" > Cancel </button>
                <button type="submit" class="hvr-shutter-out-horizontal" data-dismiss="modal"> Submit </button>                
            </div>
        </div>  /.modal-content 
    </div>  /.modal-dialog 
</div>  /.modal -->
<script type="text/javascript">
$(document).ready(function(){
	$('.google-play, .app-store').click(function(){
		$('#myModal').modal({
			backdrop: 'static'
		});
	}); 
});
</script>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <form id="banner_form12">
            <div class="modal-content">
                <div class="modal-header" style="background: #3f8ed9; color: white;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span style="font-size: 36px; color: #fff; " aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <h5>Enter your phone number to receive a link for the app -OR- <br>  <a style="cursor:pointer" target="_blank" href="">click here</a> to go to the page instead.</h5>
                    <div class="form-group">
                        <label> Phone Number </label>
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="" required="">
                        <input name="action" type="hidden" value="app_url_send" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hvr-shutter-out-horizontal" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="hvr-shutter-out-horizontal">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $('.dataTables_info').parent().attr('class', 'col-sm-3 col-md-3');
        $('.dataTables_info').css('margin-top','7px');
        $('.dataTables_paginate.paging_simple_numbers').parent().attr('class', 'col-sm-7 col-md-7 text-left');
    })

</script>
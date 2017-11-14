<?php
/*	  Template Name: Promotional-Deals */

if(!empty($_SESSION['account_info'])){
get_header();
  $phone = $_SESSION['phone'];
 $data   = getCustomerByPhone($phone);
 $p_image = $data[0]->customer_image;

if($p_image == Null  || empty($p_image)){

 $user_image = '../wp-content/uploads/user-defolt.png';

}else{

 $user_image = '../wp-content/uploads/'.$p_image;

}
 
   if(empty($_SESSION['account_info']->firstname)){
    $Name = $phone;
   }else{
    $Name = ucfirst($_SESSION['account_info']->firstname);
   }
?>

<div id="desh-bord">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 left_col" >
        <nav id="left-nav"  >
       <div class="scrollbar" id="style-5">
          <ul>
            <li>
              <div class="user-profile text-center"> <span><img src="<?php echo $user_image ; ?>" ></span> <span>
                <p> <?php echo $Name; ?></p>
                </span> </div>
            </li>
            <?php wp_nav_menu( array('menu'=>'dashboard_menu')); ?>
          </ul>
	</div>
        </nav>
      </div>
      <div class="deshbord-menu">
        <div class="nav_menu">
          <nav>
            <div>
              <button id="menu_toggle" class="deshbord-toggle"><i class="fa fa-bars"></i></button>
            </div>
          </nav>
        </div>
      </div>
      <div class="right_col" >
        <div class="col-md-12" style="clear: both;">
          <div class="desh-bg">
            <div class="row">
                <div class="col-md-12 text-center">
                <div class="title">
                    <h4 style="text-align: left;"> Promotional Deals</h4>
                </div>
                           
                        </div>
                
 
            </div>
              
              
              
                          <div class="row">
                <div class="col-md-6">
                    <h3 style="font-weight: 800;">Download Our App and Get $1 Free</h3>

                </div>

            <div class="col-md-6" style="display:flex; flex-wrap:wrap; justify-content:center;"> 
                    <a style="margin:10px;" href="javascript:;" class="forget google-play img-responsive" data-toggle="modal" data-target="confirmation-modal"><img src="http://www.vonecall.com/vonecall/wp-content/uploads/android.jpg" width="150px"></a>
                    <a style="margin:10px;" href="javascript:;" class="forget app-store img-responsive" data-toggle="modal" data-target="confirmation-modal"><img src="http://www.vonecall.com/vonecall/wp-content/uploads/i-phone.jpg" width="150px"></a></div>
            </div>
              
            <div class="row">
              <div class="col-md-8 col-md-offset-2">
<div class="tab-content">

          <div class="loading">

             <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">

           </div>
   
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php get_footer();
}else{
  wp_redirect(WP_SITEURL);
} ?>

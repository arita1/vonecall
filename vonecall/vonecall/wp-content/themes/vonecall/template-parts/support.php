<?php
/* Template Name: Support */
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
              <div class="col-md-12">
                <div class="title">
                  <h4 > support </h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="loading"> <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>"> </div>
              <div class="col-md-6 col-md-offset-3">
                <div class="row">
                  <div class="col-md-12 text-center ">
                    <div>
                      <h2><strong style="color:#000;"> We are here to help! </strong></h2>
                      <br>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <div class=" service-item"> <a href="contact">
                      <div ><img class="text-center" src="../wp-content/uploads/support1.png" class="img-responsive"></div>
                      <div class="service-item-content">
                        <h4> Call Us </h4>
                        <p> 855-241-0007</p>
                      </div>
                      </a> </div>
                  </div>
                  <div class="col-md-6 col-sm-6 ">
                    <div class=" service-item"> <a href="contact">
                      <div ><img  class="text-center"  src="../wp-content/uploads/support3.png"  class="img-responsive"></div>
                      <div class="service-item-content">
                        <h4> Email</h4>
                        <p><a style="color: #333;" href="mailto:Info@SkillMetrix.com">abdi.hassan@rydtechnologies.com </a></p>
                      </div>
                      </a> </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <div class=" service-item"> <a href="#">
                      <div ><img  class="text-center"  src="../wp-content/uploads/support4.png" class="img-responsive"></div>
                      <div class="service-item-content">
                        <h4> Live Chat </h4>
                        <p>Lorem ipsum dolor sit amet, placeat necessitatibus.</p>
                      </div>
                      </a> </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class=" service-item"> <a href="how-it-works">
                      <div><img class="text-center" src="../wp-content/uploads/support5.png"  class="img-responsive"></div>
                      <div class="service-item-content">
                        <h4> How It Works </h4>
                        <p> Lorem ipsum dolor sit amet, placeat necessitatibus. </p>
                      </div>
                      </a> </div>
                  </div>
                </div>
                <br>
                <br>
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

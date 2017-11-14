<?php
/* Template Name: Change-password */

get_header();

if(!empty($_SESSION['account_info'])){
get_header();
 $phone = $_SESSION['phone'];
 $data   = getCustomerByPhone($phone);
 /***to show user image or default image ****/
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
                  <h4 > Change password </h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-md-offset-3">
					<div class="tab-content">

					<div class="loading">
					<img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">
					</div>

					<form id="update_password" name="update_password" method="post" >
                    
					<div class="row" >
					    <div class="col-md-12">
                           <div class="form-group">
                          <label>Old Password </label>
			 <span class="star">*</span>
                          <br>
                          <input type="password" id="old_pass" name="old_pass" placeholder="**********" required />
                        </div>
						</div>
                     </div>
					 <div class="row" >
					    <div class="col-md-12">
                           <div class="form-group">
                          <label>New Password</label>
			 <span class="star">*</span>
                          <br>
                          <input type="password" id="new_pass" name="new_pass" placeholder="**********" required/>
                        </div>
						</div>
                     </div>
					 <div class="row" >
					    <div class="col-md-12">
                           <div class="form-group">
                          <label>Confirm Password</label>
			 <span class="star">*</span>
                          <br>
                          <input type="password" id="c_pass" name="c_pass" placeholder="**********" required />
                        </div>
						</div>
                     </div>
					  <input type="hidden" name="action" value ="update_pass" />
					  <input type="hidden" name="customerID" value="<?php echo $data[0]->customerID; ?>" />
					  <input type="hidden" name="session_pass" value="<?php echo $data[0]->password; ?>"/>
					  <div class="text-right">
                    <div class="form-group">
                      <button value="" class="hvr-shutter-out-horizontal"  type="submit" name="submit">Update Password</button >
                    </div>
                  </div>					
					</form>
					</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
//echo"session".$_SESSION['portaoneSession'];
get_footer();
}else{
	wp_redirect(WP_SITEURL);
}
?>
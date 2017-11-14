<?php
/* Template Name: Account */
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
                  <h4 >ACCOUNT/PROFILE UPDATE </h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 col-md-offset-2">
<div class="tab-content">

          <div class="loading">

             <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">

           </div>
                <form id="update_profile" name="update_profile" method="post" enctype="multipart/enctype">
                  <div class="persnal-detail">
                  <div class="row" >
		          <div class="col-md-12 text-center ">
                    <div class="form-group">
                      <label for="user_image">
		             <i class="fa fa-pencil upload-user-img" for="user_image" ></i>
                      <div style="width:150px; height:150px;" > <img src="<?php echo $user_image ; ?>" id="preview_img" class="personal-info-user-img" />
                        <input type="file" class="hidden"  name="user_image" id="user_image" onchange="readURLimg(this)" style="border:none; margin-bottom:10px;"  />
                        <input type="hidden" name="old_image" value= "<?php echo $p_image ;?>"
			<div></div>
                        </label>
                      </div>
					  <div><h2><strong style="color:#000;" ><i style="color:#2dc719; font-size:24px; " class="fa fa-circle" aria-hidden="true"></i> <?php echo $data[0]->phone;?></strong></h2></div>
                    </div>
				</div>
                    <div class="row" >
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label>First name</label>
                          <br>
                          <input name="first_name" id="first_name" class="form-control" placeholder=" Enter your Name"  type="text" value="<?php echo $data[0]->firstName;?>" >
                        </div>
                      </div>
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label>last name</label>
                          <br>
                          <input name="last_name" id="last_name" class="form-control" placeholder="Enter your lastname" type="text"  value="<?php echo $data[0]->lastName;?>" >
                        </div>
                      </div>
                    </div>
                    <div class="row" >
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label> Email </label>
                         <br>
                          <input name="email" id="email" class="form-control" placeholder="Enter your Email"  type="email" value="<?php echo $data[0]->email;?>"  required>
                        </div>
                      </div>
					  <div class="col-md-6" >
                        <div class="form-group">
                          <label> Zip Code </label>
                          <br>
                          <input name="zip_code" id="zip_code" class="form-control" placeholder="Enter your Zip Code" type="text" value="<?php echo $data[0]->zipCode;?>"  >
                        </div>
                      </div>                    
                    </div>
                    <div class="row" >
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label> state </label>
                          <br>
                          <input name="state" id="state" class="form-control" placeholder="Enter your  state" type="text"  value="<?php echo $data[0]->stateID;?>" />
                        </div>
                      </div>
					 <div class="col-md-6" >
                        <div class="form-group">
                          <label> city </label>
                          <br>
                          <input name="city" id="city" class="form-control" placeholder="Enter your city" type="text"  value="<?php echo $data[0]->city;?>"  >
                        </div>
                      </div>
                    </div>
                    <div class="row" >
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label> country </label>
                          <br>
                          <input name="country" id="country" class="form-control" placeholder="Enter your country" type="text" value="<?php echo $data[0]->country;?>"  >
                        </div>
                      </div>
					    <div class="col-md-6" >
                        <div class="form-group">
                          <label> </label>
                         <br>
                          <input name="phone" id="phone" class="form-control" placeholder="Enter your phone No " type="hidden" value="<?php echo $data[0]->phone;?>"  required >
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="customerID" value="<?php echo $_SESSION['customerID'];?>" />
                    <input type="hidden" name="action" value ="update_profile" />
                  </div>
                  <div class="text-right" >
                    <div class="form-group">
                      <button value="" class="hvr-shutter-out-horizontal"  type="submit" name="submit">Update </button >
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
</div>
<?php get_footer();
}else{
  wp_redirect(WP_SITEURL);
} ?>

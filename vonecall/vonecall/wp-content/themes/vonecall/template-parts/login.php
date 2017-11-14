<?php
/* Template Name: Login */
get_header();
$permalink=get_permalink();// function to get permalink
$role=split('vonecall/',$permalink);//getting specific data from permalink 
$img_url=WP_SITEURL.'/wp-content/uploads/';// image path static

// view user page according to role as customer/distributor/store owner

switch($role[1]){
    case 'distributor/': $type=1;

                        $html="<h1> <img src='".$img_url.'distributor-page.png'."'> <br><br><span style='color:#3f8ed9; '>Distributor </span>  </h1>";
                        $html.='<form role="form"  method="post" id="frm-login" autocomplete="off">
                    <div class="form-group">
                      <label for="email" class="sr-only">Email</label>
                      <input type="text" name="username" id="email" title="Enter Email or phone" class="form-control" placeholder="Your Email or Phone Number" required >
                    </div>
                    <div class="form-group">
                      <label for="key" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required >
                    </div>
                    <div class="show_password_eye">
                     <span><img onclick="showPassword()" src="'. WP_SITEURL.'/wp-content/uploads/show_hide_password.png" width="20px"></span>
                     <input type="hidden" name="action" value="check_auth"/>
                   </div>
                    <!-- <div class="checkbox"> <span class="character-checkbox"  onclick="showPassword()"></span> <span class="label">Show password</span>
                    </div> -->
                    <input type="hidden" name="user_role" id="user_role" value="'.$type.'"/>
                    <button type="submit" id="btn-login" class="hvr-shutter-out-horizontal btn-block" value="">Log in </button>
                  </form>';
            break;
    case 'store/':      $type=2;
                        $html="<h1> <img src='".$img_url.'store-page.png'."'> <br><br><span style='color:#3f8ed9; '>Store </span>  </h1>";
                        $html.='<form role="form"  method="post" id="frm-login" autocomplete="off">
                    <div class="form-group">
                      <label for="email" class="sr-only">Email</label>
                      <input type="text" name="username" id="email" title="Enter Email or phone" class="form-control" placeholder="Username" required >
                    </div>
                    <div class="form-group">
                      <label for="key" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required >
                    </div>
                    <div class="show_password_eye">
                     <span><img onclick="showPassword()" src="'. WP_SITEURL.'/wp-content/uploads/show_hide_password.png" width="20px"></span>
                     <input type="hidden" name="action" value="check_auth"/>
                   </div>
                    <!-- <div class="checkbox"> <span class="character-checkbox"  onclick="showPassword()"></span> <span class="label">Show password</span>
                    </div> -->
                    <input type="hidden" name="user_role" id="user_role" value="'.$type.'"/>
                    <button type="submit" id="btn-login" class="hvr-shutter-out-horizontal btn-block" value="">Log in </button>
                  </form>';

            break;
    case 'customer/': $type=3;
                    $html="<h1> <img src='".$img_url.'customer-page.png'."'> <br><br><span style='color:#3f8ed9; '>Customer </span>  </h1>";
                    $html.='<form role="form"  method="post" id="frm-login" autocomplete="off">
                    <div class="form-group">
                      <label for="email" class="sr-only">Email</label>
                      <input type="text" name="username" id="email" title="Enter Email or phone" class="form-control" placeholder="Your Email or Phone Number" required >
                    </div>
                    <div class="form-group">
                      <label for="key" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required >
                    </div>
                    <div class="show_password_eye">
                     <span><img onclick="showPassword()" src="'.WP_SITEURL.'/wp-content/uploads/show_hide_password.png" width="20px"></span>
                     <input type="hidden" name="action" value="check_auth"/>
                   </div>
                    <!-- <div class="checkbox"> <span class="character-checkbox"  onclick="showPassword()"></span> <span class="label">Show password</span>
                    </div> -->
                    <input type="hidden" name="user_role" id="user_role" value="'.$type.'"/>
                    <button type="submit" id="btn-login" class="hvr-shutter-out-horizontal btn-block" value="">Log in </button>
                  </form>';
                    
            break;
}

?>

<section id="costom-tab" class="log-reg"  >
  <div class="container">
      <div class="tabbable-panel">
        <div class="tabbable-line">
          <ul class="nav nav-tabs ">
            <li class="active" id="log"> <a href="#tab_default_1" data-toggle="tab"> Login </a> </li>
            <?php if($type == 3){?> <li id="reg"> <a href="#tab_default_2" data-toggle="tab"> Register </a> </li><?php }?>
          </ul>
          <div class="tab-content">
           <div class="loading">
              <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">
            </div>
          <div class="col-md-7 reg-img"> <img  src="<?php echo WP_SITEURL; ?>/wp-content/uploads/login.jpg" style="width: 984px;"> </div>
            <div class="tab-pane active" id="tab_default_1">
              <div class="col-md-5 text-center login-bg">
                <div id="login"> <?php echo $html;?>
<!--                  <form role="form"  method="post" id="frm-login" autocomplete="off">
                    <div class="form-group">
                      <label for="email" class="sr-only">Email</label>
                      <input type="text" name="username" id="email" title="Enter Email or phone" class="form-control" placeholder="Your Email or Phone Number" required >
                    </div>
                    <div class="form-group">
                      <label for="key" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required >
                    </div>
                    <div class="show_password_eye">
                     <span><img onclick="showPassword()" src="<?php// echo WP_SITEURL; ?>/wp-content/uploads/show_hide_password.png" width="20px"></span>
                     <input type="hidden" name="action" value="check_auth"/>
                   </div>
                     <div class="checkbox"> <span class="character-checkbox"  onclick="showPassword()"></span> <span class="label">Show password</span>
                    </div> 
                    <input type="hidden" name="user_role" id="user_role" value="<?php// echo $type;?>"/>
                    <button type="submit" id="btn-login" class="hvr-shutter-out-horizontal btn-block" value="">Log in </button>
                  </form>-->
                      
                  <a href="javascript:;" class="forget" data-toggle="modal" data-target=".forget-modal">Forgot your password?</a> </div>
              </div>
            </div>
            <?php if($type == 3){?>
            <div class="tab-pane" id="tab_default_2">
              <div class="register">
                <div class="col-md-5"> 
                  
                  <!--open of var-->
                  <div class="phone_verify">
                    <div class="title">
                      <h3 style="font-size: 18px !important;">Are you an existing customer of Vonecall Pinless</h3>
                    </div>
                    <form id="phone_verify_form" name="phone_verify_form" class="bg-gray-pad" role="form" method="post">
                      <div class="title">
                        <h4>Please enter phone number</h4>
                      </div>
                      <div class="form-group">
                        <input name="phone" id="phone_number" class="form-control" placeholder="xxx-xxx-xxxx" type="text" value="" required >
                      </div>
                      <div class="form-group">
                      <input type="hidden" name="action" value="verify_user_phone"/>
                        <button  id="checkPhone" class="hvr-shutter-out-horizontal" type="submit" name="checkPhone"> Check </button>
                      </div>
                    </form>
                  </div>
                  
                  <!--end of var--> 
                  
                  <!--open of otp-->
                  <div class="otp_verify">
                    <div class="title">
                      <h3 >Verification</h3>
                    </div>
                    <form id="otp_form"  class="bg-gray-pad"  method="post" >
                      <div class="title">
                        <h4> Enter your OTP for verification </h4>
                      </div>
                      <div class="form-group">
                        <input name="otp" id="otp" class="form-control" required placeholder="One Time Password" type="text" value="">
                        <input type="hidden" name="otpNumber" id="otpNumber"/>
                      </div>
                      <div class="form-group">
                        <input type="hidden" name="action" value="check_otp"/>
                        <button  id="checkOtp" class="hvr-shutter-out-horizontal"  type="submit" name="checkOtp"> Verify </button>
                      </div>
                    </form>
                  </div>
                  
                  <!--end of otp--> 
                  
                  <!--open of otp-->
                  <div class="login-pag-send">
                    <div class="title">
                      <h3 >Verification</h3>
                    </div>
                    <div class="option_div bg-gray-pad ">
                      <div class="title">
                        <h4>You are already a registerd user please click below to go login page... </h4>
                      </div>
                      <a class="btn btn-primary btn-otp" href="https://www.vonecall.com/vonecall-user-login">Go to login</a>
                      <h5>Sign up if you are new user</h5>
                    </div>
                  </div>
                  
                  <!--open of ask_for_otp-->
                  <div class="ask_for_otp" >
                    <div class="title">
                      <h3 >Verification</h3>
                    </div>
                    <div class=" bg-gray-pad">
                      <div class="title">
                        <h4> Please click here to receive OTP for registration</h4>
                      </div>
                      <button id="send_otp" class="hvr-shutter-out-horizontal" tabindex="7" type="button" name="send_otp"> Send OTP </button>
                    </div>
                  </div>
                  
                  <!--end of ask_for_otp--> 
                  
                  <!--open of register-user-->
                  
                  <div class="register-user" >
                    <div class="title">
                      <h3 >Register</h3>
                    </div>
                    <div class="bg-gray-pad" >
                    <div id="successMessagePhone">You are verified , please continue with signup </div>
                    <script type="text/javascript">
                      var onloadCallback = function() {
                      grecaptcha.render('html_element', {
                      'sitekey' : '6LfaqDUUAAAAAAIwd6E3OhHtMDFCE85dK5CMTNsO'
                      });
                      };
                    </script>
                    <form id="register_form" name="register_form" method="post" autocomplete="off">
                      <div class="persnal-detail">
                        <div class="title">
                          <h4 >Personal Info </h4>
                        </div>
                        <div class="row" >
                          <div class="col-md-6" >
                            <div class="form-group">
                              <input name="first_name" id="first_name" class="form-control" placeholder="First Name"  type="text" required>
                              <input name="newPhone" id="newPhone" class="form-control" placeholder="Phone Number" tabindex="2" type="hidden" value="">
                            </div>
                          </div>
                          <div class="col-md-6" >
                            <div class="form-group">
                              <input name="last_name" id="last_name" class="form-control" placeholder="Last Name" type="text" required>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <input name="email" id="reg_email" autocomplete="off" class="form-control" placeholder="Email"  type="email" required>
                        </div>
                        <div class="form-group">
                          <input name="zip_code" id="zip_code" class="form-control" placeholder="Zip Code" type="text" required >
                        </div>
                        <div class="form-group">
                          <input name="password" id="password" class="form-control" placeholder="Password"  type="password" required >
                        </div>
                        <div class="form-group">
                          <input name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password"  type="password" required >
                        </div>
                        <div class="form-group">
                          <input name="one_time_password" id="one_time_password" class="form-control" placeholder="OTP"  type="text">
                        </div>
                        <div class="form-group text-right">
                            <span><a href="#" id="resend_OTP">Resend OTP</a></span>
                        </div>
                      </div>
                                           <div class="form-group">
                      <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6LfaqDUUAAAAAAIwd6E3OhHtMDFCE85dK5CMTNsO"></div>
                    </div>
                    <script type="text/javascript">
                      function recaptchaCallback() {
                          $('#btn-logins').removeAttr('disabled');
                      };
                    </script>
                      <div style=" position: relative;" >
                          <input  class="largerCheckbox"  type="checkbox" name="terms" id="terms" checked="checked" value="">
                         <span class="checkbox-cont" > I have agree terms and conditions </span>
                        </div>
                        <input type="hidden" name="action" value ="register_user"/>
                  
                      <div class="form-group">
                        <button value="" id="btn-logins" class="hvr-shutter-out-horizontal"  type="submit" name="submit" disabled=""> Register </button >
                      </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
</section>

<!-- popup modal -->
<div class="modal fade forget-modal" tabindex="-1" role="dialog" aria-labelledby="myForgetModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <form method="post" id="forgot_password_form">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"> <span style="font-size: 36px; color: #fff; " aria-hidden="true">×</span> <span class="sr-only">Close</span> </button>
        <h2 class="modal-title text-center">Forgot Password</h2>
      </div>
      <div class="modal-body">
         
        <div class="form-group">
          <input type="email" name="email" id="email" class="form-control" placeholder="Registered Email *" required>
          <input type="hidden" name="action" value="forgot_password">
        </div>
<!--        <div class="form-group">
          <input type="email" name="email" id="email" class="form-control" placeholder=" Registered Phone Number *" required>
        </div>-->
      </div>
      <div class="modal-footer">
        <button type="button" class="hvr-shutter-out-horizontal" data-dismiss="modal">Cancel</button>
        <button type="submit" class="hvr-shutter-out-horizontal" >Submit</button>
      </div>
  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal -->

<?php
get_footer();
?>

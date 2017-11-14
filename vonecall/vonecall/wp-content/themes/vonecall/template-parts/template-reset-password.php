<?php
/* Template Name: Reset-password */
get_header();
?>
<div class="clf">
  <div class="container">
    <div class="row">
      <div class="col-md-offset-3 col-md-6"> 
                  
                  <!--open of var-->
                  <div style="margin:40px 0px;">
                    <div class="title">
                      <h3 style="font-size: 18px !important;">Reset Pssword</h3>
                    </div>
                    <form id="reset_password" name="reset_password" class="bg-gray-pad" role="form" method="post">
                      <div class="title">
                        <h4>Reset Password</h4>
                      </div>
                      <div class="form-group">
                        <input name="phone" id="new_password" class="form-control" placeholder="Enter New Password" type="password" name="new_password" value="" required="">
                      </div>
                      <div class="form-group">
                       <input name="phone" id="confirm_password" class="form-control" placeholder="Enter Confirm Password" type="password" name="confirm_password" value="" required="">
                     </div>
                     <div class="form-group">
                        <button id="checkPhone" class="hvr-shutter-out-horizontal" type="submit" name="checkPhone"> Reset Now </button>
                      </div>
                    </form>
                  </div>
                  
                  <!--end of var--> 
                 
      </div>
    </div>
  </div>
<?php get_footer(); ?>





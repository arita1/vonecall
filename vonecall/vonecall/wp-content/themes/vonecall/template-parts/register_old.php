<?php
/* Template Name: register */
get_header();
?>


<section id="register">
    <div class="container">
        <div class="row">
    	             <div class="col-md-8">
                      <div class="title"> <h3 >Register</h3></div>
					 <div class="register-page" >
                       <!--  <div> -->
                                <!--task  message start-->  
                              <!--   <span style="color:red">  
                                <div id="errorMessagePhone"></div>
                                </span>
                                <span style="color:green">  
                                <div id="successMessagePhone">You are verified , please continue with singup </div>
                                </span> -->
                                <!--task  message end-->
                           <!--  <div class="otp_class">

                                <form id="otp_form" action="" method="post" enctype="multipart/form-data">

                                <div class="title" style="color:#000;"><h5> Enter your OTP for verification   </h5></div>

                                <div class="form-group">
                                        <input name="otp" id="otp" class="form-control" placeholder="One Time Password" type="text" value="">
                                </div>
                                <div class="form-group">
                                <button  id="checkOtp" class="btn btn-primary btn-block"   type="button" name="checkOtp">
                                Verify
                                </button>
                                </div>
                                
                                </form>     
                             </div> --><!--end of opt-->

                             
                             <!--  <div class="option_div"s>
                                <div class="title" style="color:#000;"><h5> Your already a registerd user please click below to go login page...  </h5></div>
                                
                                <a class="btn btn-primary btn-block btn-lg" href="https://www.vonecall.com/vonecall-user-login">Go to login</a>
                                
                             </div>
                            
                              <div class="ask_for_otp" style="display: none;">
                                    <div class="title" style="color:#000;"><h5> Please click here to receive OTP for registration</h5></div>
                                    
                                    <button onclick="send_otp()" id="send_otp" class="btn btn-primary btn-block btn-lg" tabindex="7" type="button" name="send_otp">
                                    Send OTP
                                    </button>
                                
                                 </div> -->
                                
                            
                             
                            <!-- <p>Sign up if you are new user</p> -->
                            
                           <!--  <div class="phone_div">
                                <form id="phone_verify_form" action="" method="post" enctype="multipart/form-data">
                                   
                                        <div class="title"><h4>Enter your phone number for verification</h4></div>
                                    <div class="form-group">
                                            <input name="phone" id="phone_number" class="" placeholder="Phone Number" type="text" value="">
                                    </div>
                                
                                    <div class="form-group">
                                        <button  id="checkPhone" class="btn btn-primary btn-block" type="button" name="checkPhone">
                                        Verify
                                        </button>
                                    </div>
                                </form>
                            </div> -->
                       <!--  </div> -->
                        
                        <form id="register_form" method="post"> 
                                <div class="persnal-detail">
                                 <div class="title"> <h4 >Personal Info </h4></div>
                                   <!--  <div class="form-group">
                                        <input name="newPhone" id="newPhone" class="form-control" placeholder="Phone Number" type="hidden">
                                    </div> -->

                                    <div class="row" > 
                                    <div class="col-md-6" > 
                                    <div class="form-group">
                                        <!-- <label class="control-label" for="Name">Name</label> -->
                                        <input name="first_name" id="first_name" class="form-control" placeholder="First Name"  type="text" required>
                                    </div>
                                    </div>
                                      <div class="col-md-6" > 
                                    <div class="form-group">
                                        <input name="last_name" id="last_name" class="form-control" placeholder="Last Name" type="text" required>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="email" id="email" class="form-control" placeholder="Email"  type="email" required>
                                    </div>
                                    <div class="form-group">
                                        <input name="password" id="password" class="form-control" placeholder="Password"  type="password" required >
                                    </div>
                                    <div class="form-group">
                                        <input name="confirm_password" id="confirm_password" class="form-control" placeholder="Confurm Pssword"  type="password" required >
                                    </div>
                                </div>
                            
                                <div class="billing-address">
                                    <div class="form-group">
                                     <div class="title"> <h4 > Address </h4></div>
                                        <input name="address" id="address" class="form-control " placeholder="Address"  type="text" required >
                                    </div>
                                    <div class="form-group">
                                        <input name="city" id="city" class="form-control " placeholder="City"  type="text" required >
                                    </div>
                                    <div class="form-group">
                                        <select name="card_state" class="w132 float_left">
<option value="" selected="selected">-- Select State --</option>
<option value="AB">Alberta</option>
<option value="AK">Alaska</option>
<option value="AL">Alabama</option>
<option value="AR">Arkansas</option>
<option value="AZ">Arizona</option>
<option value="BC">British Columbia</option>
<option value="CA">California</option>
<option value="CO">Colorado</option>
<option value="CT">Connecticut</option>
<option value="DC">District of Colombia</option>
<option value="DE">Delaware</option>
<option value="FL">Florida</option>
<option value="GA">Georgia</option>
<option value="GU">Guam</option>
<option value="HI">Hawaii</option>
<option value="IA">Iowa</option>
<option value="ID">Idaho</option>
<option value="IL">Illinois</option>
<option value="I">Indiana</option>
<option value="KS">Kansas</option>
<option value="KY">Kentucky</option>
<option value="LA">Louisiana</option>
<option value="MA">Massachusetts</option>
<option value="MB">Manitoba</option>
<option value="MD">Maryland</option>
<option value="ME">Maine</option>
<option value="MH">Marshall Islands</option>
<option value="MI">Michiga</option>
<option value="M">Minnesota</option>
<option value="MO">Missouri</option>
<option value="MP">N Mariana Islands</option>
<option value="MS">Mississippi</option>
<option value="MT">Montana</option>
<option value="NB">New Brunswick</option>
<option value="NC">North Carolina</option>
<option value="ND">North Dakota</option>
<option value="NE">Nebraska</option>
<option value="NH">New Hampshire</option>
<option value="NJ">New Jersey</option>
<option value="NL">Newfoundland and Labrador</option>
<option value="NM">New Mexico</option>
<option value="NS">Nova Scotia</option>
<option value="NT">Northwest Territories</option>
<option value="NU">Nunavut</option>
<option value="NV">Nevada</option>
<option value="NY">New york</option>
<option value="OH">Ohio</option>
<option value="OK">Oklahoma</option>
<option value="O">Ontario</option>
<option value="OR">Orego</option>
<option value="PA">Pennsylvania</option>
<option value="PE">Prince Edward Island</option>
<option value="PR">Puerto Rico</option>
<option value="PW">Palau</option>
<option value="QC">Quebec</option>
<option value="RI">Rhode Island</option>
<option value="SC">South Carolina</option>
<option value="SD">South Dakota</option>
<option value="SK">Saskatchewa</option>
<option value="T">Tennesee</option>
<option value="TX">Texas</option>
<option value="UT">Utah</option>
<option value="VA">Virginia</option>
<option value="VI">Virgin Islands</option>
<option value="VT">Vermont</option>
<option value="WA">Washingto</option>
<option value="WI">Wisconsi</option>
<option value="WV">West Virginia</option>
<option value="WY">Wyoming</option>
<option value="YT">Yuko</option>
</select>                                   </div>
                                    <div class="form-group">
                                        <input name="zip_code" id="zip_code" class="form-control" placeholder="Zip Code" type="text" required >
                                    </div>
                                </div>
                            
                                   <div style="margin-top: 5px; margin-bottom: 5px;">
                                    <div class="g-recaptcha" data-sitekey="6LcgYQgUAAAAACqND5lTKW28zNx5vlB8CxwNyXRb" data-theme="light" data-type="image"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?k=6LcgYQgUAAAAACqND5lTKW28zNx5vlB8CxwNyXRb&amp;co=aHR0cHM6Ly93d3cudm9uZWNhbGwuY29tOjQ0Mw..&amp;hl=en&amp;type=image&amp;v=r20170613131236&amp;theme=light&amp;size=normal&amp;cb=fuixr8ju7pd" title="recaptcha widget" width="304" height="78" frameborder="0" scrolling="no" sandbox="allow-forms allow-modals allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea></div></div>                                  <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=onload&amp;hl=en" async="" defer=""></script>                                  </div>
                            <!---<div class="g-recaptcha" data-sitekey="6LcgYQgUAAAAACqND5lTKW28zNx5vlB8CxwNyXRb"></div>-->
                                <div class="form-group">
                                    <input value="Join" class="btn btn-primary btn-register"  type="submit" name="submit">
                                </div>
                            </dir>
                        </form>
                    </div>    	
				</div>
              <div class="col-md-4">
              <div class="bg-gray text-center" > 
			  <div class="title"><h3>Secure Payment Services</h3></div>
                  <img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/trust-pay.png">
              </div>
              </div>
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<?php get_footer(); ?>

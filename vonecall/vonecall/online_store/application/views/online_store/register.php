<?php $this->load->view('online_store/inc_header');?>


	<!-- slider -->
			<div id="main-slider" class="slider">
	    		<div>
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				</div>
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
<div class="content">
<!-- /.intro-header -->
		<div class="sign-up-form  get-free-trial clearfix " style="margin-top: 10px; padding-top: 30px;">
				<div class="container">
				  <div class="row">
					<div class="col-md-6 col-lg-6 left">
						<div>
							<?php 
							if($this->session->flashdata('error'))
							{
								$message = $this->session->flashdata('error');
							?>
								<div style="padding:5px;text-align:center;font-size:initial;"><?php echo $message['message'];?></div>
							<?php }
							
							if($this->session->flashdata('success'))
							{
								$message = $this->session->flashdata('success');
							?>
								<div style="padding:5px;text-align:center;font-size:initial;"><?php echo $message['message'];?></div>
							<?php }
							 ?>
							 	<!--task  message start-->  
								<span style="color:red">  
								<div id="errorMessagePhone"></div>
								</span>
								<span style="color:green">  
								<div id="successMessagePhone"></div>
								</span>
								<!--task  message end-->
							<div class="otp_class" style="display: none;">
								<form id="otp_form" action="" method="post" enctype="multipart/form-data">
								<div class="title" style="color:#000;"><h5> Enter your OTP for verification   </h5></div>
								<div class="form-group">
										<input name="otp" id="otp" class="form-control w132 float_left" placeholder="One Time Password" tabindex="2" type="text" value="" >
								</div>
								<div class="form-group">
								<button onclick="verifyOTP()" id="checkOtp" class="btn btn-primary btn-block btn-lg" tabindex="7" type="button" name="checkOtp">
								Verify
								</button>
								</div>
								
								</form>		
							 </div><!--end of opt-->
							 
							  <div class="option_div" style="display: none;">
								<div class="title" style="color:#000;"><h5> Your already a registerd user please click below to go login page...  </h5></div>
								
								<a class="btn btn-primary btn-block btn-lg" href="<?php echo base_url()?>vonecall-user-login">Go to login</a>
								
							 </div>
							
							  <div class="ask_for_otp" style="display: none;">
									<div class="title" style="color:#000;"><h5> Please click here to receive OTP for registration</h5></div>
									
									<button onclick="send_otp()" id="send_otp" class="btn btn-primary btn-block btn-lg" tabindex="7" type="button" name="send_otp">
									Send OTP
									</button>
								
								 </div>
								
							
							 
							<!-- <p>Sign up if you are new user</p> -->
							
							<div class="phone_div">
								<form id="phone_verify_form" action="" method="post" enctype="multipart/form-data">
									<!-- <div class="title" style="color:#000;"><h5>    </h5></div> -->
										<div class="title"><h4 style="font-family: Gill Sans,Gill Sans MT,Calibri,sans-serif;">Enter your phone number for verification</h4></div>
									<div class="form-group">
											<input name="phone" id="phone_number" class="form-control w132 float_left" placeholder="Phone Number" tabindex="2" type="text" value="">
									</div>
								
									<div class="form-group">
										<button onclick="verifyPhoneNumber()" id="checkPhone" class="btn btn-primary btn-block btn-lg" tabindex="7" type="button" name="checkPhone">
										Verify
										</button>
									</div>
								</form>
							</div>
						</div>
						
						<form id="register_form" action="<?php echo base_url();?>frontend/register_new_customer" method="post" enctype="multipart/form-data">
							
							<dir class="hide-show-dive" style="display: none;padding: 0px;">
								<div class="persnal-detail">
									<label class="control-label" for="Name">Personal Info</label>
									<div class="form-group">
										<input name="newPhone" id="newPhone" class="form-control w132 float_left" placeholder="Phone Number" tabindex="2" type="hidden" value="">
									</div>
									<div class="form-group" style="margin-top:10px; ">
										<!-- <label class="control-label" for="Name">Name</label> -->
										<input name="first_name" id="first_name" class="form-control w132 float_left" placeholder="First Name" tabindex="1" type="text">
									</div>
									<div class="form-group">
										<input name="last_name" id="last_name" class="form-control w132 float_left" placeholder="Last Name" tabindex="2" type="text">
									</div>
									<div class="form-group">
										<input name="email" id="email" class="form-control w132 float_left" placeholder="Email" tabindex="2" type="email">
									</div>
									<div class="form-group">
										<input name="password" id="password" class="form-control w132 float_left" placeholder="Password" tabindex="2" type="password">
									</div>
									<div class="form-group">
										<input name="confirm_password" id="confirm_password" class="form-control w132 float_left" placeholder="Confurm Pssword" tabindex="2" type="password">
									</div>
								</div>
							
								<div class="billing-address">
									<div class="form-group">
										<label class="control-label" for="Name">Address :-</label>
										<input name="address" id="address" class="form-control w132 float_left" placeholder="Address" tabindex="2" type="text">
									</div>
									<div class="form-group">
										<input name="city" id="city" class="form-control w132 float_left" placeholder="City" tabindex="2" 	type="text">
									</div>
									<div class="form-group">
										<?php echo form_dropdown('card_state', $option_state, (isset($card_state)?$card_state:''), 'class="w132 float_left"');?>
									</div>
									<div class="form-group">
										<input name="zip_code" id="zip_code" class="form-control w132 float_left" placeholder="Zip Code" tabindex="2" type="text">
									</div>
								</div>
							
								   <div style="margin-top: 5px; margin-bottom: 5px;">
							        <?php echo $widget;?>
									<?php echo $script;?>
							      </div>
							<!---<div class="g-recaptcha" data-sitekey="6LcgYQgUAAAAACqND5lTKW28zNx5vlB8CxwNyXRb"></div>-->
								<div class="form-group">
									<input value="Join" class="btn btn-primary btn-block btn-lg" tabindex="7" type="submit" name="submit">
								</div>
							</dir>
						</form>
					</div>
					
					
					<div class="col-md-6 col-lg-6 right">
						<div class="title"><h4 style="font-family: Gill Sans,Gill Sans MT,Calibri,sans-serif;margin-left:118px;">Secure Payment services</h4></div>
							<!-- <p> Please provide your username and password below to access your account. </p> -->
							
							<div class="trusted-card">
							<div class="trust_pay_img">	
							<img src="img/trust-pay.png" width="200px" />
							</div>
								<div class="centered-white-inner-container">
									<!--<a href="#">
										<p><img src="img/grt-img.gif" /></p>
										<div class="spacer"></div>
										<p><img src="img/siteseal_gd_3_h_l_m.gif" /></p>
										<div class="spacer"></div>
										<p><img src="img/cards.gif" /></p>
									</a>-->
									<div class="auth_net_img">
									<a href="#">
										<p><img width='100px' height='200px' src="img/authnet-secured.jpg"/></p>
										<div class="spacer"></div>
									</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					
				
		</div>
</div>
<script>

</script>
<?php $this->load->view('online_store/inc_footer');?>




<?php $this->load->view('online_store/inc_header');?>

    <div class="content">
    <!-- /.intro-header -->
		
	<div class="clearfix sign-up-form" style="margin-top: 70px;">
				<div class="container">
					<div class="row">
						
						<div class="col-md-5 col-lg-5 left">
							<div class="title"><h3> New Customer </h3></div>
							<p>Sign up if you are new user</p>
							<div class="sign-up">
								<a href="#" class="btn btn-primary btn-block btn-lg" tabindex="7" >Sign Up For Free Trial</a></center>
							</div>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus lacus tortor</p>
						</div>
						<div class="col-md-6 col-lg-6 right">
							<div class="title"><h3>Returning Customer</h3></div>
							<p> Please provide your username and password below to access your account. </p>
							<form>
								<div class="form-group">
									<label class="control-label" for="Name">Your Email or Phone Number <span style="color:red;">*</span></label>
									<input name="first_name" id="first_name" class="form-control input-lg" placeholder="First Name" tabindex="1" type="text">
								</div>
								<div class="form-group">
								<label class="control-label" for="Name">Your Password <span style="color:red;">*</span></label>
									<input name="password" id="password" class="form-control input-lg" placeholder="password" tabindex="2" type="password">
								</div>
								
								
								<div class="form-group">
									<input value="Sign in" class="btn btn-primary btn-block btn-lg" tabindex="7" type="submit">
								</div>
								<div class="forgot-pass form-group"><a href="#">Forgot password?</a></div>
							</form>
						</div>
					</div>
					
				</div>
		</div>
	</div>
   
    <!-- /.content-section-a -->

<?php $this->load->view('online_store/inc_footer');?>




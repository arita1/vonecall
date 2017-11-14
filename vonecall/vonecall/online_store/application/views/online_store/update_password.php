<?php $this->load->view('online_store/inc_header');?>
<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php
foreach ($customerDetails as $value) {
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$lastName = $value->lastName ;
$address = $value->address ;
$city = $value->city ;
$zipCode = $value->zipCode ;
$email = $value->email ;
}

 ?>
<div class="content">
<div class="box-table my-account">
	<div class="row">
		<div class="col-md-6 col-lg-6 right">
							 
							<div class="title"><h3>Update Profile</h3></div>
							</br>
							<?php
						if(is_numeric($this->uri->segment(2)) && $this->uri->segment(2)==1)
						{
							
						?>
					<div style=" color:green; center;font-size:initial;"><?php echo 'Please complete your profile to access your account.';?></div>
						<?php }
						 ?>
							<p> Following details are required for complete you registration  </p>
							<form method="post" action="<?php echo base_url() ?>frontend/update_user_profile" id="new_update_profile">
								<div class="form-group">
									<label class="control-label" for="Name">Update Email <span style="color:red;">*</span></label>
									<input name="new_email" class="form-control input-lg" id="new_email" placeholder="email" tabindex="1" type="text"  value="<?php if(isset($email) && $email!=""){ echo $email; } ?>">
								</div>
								<div class="form-group">
								<label class="control-label" for="Name">Create Password <span style="color:red;">*</span></label>
									<input name="new_password" id="new_password" class="form-control input-lg" placeholder="password" tabindex="2" type="password" >
								</div>
								
								
								<div class="form-group" style="margin-top:10px; ">
									<!-- <label class="control-label" for="Name">Name</label> -->
									<input name="first_name" id="first_name" class="form-control w132 float_left" placeholder="First Name" tabindex="1" type="text"  value="<?php if(isset($firstName) && $firstName!=""){ echo $firstName; } ?>">
								</div>
								<div class="form-group">
									<input name="last_name" id="last_name" class="form-control w132 float_left" placeholder="Last Name" tabindex="2" type="text" value="<?php if(isset($lastName) && $lastName!=""){ echo $lastName; } ?>">
								</div>
								
								<div class="form-group">
										<label class="control-label" for="Name">Address :-</label>
										<input name="address" id="address" class="form-control w132 float_left" placeholder="Address" tabindex="2" type="text" value="<?php if(isset($address) && $address!=""){ echo $address; } ?>">
									</div>
									<div class="form-group">
										<input name="city" id="city" class="form-control w132 float_left" placeholder="City" tabindex="2" 	type="text" value="<?php if(isset($city) && $city!=""){ echo $city; } ?>">
									</div>
									<div class="form-group">
										<?php echo form_dropdown('card_state', $option_state, (isset($card_state)?$card_state:''), 'class="w132 float_left"');?>
									</div>
									<div class="form-group">
										<input name="zip_code" id="zip_code" class="form-control w132 float_left" placeholder="Zip Code" tabindex="2" type="text" value="<?php if(isset($zipCode) && $zipCode!=""){ echo $zipCode; } ?>">
									</div>
								
								<div class="form-group">
									<input value="Update" class="btn btn-primary btn-block btn-lg" tabindex="7" type="submit">
								</div>
								
							</form>
						</div>
		
		
	</div>
</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




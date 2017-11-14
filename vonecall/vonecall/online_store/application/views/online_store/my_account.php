<?php $this->load->view('online_store/inc_header');?>
<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php
foreach ($results as $value) {
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$email = $value->email ;	
$phone = $value->phone ;	
}
/*
Name...
Email...
Phone number....
Pinless Amount...
Topup Amount...
Last recharge ...
Last Login....
 * */

 ?>
<div class="content">
<div class="box-table my-account">
	<div class="row">
		<div class="col-md-6 col-sm-6">

			<div class="new-cust ">

				  <!-- <div class="block-title"><h4 class="label-primary">Our Latest Service</h4></div> -->
					<div class="divcontent text-left" style="height:auto;">

						<h3>Account Summary</h3>
						<div class="profile inline-form">
							<div class="view-pro ">
								<a style="color:#fff" href="<?php echo base_url(); ?>vonecall-update-password" class="new-cust-regi btn btn-primary" >
									Update Profile
								</a>
							</div>
							<br/>
							<br/>
							<div class="">
								<div class="pull-left">
									<p>Name</p>
								</div>
								<div class="col-md-offset-5">
									<h4>:- <?php if($firstName!=""){ echo $firstName;  } ?>  </h4> 
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Email</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php if($email!=""){ echo $email;  } ?>  </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Phone</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php if($phone!=""){ echo $phone;  } ?>  </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left ">
									<p>Pinless Acount</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php echo $id; ?> </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Pinless Balance</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php echo '$ '. $balance;  ?>  </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Pinless Recharge</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php echo 'Not Available';?>  </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Last Recharge</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php echo 'Not Available'; ?>  </h4>
								</div>
							</div>
							
							<div class="">
								<div class="pull-left">
									<p>Last Login</p>
								</div>
								<div class="text-left col-md-offset-5">
									<h4>:- <?php if($last_login!=""){ echo $last_login;  } ?>  </h4>
								</div>
							</div>
							

						</div>
						
					</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="new-cust">
				 <!--  <div class="block-title"><h4 class="label-primary">Our Latest Service</h4></div> -->
					<div class="divcontent" style="height:auto;">
							<h3>Exclusive offer</h3>
							<br/>
						<img src="<?php echo base_url()?>images/offer.jpg" />
					</div>
			</div>
		</div>
		<div class="col-md-12 manag-srvis">
			<div class="divcontent ">
				<h3>Manage Your Services</h3>
				<div class="row ">
					<div class="col-md-4">
						<a href="<?php echo base_url();?>vonecall-pinless-account">
							<i class="fa fa-globe" aria-hidden="true"></i>
							<h2>Pinless International Calling</h2>
						</a>
					</div>
					<div class="col-md-4">
						<a href="<?php echo base_url();?>vonecall-mobile-top-ups">
							<i class="fa fa-phone-square" aria-hidden="true"></i>
							<h2> Mobile Airtime Recharge - Top Ups</h2>
						</a>
					</div>
					<div class="col-md-4">
						<a href="#">
							<i class="fa fa-mobile" aria-hidden="true"></i>
							<h2>  Mobile App Dialer</h2>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




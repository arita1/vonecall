<?php $this->load->view('online_store/inc_header');?>
<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php
//echo $this->session->userdata('customerID');die;
/*foreach ($results as $value) {
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$phoneEmailId = $value->phoneEmailID ;	
}*/
/*
 Last Login
Pinless Acount:
Mobile App
Mobile Top UP: 
  */

 ?>
<div class="content">
<div class="box-table my-account">
	<div class="row">
		<div class="col-md-6 col-lg-6 ">
							<div class="title"><h3>Recharge Phone</h3></div>
							<form method="post" action="<?php echo base_url() ?>frontend/update_user_profile" id="new_update_profile">
								<div class="form-group">
									<label class="control-label" for="Name">Phone Number <span style="color:red;">*</span></label>
									<input name="phone_number" value="<?php echo $this->uri->segment(2); ?>" class="form-control input-lg" id="phone_number" placeholder="phone_number" tabindex="1" type="text" >
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




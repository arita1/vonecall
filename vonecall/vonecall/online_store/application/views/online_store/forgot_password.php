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
		<div class="col-md-3"></div>
		<div class="col-md-6 col-lg-6 right">
		<div class="title text-center"><h3>Fogot Password</h3></div>
		
		<!--task  message end-->
		<?php 
		if(!empty($this->session->flashdata('error')))
		{
			$message = $this->session->flashdata('error');
		?>
			<div style="text-align:left;font-size:initial;"><?php echo $message['message'];?></div>
		<?php }
		
		if(!empty($this->session->flashdata('success')))
		{
			$message = $this->session->flashdata('success');
		?>
			<div style="text-align:left;font-size:initial;"><?php echo $message['message'];?></div>
		<?php }
		 ?>
		  <?php
		if(is_numeric($this->uri->segment(2)) && $this->uri->segment(2)==1)
		{
			
		?>
	<div style=" color:green; center;font-size:initial;"><?php echo 'Your phone already registered, You can rest password from here  !';?></div>
		<?php }
		 ?>
		 <p style="color:#04a5ea;font-weight:100; "> Please enter  your registered email OR phone number.  </p>
		<form method="post" action="<?php echo base_url() ?>frontend/reset_password" id="forgot_password_form">
			<div class="form-group">
				<label class="control-label" for="Name">Registered Email <span style="color:red;">*</span></label>
				<input name="new_email" class="form-control input-lg" id="new_email" placeholder="email" tabindex="1" type="text" >
			</div>
			<div class="form-group">
				<label class="control-label" for="Name">Registered Phone Number <span style="color:red;">*</span></label>
				<input name="new_phone" class="form-control input-lg" id="new_phone" placeholder="phone number" tabindex="1" type="text" >
			</div>
			
			<div class="form-group">
				<input value="Update" class="btn btn-primary btn-block btn-lg" tabindex="7" type="submit">
			</div>
			
		</form>
		
			
		</form>
	</div>
	<div class="col-md-3"></div>
		
		
	</div>
</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




<?php $this->load->view('online_store/inc_header');?>
<!-- slider -->
			<div id="main-slider" class="slider">
	    		<div>
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				</div>
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
<div class="box-table">
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="new-cust">
				  <!-- <div class="block-title"><h4 class="label-primary">Our Latest Service</h4></div> -->
					<div class="divcontent" style="height:auto;">
						<h2>Not a customer !</h2>
						<div class="row">
							
							<div class="col-md-12">
								<div class="join-now">
									<a href="<?php echo base_url() ?>vonecall-register">
										<img src="<?php echo base_url()?>images/join.png" />
									</a>
								</div>
							</div>
							<div class="">
							<!--<ul class="bxslider">
							 <?php //https://www.vonecall.com/systemadmin/public/uploads/product_logo/att.gif 
							foreach ($products as $value) {
								
								?>
								<li><img width="100px" height="100px" src="<?php if($value->logoName==""){ echo 'vonecall'; }else{ echo base_url().'systemadmin/public/uploads/product_logo/'.$value->logoName; } ?>" /></li>
								<?php 
							}
								?>
							</ul>-->
							
							<div class="bottom-flags login-products">

							<marquee id="news" scrollamount="5" scrolldelay="0" loop="20" onmousemove="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount',2,0);" direction="left" width="100%" height="80">
							<!--    <p align="justify"><span class="GSLabel">Current country coverage:-</span> <br></p> -->
							<?php 
							foreach ($products as $value) {
								
								?>
								<div style="padding:5px 0px 5px 10px;">
								<a href="#">
									<img width="150px" height="80px" src="<?php if($value->logoName==""){ echo 'vonecall'; }else{ echo base_url().'systemadmin/public/uploads/product_logo/'.$value->logoName; } ?>" />
								</a>
								
							</div>
								<?php 
							}
								?>		
						     </marquee>
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
						<h2>Login</h2>

						<div class="row">
						<!--task  message start--> 
						<span style="color:green; margin-bottom: 5px;">  
						<div id="messageOnLoinPopup"></div>
						</span>
						<!--task  message end-->
						<?php 
						if(!empty($this->session->flashdata('error')))
						{
							$message = $this->session->flashdata('error');
						?>
							<div style="text-align:center;font-size:initial;"><?php echo $message['message'];?></div>
						<?php }
						
						if(!empty($this->session->flashdata('success')))
						{
							$message = $this->session->flashdata('success');
						?>
							<div style="text-align:center;font-size:initial;"><?php echo $message['message'];?></div>
						<?php }
						 ?>
						 <?php
						if(is_numeric($this->uri->segment(2)) && $this->uri->segment(2)==1)
						{
							
						?>
							<div style=" color:green; center;font-size:initial;"><?php echo 'Your phone already registered, Please login with your credentials  !';?></div>
						<?php }
						 ?>
						 
						  <?php
						if(is_numeric($this->uri->segment(2)) && $this->uri->segment(2)==2)
						{
							
						?>
					<div style=" color:green; center;font-size:initial;"><?php echo 'Your phone already registered, Please login with your temporary password sent on your phone  !';?></div>
						<?php }
						 ?>
						<!--  <div class="col-md-2">
								<div><img src="<?php echo base_url()?>images/admin.png" /></div>
							</div> -->
						 
							<div class="col-md-12">
								<form id="new_login_form" method="post" action="<?php echo base_url() ?>vonecall-login">
									<div class="form-group row">
									 
									  <div class="col-xs-12">
									  	 <label for="" class=" col-form-label pull-left">Email/Phone<span style="color:red;">*</span></label>
									    <input class="form-control" type="eamil" value="" id="email" name="email" required>
									  </div>
									</div>
									<div class="form-group row">
									
									  <div class="col-xs-12">
									  	  <label for="" class="pull-left col-form-label">Password<span style="color:red;">*</span></label>
									    <input class="form-control" type="password" value="" id="password" name="password" required>
									  </div>
									</div>
									<div class="forgot_pwd_link">
											<a href="<?php echo base_url()?>vonecall-forgot-password">Forgot Password</a>
									</div>
									<!--  <div class="btn-group btn-group-vertical" data-toggle="buttons">
											<label class="btn active">
												<input value="1" type="radio" name='user_type' checked>
												<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Customer</span>
											</label>
											<label class="btn">
											<input  value="2" type="radio" name='user_type'><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Store</span>
											</label>
										</div> -->
										<div class="form-group btn-group-vertical text-left">
											<!-- <label>Login As</label> -->
											 <input type="radio" id="radio1" name="user_type" value="1" checked>
									      	 <label for="radio1">Customer</label>
									  		  <input type="radio" id="radio2" name="user_type" value="2">
									     	  <label for="radio2">Store</label>
									    </div>

									<div class="form-group row login">
										<div class="col-md-12 ">
												<div class="new-cust-regi">
													<input type="submit" value="Login" name="submit" class="new-cust-regi" />
												</div>
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
<script>

</script>
<?php $this->load->view('online_store/inc_footer');?>
<script>
	$('.bxSlider').bxSlider({
  minSlides: 5,
  maxSlides: 5,
  slideWidth: 100,
  slideMargin: 10,
  ticker: true,
  speed: 6000
});
</script>



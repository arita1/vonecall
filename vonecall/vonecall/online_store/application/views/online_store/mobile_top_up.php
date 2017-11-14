<!-- If you're using Stripe for payments -->
<?php $this->load->view('online_store/inc_header');?>

<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img5.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php

foreach ($results as $value) {
$customerID = $value->customerID ;	
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$email = $value->email ;
$phone = $value->phone ;	
}
/*
 Last Login
Pinless Acount:
Mobile App
Mobile Top UP: 
  */

 ?>
<!-- content -->
<div class="content">
<div class="box-table top-up-div">
	<div class="row">
	
	
		<div class="col-md-12 col-sm-12">
			<div class="domestic-mobile-topup ">
				  <!-- <div class="block-title"><h4 class="label-primary">Our Latest Service</h4></div> -->
					<div class="divcontent text-left" style="height:auto;">
					<h3>Benefits</h3>
					  <div class="topup-body">	
						<div class="row">
							<div class="col-md-4">
								<div class="topup1 topup-box">
									<h4>Easy</h4>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
									<div class="topup-check"><i class="fa fa-check-circle-o" aria-hidden="true"></i></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="topup2 topup-box">
									<h4>Choice</h4>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
									<div class="topup-check"><i class="fa fa-check-circle-o" aria-hidden="true"></i></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="topup3 topup-box">
									<h4>Safe & Instant</h4>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
									<div class="topup-check"><i class="fa fa-check-circle-o" aria-hidden="true"></i></div>
								</div>
							</div>
							
						</div>
					  </div>
					</div>
				</div>
		
			
		</div>
		
		
		<div class="col-md-12 manag-srvis">
			<div class="divcontent ">
				<h3>How It Works</h3>
					<div class="row ">
						<div class="col-md-4">
							<div class="word-block">
								<img src="<?php echo base_url(); ?>images/ico-caree.png">
								<h4>Choose your Product/Carrier</h4>
							</div>
						</div>
						<div class="col-md-4">
							<div class="word-block">
								<img src="images/ico-phone.png">
								<h4>Enter the U.S. Mobile Number You'd Like to Recharge</h4>
							</div>
						</div>
						<div class="col-md-4">
							<div class="word-block">
								<img src="images/ico-ammount.png">
								<h4>Choose an Amount</h4>
							</div>
						</div>
					</div>
					
					<!------>
					<div id="main" class="p5t p10b">
					<div class="bg_tt_page"><div class="ac"><h1>Choose Product</h1></div></div>
					<div class="center_page_afterlogin">
						<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
						<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
						<div class="col_big">
							<div class="operatorLogos">
								<?php foreach ($carriers as $item) { ?>
				        		<div class="operatorBox top_up_box" >
				        			<!-- <a href="<?php echo site_url('vonecall-topup-usa-rtr-recharge/'.$item->ppnProductID);?>" title="<?php echo $item->vproductVendor;?>">-->
								   <a href="<?php echo site_url('vonecall-topup-usa-rtr-recharge/'.$item->ppnProductID);?>" title="<?php echo $item->vproductVendor;?>">
							         <img src="<?php echo $this->config->item('base_url')?>systemadmin/public/uploads/product_logo/<?php echo $item->logoName;?>" alt=""/>
							    	 <h5 style="color:#000;font-family: Times;">  <?php echo $item->vproductVendor;?> </h5>
							       </a>	
							    </div>
							    <?php }?>
				        	</div>  
						</div>
					</div>
					<div class="cb"></div>
					<div class="bottom_pages_afterlogin2"></div>
					<div class="cb"></div>
				    </div>
					<!------>
					<!--<div class="started-top-up">
						<div class="col-md-8 col-md-offset-2">
						<h3>To get started, select the mobile carrier and enter the mobile number you would like to top up</h3>
						<form>
							<div class="row">
								<div class="col-md-4">
									<select class="form-control form-control-default">
										<option value="">Select Carrier</option>
										<option value="Att">AT&amp;T</option>
										<option value="ExpoMobile">Expo Mobile</option>
										<option value="H2O">H2O Mobile</option>
										<option value="Net10">Net10 Mobile</option>
										<option value="Simple">Simple Mobile</option>
										<option value="Tmobile">T-Mobile</option>
										<option value="UltraMobile">Ultra Mobile</option>
										<option value="Verizon">Verizon Wireless</option>
									</select>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input type="text" class="form-control" name="">
									</div>
								</div>
								<div class="col-md-4">
									<input class="btn btn-primary form-control" value="Continue" disabled="disabled" type="submit">
								</div>
							</div>
						</form>
					  </div>
					</div>-->

			</div>
		</div>
	</div>
</div>

<div class="bottom-flags">

	<marquee id="news" scrollamount="5" scrolldelay="0" loop="20" onmousemove="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount',2,0);" direction="left" width="100%" height="80">
	            
	         <!--    <p align="justify"><span class="GSLabel">Current country coverage:-</span> <br></p> -->
				
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">
					Australia<br/>
					<img src="images/flags/AU.PNG" border="0" align="texttop"></a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Belgium<br/>
					<img src="images/flags/BE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Canada<br/>
					<img src="images/flags/CA.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">France<br/>
					<img src="images/flags/FR.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Germany<br/>
					<img src="images/flags/DE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Italy<br/>
					<img src="images/flags/IT.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">South Africa<br/>
					<img src="images/flags/ZA.PNG" border="0" align="texttop"></a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">	Spain<br/>
					<img src="images/flags/ES.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Sweden<br/>
					<img src="images/flags/SE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">UK<br/>
					<img src="images/flags/SH.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">USA<br/>
					<img src="images/flags/US.PNG" border="0" align="texttop"> </a>
				</div>  
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Norway <br/>
					<img src="images/flags/NO.PNG" border="0" align="texttop"> </a>
				</div> 
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">San Marino<br/>
					<img src="images/flags/SM.PNG" border="0" align="texttop"> </a>
				</div> 
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">India<br/>
					<img src="images/flags/IN.PNG" border="0" align="texttop"> </a>
				</div> 				
            </marquee>

</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




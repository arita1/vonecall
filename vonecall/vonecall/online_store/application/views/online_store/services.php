<?php $this->load->view('online_store/inc_header');?>
<style>
.services-page-tabs {width:100%; margin:0 auto;}
.homeDestinations {padding:30px; background:url("../img/servicess-bg.png"); float: left; background-size: cover;}
.homeDestinations h3{color:#fff; text-align:center; margin-bottom:30px; border-bottom: 1px solid rgb(255, 255, 255); border-bottom-style: dotted; padding-top: 0;}
.homeDestinations ul li {display:inline-block; width:40%; margin: 0 20px;}
.homeDestinations ul li  a{color:#fff;}
.homeDestinations ul li em{float: right;}
.services-page-tabs .nav-tabs {width: 70%; margin: 0 auto; }
/* .homeDestinations  ul{display:inline-block;} */
.homeDestinations .homeSearchBox-napp-fet li{display:block; color:#fff; width: 100%;}
.homeDestinations .banner-social-buttons li {display:block; margin:20px 70px;}
.testimonial {text-align:center;}
.testimonial p{color:#fff; font-style:italic;}	
	
</style>

	<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="img/Doodlebugs_Service_Banner.jpg" title="" /></div>
				
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
<div class=" clearfix">
				
		<div class="services-page-tabs services-tabs">
			<div class="tab-btns">
				<ul class="nav nav-tabs responsive-tabs">
				  <li class="active">
					<a href="#ser1" role="tab" data-toggle="tab" >Special Rates</a>
					<span class="down-arrow"></span>
					</li>
				  <li class="">
					<a href="#ser2" role="tab" data-toggle="tab">iOS/Android App</a>
					<span class="down-arrow"></span></li>
				  <li class="">
					<a href="#ser3" role="tab" data-toggle="tab">Pinless Calling </a>
				  <span class="down-arrow"></span></li>  
				</ul>
			</div>
			<div class="tab-content">
			  <div class="tab-pane active" id="ser1">
				<div class="tab-box testimonial">
					<div class="row">
							<div class="col-md-12">
								<div><img src="img/imageThree.jpg" /> </div>
								<div>
									<div class="homeDestinations">
										<ul class="lowestRatesForCountry">      
											<?php
											if(!empty($results_random))
											{
												foreach ($results_random as $value) {
													?>
													<li>
														<a href="#" title="Cuba Phone Cards">
															<?php echo $value->city; ?>
															<em> <?php echo $value->rate; ?> $</em>
														</a>
														
													</li>
													<?php
												}
											}
											?>
											
											
												

										</ul>
									</div>
								</div>
							</div>
					</div>

				</div>						  
			  </div>
			  <div class="tab-pane " id="ser2">
				<div class="tab-box testimonial">
					<div class="row">
							<div class="col-md-12">
								<div><img src="img/imageone.png" /> </div>
								<div class="homeDestinations">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<ul class="homeSearchBox-napp-fet">
											<li class="homeSearchBox-napp-wifi">
												Make Calls via WiFi/Data or Access Numbers
											</li>
											<li class="homeSearchBox-napp-top">
												Send money via TopUp to 300+ Destinations
											</li>
											<li class="homeSearchBox-napp-txt">
												Send International Text Messages
											</li>
											<li class="homeSearchBox-napp-his">
												Control Expenses with Call History
											</li>
											<li class="homeSearchBox-napp-sup">
												Get 24/7 In-App Support
											</li>
										</ul>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6">
									<ul class="list-inline banner-social-buttons">
										<li class="service_ios_pic">
											<a href="#" class="  "><img src="img/app-stor.png"></a>
										</li>
										 <li class="service_android_pic">
											<a href="#" class=" "><img src="img/gle-play.png"></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>						  
				</div>
			  </div>
			  <div class="tab-pane " id="ser3">
				<div class="tab-box testimonial">
					<div class="row">
						<div class="col-md-12">
							<div><img src="img/banner8.jpg" /> </div>
							<div class="homeDestinations">
								<h3>What is pinless calling ?</h3>
								<p style="color: #FFF;">&ldquo; Pinless feature is the ability of the Calling Card provider to recognize you automatically without the need of entering your PIN number. &rdquo;</p>
							</div>
						</div>
					</div>						  
				</div>
			  </div>
			</div>
		</div>

		</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




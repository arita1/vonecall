<!-- If you're using Stripe for payments -->
<?php $this->load->view('online_store/inc_header');?>
<style>
	.countryFlag span {
		float: left;
		width:100%;
		font-weight: bold;
		font-size: 14px;
	}
	.countryFlag{
		min-height: 120px;
	}
	.error {
	    color: red !important;
	    float: left !important;
	}
	.message{
		width:100%;
	}
	.box_makepayment label {	    
	    width: 340px;
	}
</style>

<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img5.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php

foreach ($customerResults as $value) {
$customerID = $value->customerID ;	
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$email = $value->email ;
$phone = $value->phone ;	
}


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
								<img src="<?php echo base_url(); ?>images/ico-phone.png">
								<h4>Enter the U.S. Mobile Number You'd Like to Recharge</h4>
							</div>
						</div>
						<div class="col-md-4">
							<div class="word-block">
								<img src="<?php echo base_url(); ?>images/ico-ammount.png">
								<h4>Choose an Amount</h4>
							</div>
						</div>
					</div>
					
					<!------>
					<div id="main" class="p5t p10b">
					  <div class="bg_tt_page"><div class="ac"><h3>Selected Product</h3></div></div>
					  <div class="center_page_afterlogin">
					    
					    <div class="col_big">
					        <div class="box_makepayment ">
					        	
					        	<!-- Loading Image -->
							    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
						    
						        <div class="countryBox">
						        	<div class="flag">
						            	 <div class="countryFlag">
						            	 	<img width="150" height="90" alt="<?php echo $results->vproductVendor ?>" src="<?php echo $this->config->item('base_url')?>systemadmin/public/uploads/product_logo/<?php echo $results->logoName;?>">
						            	 	<span> <?php echo $results->vproductName; echo isset($rechargeAmount)?' ('.$rechargeAmount.')':''
						            	 		?> </span>
						            	 </div> 
						            </div>            
						        </div>
						    
					            <?php echo form_open_multipart(site_url('vonecall-topup-usa-rtr-recharge/'.$results->ppnProductID), array('id'=>'topup_form', 'name'=>'topup_form'));?>
					           	<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
					    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
					            <div class="form-group">
					            	<div class="row">
					            		<div class="col-md-5">
					            			<label class="label1"> Mobile Number:</label>
					           			 </div>
					            		<div class="col-md-7">
					            			<input class="phone-num form-control" type="text" name="phoneNumber" maxlength="<?php echo $results->vLocalPhoneNumberLength?>" value="<?php echo isset($phone)?$phone:''?>" readonly="">
								         	<span class="coun-code" style="">+<?php echo $results->CountryCode?></span> 
								            <input type="hidden" name="countryCode" value="<?php echo $results->CountryCode?>">
								            <?php if (isset($error['phoneNumber'])) {?><span class="p155l red_color"><?php echo $error['phoneNumber'];?></span><div class="cb"></div><?php }?>
								            <div class="cb"></div>
								        </div>
					            	</div>
					            </div>
					            
					            <div class="form-group">
					            	<div class="row">
					            		<div class="col-md-5">  <label class="label1">Amount:</label></div>
					            		<div class="col-md-7">
								            <?php if(isset($recharge_amount)){
								            	echo form_dropdown('amount', $recharge_amount, (isset($amount)?$amount:''), 'class="w162 float_left" id="amount"');?>
								            <?php }else{ ?>
								            	<input type="text" name="amount" value="<?php echo isset($amount)?$amount:''?>" placeholder="<?php echo $rechargeAmount?>" />
								            	<input type="hidden" name="skuID" value="<?php echo isset($skuID)?$skuID:''?>" />
								            <?php }?>
								            <?php if (isset($error['amount'])) {?><span class="p155l red_color"><?php echo $error['amount'];?></span><div class="cb"></div><?php }?>
								            <div class="cb"></div>
								          </div>
					            	   </div>
					             	</div>
					              <div class="form-group">
					            	<div class="row">
					            		<div class="col-md-5"> <label class="label1">Sender Mobile:</label> </div>
					            		<div class="col-md-7"> 
					            			<input type="text" name="senderPhone" value="<?php echo isset($senderPhone)?$senderPhone:''?>">
				            				<?php if (isset($error['senderPhone'])) {?><span class="p155l red_color"><?php echo $error['senderPhone'];?></span><div class="cb"></div><?php }?>
				            				<div class="cb"></div>
				            			</div>
					            	</div>
					              </div>
					            <div class="form-group">
					            <div class="p155l p10b">
					            	<a class="bt_submit4 float_left" href="javascript:void(0);" id="submitButton"> Charge </a>
					            </div>
					            </div>
					            <?php echo form_close();?>
					           
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
					<img src="<?php echo base_url(); ?>images/flags/AU.PNG" border="0" align="texttop"></a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Belgium<br/>
					<img src="<?php echo base_url(); ?>images/flags/BE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Canada<br/>
					<img src="<?php echo base_url(); ?>images/flags/CA.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">France<br/>
					<img src="<?php echo base_url(); ?>images/flags/FR.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Germany<br/>
					<img src="<?php echo base_url(); ?>images/flags/DE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Italy<br/>
					<img src="<?php echo base_url(); ?>images/flags/IT.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">South Africa<br/>
					<img src="<?php echo base_url(); ?>images/flags/ZA.PNG" border="0" align="texttop"></a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">	Spain<br/>
					<img src="<?php echo base_url(); ?>images/flags/ES.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Sweden<br/>
					<img src="images/flags/SE.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">UK<br/>
					<img src="<?php echo base_url(); ?>images/flags/SH.PNG" border="0" align="texttop"> </a>
				</div>
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">USA<br/>
					<img src="images/flags/US.PNG" border="0" align="texttop"> </a>
				</div>  
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">Norway <br/>
					<img src="<?php echo base_url(); ?>images/flags/NO.PNG" border="0" align="texttop"> </a>
				</div> 
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">San Marino<br/>
					<img src="<?php echo base_url(); ?>images/flags/SM.PNG" border="0" align="texttop"> </a>
				</div> 
				<div style="padding:5px 0px 5px 10px;">
					<a href="#">India<br/>
					<img src="<?php echo base_url(); ?>images/flags/IN.PNG" border="0" align="texttop"> </a>
				</div> 				
            </marquee>

</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




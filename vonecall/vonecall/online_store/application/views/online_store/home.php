<?php $this->load->view('online_store/inc_header');?>


	<!-- slider -->
			<div id="main-slider" class="slider">
	    		<ul class="bxslider">
				  <li><img src="images/slider/img1.jpg" title="" /></li>
				  <li><img src="images/slider/img2.jpg" title="" /></li>
				  <li><img src="images/slider/img3.jpg" title="" /></li>
				  <li><img src="images/slider/img4.jpg" title="" /></li>
				</ul>
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
<div class="box-table">
		
	<div class="col-md-3 col-sm-3 col-xs-3">
		 <div class="home-col">
	        <div class="hmidleft">
	            <div class="block-title"><h4 class="label-primary">Mobile Topup</h4></div>
	            <div class="divcontent" style="height:205px;">
				<div class="vtip bullet" id="span1"><li><i class="fa fa-angle-double-right" ></i>Recharge US prepaid</li></div>
				<div class="vtip bullet" id="span2"><li><i class="fa fa-angle-double-right"></i>Easy to use</li></div>
				<div class="bullet"><li><i class="fa fa-angle-double-right"></i>Reliable</li></div>
				<div class="vtip bullet" id="span4"><li><i class="fa fa-angle-double-right"></i>Secure</li></div>
				<div class="bullet"><li><i class="fa fa-angle-double-right"></i>International Mobile Recharge</li></div>
				<div class="vtip bullet" id="span6"><li><i class="fa fa-angle-double-right"></i>200+ mobile networks</li></div>
				<div class="vtip bullet" id="span6"><li><a href="<?php base_url();?>vonecall-top-ups">Click here to see More</a></li></div>
				</div>
	        </div>
   
         </div>
	
	</div>
	<div class="col-md-6 col-sm-6 col-xs-6">
		 <div class="home-col">
	        <div class="hmidleft">
	            <div class="block-title"><h4 class="label-primary">Pinless Dailing</h4></div>
	            <div class="divcontent" style="height:205px;">
	            	<div class="row">
	            		<div class="col-md-6">
	            			<div class="vtip bullet" id="span1"><li><i class="fa fa-angle-double-right" ></i>Best International Rates</li></div>
							<div class="vtip bullet" id="span2"><li><i class="fa fa-angle-double-right"></i>Manage your accounts online</li></div>
							<div class="bullet"><li><i class="fa fa-angle-double-right"></i>Check Your Call Details</li></div>
							<div class="vtip bullet" id="span4"><li><i class="fa fa-angle-double-right"></i>No Contracts</li></div>
							<div class="bullet"><li><i class="fa fa-angle-double-right"></i>No Expiration Date</li></div>
							<div class="vtip bullet" id="span6"><li><i class="fa fa-angle-double-right"></i>No Hidden Fees</li></div>
							<div class="vtip bullet" id="span6"><li><i class="fa fa-angle-double-right"></i>Online top up</li></div>
	            		</div>
	            		<div class="col-md-6">
	            			<div class="join-now">
									<a href="<?php echo base_url() ?>vonecall-register"><img  style="margin-top: 45px;"  src="https://www.vonecall.com/images/Join-Now-PNG-Clipart.png"></a>
								</div>
	            		</div>
	            	</div>
					
				<!--<div class="vtip bullet" id="span6"><li><i class="fa fa-angle-double-right"></i>Use on Multiple Phones</li></div>-->
				</div>
	        </div>
   
         </div>
	
	</div>

	<!-- <div class="col-md-3 col-sm-3">
		
		<div class="hmidright">
	            <div class="block-title"><h4 class="label-primary">Special Rates</h4></div>
	            <div class="divcontent">
	         
	            <fieldset class="GSFieldset deals">
	               
	           			<!--<div class="deals-title">
	           				Bangladesh All	           				
	           			</div>-->
	           			<!-- <div class="dtls">
	           			<table class="dtlstable">
	           				<thead>
	           				<tr>
	           					
	           					<th>City</th>
	           					<th>Rates</th>
	           				</tr>
	           			</thead>
	           			<tbody>
	           		<?php if(!empty($results_random)){
	           		foreach($results_random as $new){
	           		?>
	           				<tr>
	           						           				
	           					<td><?php echo $new->city;?></td>
	           					<td><?php echo $new->rate;?> $</td>
	           				</tr>
	           		<?php } }?>	
	           		</tbody>
	           			</table>
	           			</div>
	           	   
	           		
           			</fieldset> -->
           										
					<!-- <div style="padding:10px;" class="GSLabel">To purchase package(s):<br>
           				<div style="padding:5px 0;">Existing user login <a href="user/login.html" style="padding:3px;cursor:pointer; font-size: 9px;line-height: 5px; box-shadow: none;border: 1px solid #8F4C00;" class="GSButton">Login</a></div>
						<div>New to  [<a href="user/registration.html">Open Account</a>]</div> 				
           			</div> -->
                <!-- </div>
	        </div>
	</div> -->
<div class="col-md-3 col-sm-3 col-xs-3">
		 <div class="home-col">
	        <div class="hmidleft">
	            <div class="block-title"><h4 class="label-primary">Mobile Apps</h4></div>
	            <div class="divcontent" style="height:205px;">
				<ul class="list-inline banner-social-buttons">	   
					<li>
						<a class="ios_img" href="#"><img src="img/app-stor.png"></a>
					</li>
					 <li>
						<a class="android_img" href="#"><img src="img/gle-play.png"></a>
					</li>
				</ul>
				</div>
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




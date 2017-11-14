<!-- If you're using Stripe for payments -->
<?php $this->load->view('online_store/inc_header');?>
<style>
.control-group1{
 text-align: center;   
}
label.control-label {
 display: inline-block; 
 margin-left: 20px;
}
.controls.selection {
 display: inline-block;   
}
label.radio {
 display: inline-block;
 margin-left: 40px;
}
.radio span {
 padding-right: 30px;
}
.error{
	color:red;
} 
</style>
<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php
//echo '<pre>';print_r($portaOneDetails);
//$balance=$portaOneDetails->balance;
foreach ($results as $value) {
$last_login = $value->last_login ;
$firstName = $value->firstName ;
$email = $value->email ;
$phone = $value->phone ;
$customerID = $value->customerID ;
$balance = $value->balance ;		
}


/*
 Last Login
Pinless Acount:
Mobile App
Mobile Top UP: 
  */

 ?>
<div class="content">
	<div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
<div class="call-rate-menu">
<div class="container">
<div class="row">
<?php $this->load->view('online_store/inc_pinless_menus');?>

<div class="col-md-7 col-md-offser-1 pinless-balance">
	<div class="row">
		<div class="col-md-12">
			<div class="show_blance form-inline">
		    	<div class="balance_img"><img src="<?php echo base_url(); ?>/images/Wallet-2-512.png"></img>     
		        </div>
		        <div class="balance_amt">
		        	<p>Balance $ <?php if($balance!=""){ echo $balance; }else{ echo '0'; }  ?></p>
		        </div>
		    </div>		
		</div>
		<?php 
		if($this->session->flashdata('error_message')!="")
		{
		?>
		
			<div style="padding:5px;text-align:center;font-size:initial;color:red;"><?php echo $this->session->flashdata('error_message');?></div>
		<?php }
		
		if($this->session->flashdata('success_message')!="")
		{	
		?>
			<div style="padding:5px;text-align:center;font-size:initial;color:green;"><?php echo $this->session->flashdata('success_message');?></div>
		<?php }
		 ?>
		
		<div class="col-md-12">
			            <!-- CREDIT CARD FORM STARTS HERE -->
            
            <div class="panel panel-default credit-card-box" id="funding_div">
                <div class="panel-body">
                    <form id="pinless_form" method="POST" action="<?php echo base_url();?>frontend/chargeAccount" >
                        
                       <!-- <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="couponCode">Phone Number</label>
                                    <input id="phoneNumberPortaOne" name="phoneNumberPortaOne" type="text" class="form-control" name="phoneNumberPortaOne" onchange="verifyPhoneNumberPortaOne(this.value)" 
                                    placeholder="Phone Number"/>
                                </div>
                            </div>                        
                       </div>-->
                      <!-- <input name="phone" id="phone" type="hidden" value="<?php //echo $phone; ?>" />-->
                        <!--task  message start-->  
							<span style="color:red">  
							<div id="errorMessagePhonePorta">
							</div>
							</span>
							<span style="color:green">  
							<div id="successMessagePhonePorta"></div>
							</span>
							<!--task  message end   -->
						
						<input id="customerID" name="customerID" type="hidden" value="<?php echo $customerID; ?>" />
						
						
						  <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="accountName">Account Name</label>
                                    <div class="input-group">
                                        <input 
                                        id="cardNumber"
                                            class="form-control"
                                            name="accountName"
                                            type="text"
                                            readonly=""
                                            value="<?php if(isset($firstName)&& $firstName!=""){ echo $firstName; }else{  echo ''; } ?>"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="accountName">Account Phone</label>
                                    <div class="input-group">
                                        <input 
                                        id="phone"
                                            class="form-control"
                                            name="phone"
                                            type="text"
                                            readonly=""
                                            value="<?php if(isset($phone)&& $phone!=""){ echo $phone; }else{  echo ''; } ?>"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
						<div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="amount">Pinless Amount</label>
                                    <div class="input-group">
                                       <!-- <input 
                                            id="amount"
                                            class="form-control"
                                            name="amount"
                                            placeholder="amount"
                                        />-->
                                        <select id="amount"
                                            class="form-control"
                                            name="amount">
	                                        <option value="">Amount</option>
	                                        <option value="5">$ 5</option>
	                                        <option value="10">$ 10</option>
	                                        <option value="20">$ 20</option>
	                                        <option value="30">$ 30</option>
	                                        <option value="40">$ 40</option>
	                                        <option value="50">$ 50</option>	
                                        <select>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="amount">Card Type</label>
                                    <label class="pull-right"><img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png"></label>
                                    <div class="input-group">
                                     <div class="control-group">
									  <div class="controls">
									    <label class="radio inline" for="radios-0">
									      <input type="radio" name="my_card_type" id="my_card_type" value="1" checked="checked">
									      <span>Saved<span>
									    </label>
									    <label class="radio inline" for="radios-1">
									      <input type="radio" name="my_card_type" id="my_card_type" value="2">
									      <span>New</span>
									    </label>
									 		 </div>
										</div>
									</div>
                                </div>                            
                            </div>
                        </div>
						
						<div class="new_card" style="display: none;">
							<div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">CARD NUMBER</label>
                                    <div class="input-group">
                                        <input 
                                        id="cardNumber"
                                           
                                            class="form-control"
                                            name="cardNumber"
                                            placeholder="Valid Card Number"
                                            autocomplete="cc-number"
                                             autofocus 
                                        />
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                          
                        <div class="row">
                            <div class="col-xs-7 col-md-7">
                                <div class="form-group">
                                    <label for="cardExpiry"><span class="hidden-xs">EXPIRATION</span><span class="visible-xs-inline">EXP</span> DATE</label>
                                    <input 
                                    id="cardExpiry"
                                        type="tel" 
                                        class="form-control myDateFormat" 
                                        name="cardExpiry"
                                        placeholder="MM-YYYY"
                                        autocomplete="cc-exp"
                                         
                                    />
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-5 pull-right">
                                <div class="form-group">
                                    <label for="cardCVC">CV CODE</label>
                                    <input 
                                        id="cardCVC"
                                        class="form-control"
                                        name="cardCVC"
                                        placeholder="CVC"
                                        autocomplete="cc-csc"
                                    />
                                </div>
                            </div>
                            
                            <div class="col-xs-5 col-md-5 pull-right">
                                <div class="form-group">
                                    <label for="cardCVC">Save card for future</label>
                                    <input type="checkbox" name="save_my_card" id="save_my_card" value="1">
                                </div>
                            </div>
                        </div>
						</div>
						
						<div class="saved_card">
						 <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="amount">Saved Cards</label>
                                    <div class="input-group">
                                       <!-- <input 
                                            id="amount"
                                            class="form-control"
                                            name="amount"
                                            placeholder="amount"
                                        />-->
                                        <select id="my_card_value"
                                            class="form-control"
                                            name="my_card_value"
                                            onchange="getval(this);"
                                            >
	                                        <option value="">Cards</option>
	                                        <?php 
	                                        if(!empty($saved_cards)){
	                                        	foreach ($saved_cards as $value) {?>
													<option value="<?php echo $value->sa_card_id; ?>"><?php echo substr($value->sa_card_number, 0, 4) . str_repeat('X', strlen($value->sa_card_number) - 8) . substr($value->sa_card_number, -4);?></option>	
												<?php }
	                                        }
	                                        ?>
	                                        
                                        
                                        <select>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="row">
	                            	<div class="col-xs-6">
		                                <div class="form-group">
			                               
				                                <div class="enter_cvv" id="enter_cvv" style="display: none">
				                                	 <label for="amount">CVV</label>
				                                	<div class="input-group">
				                                        <input 
				                                        id="my_cvv"
				                                            class="form-control"
				                                            name="my_cvv"
				                                            type="text"
															placeholder="CVV"
				                                            value=""
				                                        />
				                                    </div>
				                                </div>
		                                </div>
	                                </div>
                                </div>
                            
                           </div>
						</div>		
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingName">Billing Name</label>
                                    <div class="input-group">
                                        <input 
                                        id="billingName" class="form-control"
                                            name="billingName"
                                            placeholder="Billing Name"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-folder-o"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingAddress">Billing Address</label>
                                    <div class="input-group">
                                        <input 
                                        id="billingAddress" class="form-control"
                                            name="billingAddress"
                                            placeholder="Billing Address"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-adn"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingCity">Billing City</label>
                                    <div class="input-group">
                                        <input 
                                        id="billingCity" class="form-control"
                                            name="billingCity"
                                            placeholder="Billing City"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                          
                          <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingCountry">Billing Country</label>
                                    <div class="input-group">
                                        <!--<input 
                                        id="billingCountry" class="form-control"
                                            name="billingCountry"
                                            placeholder="Billing Country"
                                        />-->
                                         <select id="billingCountry"
                                            class="form-control"
                                            name="billingCountry">
	                                        <option value="">Select</option>
	                                        <option value="1">US</option>
	                                        <option value="2">CANADA</option>	
                                        <select>
                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingState">Billing State/Province</label>
                                    <div class="input-group">
                                    	<?php echo form_dropdown('billingState', $option_state, (isset($card_state)?$card_state:''), 'class="form-control"');?>
                                    	
                                        <!--<input 
                                        id="billingState" class="form-control"
                                            name="billingState"
                                            placeholder="Billing State"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>-->
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="billingZip">Billing Zip/Postal Code</label>
                                    <div class="input-group">
                                        <input 
                                        id="billingZip" class="form-control"
                                            name="billingZip"
                                            placeholder="Billing Zip"
                                        />
                                        <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                        
                         <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="amount">Agreement</label>
                                    <p>By Completing this form, you are authorizing us to charge the payment amount above against the payment method you selected. You also agree that the information you've provided is correct and that you are an authorized party on the account.</p>
                                    <div class="input-group">
                                     <div class="control-group">
									  <div class="controls">
									    <label class="radio inline" for="radios-0">
									      <input type="radio" name="agrrement" id="agrrement" value="1" checked="checked">
									      <span>Yes, I agree.<span>
									    </label>
									    <label class="radio inline" for="radios-1">
									      <input type="radio" name="agrrement" id="agrrement" value="2">
									      <span>No, I don't Agree.</span>
									    </label>
									 		 </div>
										</div>
									</div>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12"> <!--onclick="chargeAccount()"-->
                                <input  class="subscribe btn btn-success btn-lg btn-block" type="submit" name="pinless_submit" value="Charge"/>
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
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
</div>
<?php $this->load->view('online_store/inc_footer');?>




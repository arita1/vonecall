<!-- If you're using Stripe for payments -->
<?php $this->load->view('online_store/inc_header');?>

<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<!-- content -->
<?php
//echo '<pre>';print_r($portaOneDetails);
$balance=$portaOneDetails->balance;
foreach ($results as $value) {
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
		        		<a class="ghost-button-border-color" href="<?php echo base_url();?>vonecall-charge-wallet">Recharge</a>
		        </div>
		    </div>		
		</div>
		<div class="col-md-12">
			<div class="show_blance form-inline">
		    	<div class="balance_img"><img src="<?php echo base_url(); ?>/images/list-icon-7901.png"></img>     
		        </div>
		        <div class="balance_amt">
		        	<p>Recent Recharge</p>
		        		<a class="ghost-button-border-color" href="#">Transaction History</a>
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




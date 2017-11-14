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
		  	<!-- content -->
<div class="content">
<!--new-->
    	<div class="row_rates_table pinless_div">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-success access-table">
					<div class="panel-heading">
						<h3 class="panel-title">Transaction History</h3>
						<!--<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
								<i class="glyphicon glyphicon-filter"></i>
							</span>
						</div>-->
					</div>
					<div class="panel-body ">
						<input type="text" class="form-control search_string " id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="Search" />
					</div>
					<table class="table table-hover txn-history-tbl" id="task-table">
						
						<thead>
						  <tr>
						  	<th>#</th>
							<th>Card Number</th>
							<th>Transaction ID</th>
							<th>Approval Code</th>
							<th>Created On</th>
						  </tr>
						</thead>
						<tbody>
							<?php if(!empty($txn_history)){
								$i=1;
								foreach($txn_history as $res){?>
						  <tr>
						  	<td><?php echo $i; ?></td>
							<td><?php echo substr($res->x_card_num, 0, 4) . str_repeat('X', strlen($res->x_card_num) - 8) . substr($res->x_card_num, -4); ?></td>
							<td><?php echo $res->transactionID; ?></td>
							<td><?php echo $res->approvalCode; ?></td>
							<td><?php echo $res->createdOn; ?></td>
						  </tr>
						  <?php 
						  $i++;
								}
								
							}?>
						</tbody>
					
					
					</table>
				</div>
			</div>
			
		</div>
		</div>
	
<!--new ends-->
</div>
		</div>
	</div>
</div>







</div>
</div>
</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>




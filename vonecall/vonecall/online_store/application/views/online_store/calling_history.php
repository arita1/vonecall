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
		<div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
      <div class="col_big">
      	<!-- Form -->
        <div class="box_makepayment ">        	
            <div class="cb"></div>
            <?php echo form_open_multipart(site_url('vonecall-calling-history/'.$phone), array('id'=>'transaction_form', 'name'=>'transaction_form'));?>
            
            <label class="label1">From Date</label>
            <input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" class="box_makepayment_txt w264 float_left" type="text"/>
            <?php if (isset($error['from_date'])) {?><span class="p155l red_color"><?php echo $error['from_date'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">To Date</label>
            <input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" class="box_makepayment_txt w264 float_left" type="text"/>
            <?php if (isset($error['to_date'])) {?><span class="p155l red_color"><?php echo $error['to_date'];?></span><div class="cb"></div><?php }?>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <a class="bt_submit4 float_left" href="javascript:void(0);" onclick="$('.loading').show();$('#transaction_form').submit();"><?php echo $this->lang->line('submit');?></a>
            <a class="bt_submit4 float_left" href="<?php echo site_url('calling-history/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
            <?php echo form_close();?>           
            <div class="cb"></div>            
        </div>
        
        <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <div class="row">
			<div class="col-md-12">
				<div class="panel panel-success access-table">
					<div class="panel-heading">
						<h3 class="panel-title">Calling History</h3>
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
							<th>Calling From</th>
							<th>Called To</th>
							<th>Description</th>
							<th>Connect Time</th>
							<th>Duration</th>
							<th>Status</th>
						  </tr>
						</thead>
						<tbody>
							  <?php if(count($cdr_details) > 0){
	            	$i=1;
	            	foreach($cdr_details as $item){ ?>
		            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
		              <td align="left" class="boder_right"><?php echo $item->CLI;?></td>
		              <td align="center" class="boder_right"><?php echo $item->CLD;?></td>
		              <td align="center" class="boder_right"><?php echo $item->description;?></td>
		              <td align="center" class="boder_right"><?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?></td>
		              <td align="center" class="boder_right"><?php echo $item->charged_quantity;?></td>
		              <td align="center" class=""><?php echo $item->disconnect_reason;?></td>
		            </tr>	
	            <?php } ?>
	            	
	            <?php }else{ ?>
	            <tr>
	              <td colspan="6">No Data Match Your Query.</td>
	            </tr>
	            <?php }?>
						</tbody>
					
					
					</table>
				</div>
			</div>
			
		</div>
        <!-- Records -->
       <!-- <div class="box_phonenumber p10l p10r p10t p10b">
	        <table width="100%" cellspacing="0" cellpadding="0" border="0">
	          <tbody>
	            <tr class="bg_table">
	              <td width="5%" align="left" class="boder_right"><strong>Calling From</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Called To</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Description</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Connect Time</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Duration</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Status</strong></td>
	            </tr>
	            <?php if(count($cdr_details) > 0){
	            	$i=1;
	            	foreach($cdr_details as $item){ ?>
		            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
		              <td align="left" class="boder_right"><?php echo $item->CLI;?></td>
		              <td align="center" class="boder_right"><?php echo $item->CLD;?></td>
		              <td align="center" class="boder_right"><?php echo $item->description;?></td>
		              <td align="center" class="boder_right"><?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?></td>
		              <td align="center" class="boder_right"><?php echo $item->charged_quantity;?></td>
		              <td align="center" class=""><?php echo $item->disconnect_reason;?></td>
		            </tr>	
	            <?php } ?>
	            	
	            <?php }else{ ?>
	            <tr>
	              <td colspan="6">No Data Match Your Query.</td>
	            </tr>
	            <?php }?>
	          </tbody>
	        </table>
        <div class="cb"></div>
      </div>-->
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
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




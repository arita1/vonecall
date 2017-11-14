<?php $this->load->view('online_store/inc_header');?>
<link href="<?php echo $this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css" />
<style>
	.red {color:red !important;}
.vonecall-rates {padding-top: 50px; padding-bottom: 50px; text-align: center; color: #f8f8f8; background: url(../img/inner-banner4.png) no-repeat ; background-size: cover; height: 310px;}
.banner-text{position: relative; display: block; top: 110px;}
.rate-tabe {padding: 10px; line-height: 50px; box-shadow: 1px 1px 5px -3px #000;}
.rate-tabe .tab-content{background:#fff; padding:10px;}
.rate-tabe .nav-tabs > li {width: 50%;}
.search-country {width:100%;}
.search-country select {width:100%; padding: 10px; margin: 11px 0px; border: 1px solid #37bfff; border-radius: 2px;}
.vonecall-rate-page .tab-content p{color:#777; font-size: 18px; display: inline-block;}
.vonecall-rate-page .tab-box > ul > li {display:block; width:47%; float:left; color:#777; padding: 0 20px;}
.vonecall-rate-page .tab-box > ul > li >ul > li {background:#000;}
.ra_wr_r li {background: url(../img/check4.png) top left no-repeat; padding: 0 0 0 30px; margin: 0 0 10px 0; list-style: none;
line-height: 13px;}
.rate-tabe .active {border:none;}
.rate-tabe table  th, .rate-tabe table  td{color:#777 !important;}
.tab-box > ul:nth-child(1) > li:nth-child(1) {border-right: 1px solid #999;}
.rates h3, .rates p{color:red !important;}
.colam-2 {color:#777; background: #EDEAEA; padding:5px 10px; font-size: 17px; box-shadow: 1px 1px 5px -3px #000;}
.colam-2 {}
.colam-2 h4 {font-size:17px;}
.colam-2  thead  tr td:last-child {color:red;}
.colam-2 .red a{color:red;}
.colam-3 {padding:10px;}
.colam-3 p {border-bottom: 2px solid #EDEAEA;}
.colam-3 img {width:100%; padding: 10px;}
/*.tab-content.rates_table {
    max-height: 250px;
    overflow-x: hidden;
}*/
.table th {
  border-bottom:1px solid #eee !important;
  background: #ddd !important;
  color: #000 !important;
  padding: 10px 25px !important;
  font:bold !important;
}
</style>

	<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="images/slider/img1.jpg" title="" /></div>
				 
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
	<div class="rates-page">
				<div class="container">
					<div class="row">
						
						<div class="col-md-11 rates_div">
							<!-- <div class="title"><h3>Select a Country </h3></div> -->
							<div class="rate-tabe">
							<!-- Nav tabs -->
								<ul class="nav nav-tabs responsive-tabs">
								  <li class="active"><a href="#profile" role="tab" data-toggle="tab">View Rates</a></li>
								<!-- <li ><a href="#home" role="tab" data-toggle="tab">Select a Country</a></li>-->
								</ul>

								<!-- Tab panes -->
								<div class="tab-content rates_table">
								 
								  <div class="tab-pane active" id="profile">	
								  <div class="p10b" id="main" style="padding-top: 0;">
								  <div class="bg_tt_page"><div class="ac"><?php echo "Find best rate for you";//$this->lang->line('rate');?></div></div>
								  <div class="center_page_afterlogin" style="border-left: 1px solid #B3D7E0; border-right: 1px solid #B3D7E0; border-bottom: 1px solid #B3D7E0; padding-bottom: 15px;">
								    
								      <div class="box_rate p10l p10r p10t">
								        <div class="box_quicksearch" style="padding-left: 5px;">
								          <?php echo form_open_multipart(site_url('vonecall-rates'), array('id'=>'rate_form', 'name'=>'rate_form'));?>
								          <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
								          <table width="100%" cellspacing="0" cellpadding="0">
								            <tbody>
								              <tr>
								                <td width="6%"><label><?php echo $this->lang->line('country');?></label></td>
								                <td width="24%"><?php echo form_dropdown('country', $option_country, (isset($country)?$country:''), 'class="w132"');?></td>
								     	        <!--<td width="9%"><label><?php echo $this->lang->line('country_code');?></label></td>
								                <td width="16%"><input name="countryCode" value="<?php echo isset($countryCode)?$countryCode:'';?>" class="add_phone_text3" type="text"/></td>-->
								                <td width="6%"><label><?php echo $this->lang->line('balance');?></label></td>
								                <td width="24%"><?php echo form_dropdown('balance', $option_balance, (isset($balance)?$balance:''), 'class="w132"');?></td>
								     	        <td width="15%"><a class="bt_submit4 m10b" href="javascript:void(0);" onclick="$('#rate_form').submit();"><?php echo $this->lang->line('search');?></a></td>
								              </tr>
								            </tbody>
								          </table>
								          <?php echo form_close();?>
								        </div>
								        <div class="box_phonenumber p10l p10r p10t">
								        <table width="100%" cellspacing="0" cellpadding="0" border="0">
								          <tbody>
								            <tr class="bg_table">
								              <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('country');?></strong></td>
								              <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('description');?></strong></td>
								              <td width="25%" align="right" class="boder_right"><strong><?php echo $this->lang->line('rate');?></strong></td>
								              <td width="25%" align="right"><strong><?php echo $this->lang->line('minutes');?></strong></td>
								            </tr>
								            <?php $i=1;foreach($results as $item) {?>
								                  <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
								                    <td width="25%" class="boder_right"><?php echo $item->destination;?></td>
								                    <td width="25%" class="boder_right"><?php echo $item->city;?></td>
								                    <td width="25%" align="right" class="boder_right">
								                    	<!--$ <?php echo number_format($item->rate*100, 2);?> -->
								                    	<!-- ¢ --> 
								                    	<?php if($item->rate < 1){echo ($item->rate*100).'&cent;';  }else{ echo '$ '.number_format($item->rate, 2); };?>
								                    </td>
								                    <td width="25%" align="right"><?php echo number_format($balance/$item->rate, 0);?></td>
								                  </tr>
								                  <?php $i++;}?>
								          </tbody>
								        </table>
								        <?php echo isset($paging)?$paging:'';?>
								        <div class="cb"></div>
								      </div>
								      </div>
    
									    <div class="cb"></div>
									  </div>
									  <div class="cb"></div>
									  <div class="bottom_pages_afterlogin2"></div>
									  <div class="cb"></div>
									</div>
									<script>
									function paging(num) {
										$('input[name=page]').val(num);
										$('#rate_form').submit();
									}
									</script>
								</div>
								<!-- <div class="tab-pane" id="home">
									<div class="search-country">
										<select class="selectpicker my_dropdown" name="city_rate" id="city_rate" style="height: 40px;">
										<option selected="">Select a City</option>	
										<?php if(!empty($results_city)){
										foreach($results_city as $res){?>
										  <option value="<?php echo $res->ID;?>"><?php echo $res->city;?></option>
										<?php } 
										}?>
										
										  
										</select>
									</div>
									<div class="tab-box">
									
											<div class="rates" style="float: left;margin: 4px 0 7px 1px;width: 100%;">
												<div id="city_rates" style="color: #ff3377;float:left;font-size: 20px; font-size: 20px; float: left; margin-right:5px;"></div> 
												<div  id="destination" style="color:#ff3377; font-size: 20px; float: left;"></div>
											</div>
									</div>
										<div class="ra_wr_r">
                                                <p class="title" >Only pay for what you use</p>
                                                <div>
                                                    <li>No connection fees</li>
                                                    <li>No monthly fees</li>
                                                </div>
                                            </div>						  
								 </div>-->
								</div>
							</div>
						</div>
						<!--<div class="col-md-6 right">
							
							<div class="col-md-6">
								<div class="colam-2">
									<h4>Promotional Offer Rates</h4>
									
									<table class="table table-reflow">
									  <tbody>
									   <?php if(!empty($results_random)){
										foreach($results_random as $new){?>
										<tr>
									      <td scope="row"><a href="#"><?php echo $new->city;?></a></td>
										  <td class="red"><a href="#"><?php echo $new->rate;?> 	$</a> </td>
										</tr>
										<?php } 
										}?>
										
									  </tbody>
									</table>
								</div>
							</div>
							<div class="col-md-6">
								<div class="colam-3">
									<div>
										<p><a href="#"><img src="<?php echo base_url();?>img/FEATURES.png" /></a></p>
										<p><a href="#"><img src="<?php echo base_url();?>img/ab.jpg" /></a></p>
									</div>
								</div>
							</div>
						</div>-->
					</div>
					
				</div>
		</div>


</div>
<?php $this->load->view('online_store/inc_footer');?>




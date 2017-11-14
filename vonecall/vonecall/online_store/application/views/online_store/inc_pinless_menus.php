<div class="col-md-4 pinless-menues">
	<div class="show_blance">	
		<ul>
			<li class="<?php  if($this->uri->segment(1)=="vonecall-pinless-account"){ echo 'active'; } ?> ">
				<a href="<?php echo base_url();?>vonecall-pinless-account"><i class="fa fa-angle-double-right"></i> Summary</a>
			</li>
			<li class="<?php if($this->uri->segment(1)=="vonecall-numbers-alias"){ echo 'active'; } ?> ">
				<a href="<?php echo base_url();?>vonecall-numbers-alias"><i class="fa fa-angle-double-right"></i> Associated Numbers</a>
			</li>
			<li class="<?php if($this->uri->segment(1)=="vonecall-speed-dails"){ echo 'active'; } ?> ">
				<a href="<?php echo base_url();?>vonecall-speed-dails"><i class="fa fa-angle-double-right"></i> Speed Dial</a>
			</li>
			<li class="<?php if($this->uri->segment(1)=="vonecall-calling-history"){ echo 'active'; } ?> ">
				
				<a href="<?php echo base_url();?>vonecall-calling-history"><i class="fa fa-angle-double-right"></i> Calling History</a>
			</li>
			<li class="<?php if($this->uri->segment(1)=="vonecall-transaction-history"){ echo 'active'; } ?> ">
				<a href="<?php echo base_url();?>vonecall-transaction-history"><i class="fa fa-angle-double-right"></i> Transaction History</a>
			</li>
			<li class="<?php if($this->uri->segment(1)=="vonecall-charge-wallet"){ echo 'active'; } ?> ">
				<a href="<?php echo base_url();?>vonecall-charge-wallet"><i class="fa fa-angle-double-right"></i> Recharge Pinless</a>
			</li>
		</ul>
	</div>
</div>
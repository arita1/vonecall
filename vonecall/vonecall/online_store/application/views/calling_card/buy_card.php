<?php include APPPATH.'views/_header.php';?>
<style>
.cartfooter_box {
    border-bottom: 1px solid #d8d8d8;
    border-right: 1px solid #d8d8d8;
    display: inline-block;
    padding: 10px;
    vertical-align: top;
    width: 78%;
}
.cartfooter-finalamo h5 { font-size: 12px; }
.submit-button {
    background-color: #f8c26a;
    border: 1px solid #f2b24a;
    color: #fff;
    display: block;
    padding: 5px 35px;
    text-align: center;
}
.submit-button-disable {
    background-color: #ccc;
    border: 1px solid #9c9c9c;
    color: #fff;
    display: block;
    padding: 5px 35px;
    text-align: center;
}
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('buy_calling_card');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    
    <!-- Loading Image -->
    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
   
    <div class="col_big">
    	<?php if($validCommission){?>
        <div class="box_makepayment ">
            <?php echo form_open_multipart(site_url('buy-calling-card'), array('id'=>'buy_card_form', 'name'=>'buy_card_form'));?>
            <input type="hidden" name="total_amount" id="total_amount" value="" />
            
            <div class="cartheader">
            	<div class="calcard-head-1"><h4> Card </h4></div>
            	<div class="calcard-head-2 calcard-heading"><h4> Price </h4></div>
            	<div class="calcard-head-3 calcard-heading"><h4> Quantity </h4></div>
            	<div class="calcard-head-4 calcard-heading"><h4> Subtotal </h4></div>
            </div>
			
			<div class="operatorLogos">		    
			    <div class="cart-table-content calling-card"> 
                     <div class="calcard-thmb">
	            	   <a href="javascript:void(0)">
			            <img src="<?php echo $this->config->item('base-url')?>public/images/vonecall_calling_card.png" alt=""/>
			    	    <span> </span>
			           </a>	                     </div>
                     <div class="calcard-tit-pri">
                       <div class="cart-title"> <h5> Vonecall Calling Card  </h5> </div>
                       <div class="cart-price"> <h5>  </h5> </div>
                     </div>
	            		<div class="calcard-price">
	            		  <?php echo form_dropdown('card_batch', $option_batch, (isset($card_batch)?$card_batch:''), 'id="card_amount" onchange="update_amount()"');?>
	            		</div>
                     <div class="calcard-quantity">
                       <div class="quantity-buttons">
                          <input type="button" onclick="reduce_quantity()" value="-" class="minus">
                          <input type="text" readonly="readonly" maxlength="3" step="1" min="0" value="1" title="Qty" id="quantity" class="quantity" size="4" name="quantity">
                          <input type="button" onclick="add_quantity()" value="+" class="plus">
                       	  <!-- <input type="hidden" value="1" name="itemID"> -->
                        </div>                     
                     </div>
                     <div class="calcard-pri-tot">
                        <h5 id="final_amount"> $5 </h5>
                     </div>
                  </div>		    
		    </div>
            
            <div class="cartfooter"> 
              <div class="cartfooter-top">
              	<div class="cartfooter_box">              	
				  <div class="cartfooter-block-1">
				    <div class="cartfooter-info">
				      <h5> Toll Free </h5>
				      <span> <?php echo format_phone_number($tollFree)?> </span>
				    </div> 
				    <div class="cartfooter-info">
				      <h5> Customer Service number </h5>
				      <span> <?php echo format_phone_number($customerService)?> </span>
				    </div> 
				  </div>
				  <div class="cartfooter-block-2">
				    <div class="cartfooter-select">
				      <h5> Local Access Number </h5>
				      <?php echo form_dropdown('local_access_number', $option_accessNumber, (isset($local_access_number)?$local_access_number:''), '');?>
				    </div> 
				  </div>
				</div>		  	
			    <div class="cartfooter-block-3">
			      <div class="cartfooter-finalamo">
			    	<h4> Order Subtotal: <span id="subtotal"> $5 </span> </h4>
			      </div> 	
			      <div class="cartfooter-finalamo" style="margin-top: 20px;">
			    	<input type="checkbox" name="termsAndCondition" id="termsAndCondition" style="float: left; margin-right: 10px;" /> 
			    	<h5> <a href="<?php echo site_url('terms-and-conditions')?>" title="Terms and Condition" target="_blank" > Read &amp; Agreed Terms and Conditions </a> </h5>
			      </div> 
			    </div>				
			  </div>
			  <div class="cartfooter-bottom">	
              	<div class="cartfooter_box"> 		  	
				  <div class="cartfooter-block-1">				    
				    <div class="cartfooter-input">
				      <input name="phone_number" placeholder="Phone Number" maxlength="10" value="<?php echo isset($phone_number)?$phone_number:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
				      <?php if (isset($error['phone_number'])) {?><span class="red_color"><?php echo $error['phone_number'];?></span><div class="cb"></div><?php }?>
				    </div> 
				    <div class="cartfooter-input">
				      <input name="email" placeholder="Email" value="<?php echo isset($email)?$email:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
				      <?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><div class="cb"></div><?php }?>
				    </div> 
				  </div>		  	
				  <div class="cartfooter-block-2"> 
					<div class="cartfooter-finalamo" style="text-align: center;">
						<h4 style="font-weight: bold;"> Notify Me </h4>
						<span class="span"> Email </span>
						<span class="span"> Text  </span>
						<span class="span"> Print </span>
						
						<span class="span"> <input type="checkbox" name="notify_by_email" <?php echo isset($notify_by_email)?'checked="checked"':'';?> /> </span>
						<span class="span" style="margin-left: 30px;"> <input type="checkbox" name="notify_by_text" <?php echo isset($notify_by_text)?'checked="checked"':'';?> /> </span>
						<span class="span" style="margin-left: 20px;"> <input type="checkbox" name="notify_by_print" checked="" <?php echo isset($notify_by_print)?'checked="checked"':'';?> /> </span>
					</div>
				  </div>	
				</div>	  	
				  <div class="cartfooter-block-3">
				  	<div class="cartfooter-submit" style="margin-left: 8%;">
				      <input type="button" class="submit-button-disable" name="" id="submitButton" value="Purchase" onclick="confirm_card_submit()" disabled="" />
				    </div>
				  </div>
			  </div>
			</div>  
			<?php echo form_close();?>			          
        </div>
        <?php }else{ ?>
        	<div class="box_makepayment ">
        		<h2 align="center"> You are not authorized for selling this product </h2>
        	</div>
        <?php }?>
      </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
          
  </div>
</div>

<script type="text/javascript">
	// Enable / Disable Submit button
	$('#termsAndCondition').click(function () {
	    //check if checkbox is checked
	    if ($(this).is(':checked')) {	        
	        $('#submitButton').removeAttr('disabled'); //enable input	   
 			 $('#submitButton').removeClass('submit-button-disable').addClass('submit-button');
	    } else {
	       $('#submitButton').attr('disabled', true); //disable input 
	        $('#submitButton').removeClass('submit-button').addClass('submit-button-disable');
	    }
	});
	
	function confirm_card_submit(){
		var cardQuantity = $('#quantity').val();
		var cardAmount   = $('#card_amount option:selected').text();
		var newAmount 	  = cardQuantity * cardAmount.replace('$', "");
		if(confirm('You are about to purchase '+cardQuantity+' calling cards with amount $'+newAmount/cardQuantity+' per card')){
			$('#buy_card_form').submit()
		}else{
			return false;
		}
	}
	
	// Add Item Quantity
	function add_quantity(){
		var cardQuantity = $('#quantity').val();
		var cardAmount   = $('#card_amount option:selected').text();
		var newQuantity = Number(cardQuantity) + Number(1); 
		
		var newAmount = newQuantity * cardAmount.replace('$', "");
		
		// Add Quantity
		$('#quantity').val(newQuantity);
		
		// Update Final Amount
		$('#final_amount').text('$ '+newAmount);
		$('#total_amount').val(newAmount);
		$('#subtotal').text('$ '+newAmount);		
	}
	
	// Remove Item Quantity
	function reduce_quantity(){
		var cardQuantity = $('#quantity').val();
		var cardAmount   = $('#card_amount option:selected').text();
		var newQuantity  = cardQuantity-1;
		
		// Deduct Quantity
		if(cardQuantity > 1){
			$('#quantity').val(newQuantity);
			var newAmount = newQuantity * cardAmount.replace('$', "");
			
		} else {
			$('#quantity').val(cardQuantity);
			var newAmount = cardQuantity * cardAmount.replace('$', "");
		}
		// Update Final Amount
		$('#final_amount').text('$ '+newAmount);
		$('#total_amount').val(newAmount);
		$('#subtotal').text('$ '+newAmount);
	}
	
	// Update Amount on Quantity Change
	function update_amount(){
		var cardQuantity = $('#quantity').val();
		var cardAmount   = $('#card_amount option:selected').text();
		var newAmount    = cardQuantity * cardAmount.replace('$', "");
		
		// Update Final Amount
		$('#final_amount').text('$ '+newAmount);
		$('#total_amount').val(newAmount);
		$('#subtotal').text('$ '+newAmount);
	}
	update_amount();
	
	function confirmSubmit(){
		var denomination = $("#final_amount").text();
		var cardQuantity = $('#quantity').val();
		if(confirm('You are about to purchase the following cards ( Denoimation '+denomination+' quanity '+cardQuantity+') and you have also read and agreed to the Terms and conditions of this purchase')){
			$('#buy_card_form').submit();
		}else{
			return false;
		}
	}
</script>

<?php include APPPATH.'views/_footer.php';?>
<?php include '_header.php';?>
<div id="main" class="mh730">
      <div class="bg_title"> Calling card instructions </div>
      <div class="p20l p20r p13b">
		<div class="bg_title_content">  </div>
		<div class="form_addcustomer p15t">
			<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
			<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
			
			<?php echo form_open_multipart(site_url('calling-cards-instructions'), array('id'=>'instructions', 'name'=>'instructions'));?>
		      
		      <label>Instruction</label>
		      <textarea style="width:600px; height:120px;" name="cardInstruction" id="cardInstruction" cols="45" rows="5"><?php echo isset($cardInstruction)?$cardInstruction:'';?></textarea>
		      <?php if (isset($error['cardInstruction'])) {?><span class="red_color"><?php echo $error['cardInstruction'];?></span><?php }?>
		      <div class="cb"></div>
		      
		      <label>&nbsp;</label>
		      <div class="cb"></div>
		      
		      <label>Disclaimer</label>
		      <textarea style="width:600px; height:120px;" name="ccardDisclaimer" id="ccardDisclaimer" cols="45" rows="5"><?php echo isset($ccardDisclaimer)?$ccardDisclaimer:'';?></textarea>
		      <?php if (isset($error['ccardDisclaimer'])) {?><span class="red_color"><?php echo $error['ccardDisclaimer'];?></span><?php }?>
		      <div class="cb"></div>
		      
		      <label>&nbsp;</label>
		      <div class="cb"></div>
		      
		      <label>Toll Free Number</label>
		      <input type="text" name="ccardTollFree" value="<?php echo isset($ccardTollFree)?$ccardTollFree:'';?>" class="w162" />
		      <?php if (isset($error['ccardTollFree'])) {?><span class="red_color"><?php echo $error['ccardTollFree'];?></span><?php }?>
		      <div class="cb"></div>
		      
		      <label>Customer Service</label>
		      <input type="text" name="ccardCustomerService" value="<?php echo isset($ccardCustomerService)?$ccardCustomerService:'';?>" class="w162" />
		      <?php if (isset($error['ccardCustomerService'])) {?><span class="red_color"><?php echo $error['ccardCustomerService'];?></span><?php }?>
		      <div class="cb"></div>
		      
		      <label>&nbsp;</label>
		      <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#instructions').submit();" >Submit</a>
		      <div class="cb"></div>
		      
		      <?php echo form_close();?>
		  </div>
		</div>
	</div>
  </div>
</div>


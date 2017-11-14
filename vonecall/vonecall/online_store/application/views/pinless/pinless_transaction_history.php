<style>
	.label{ width: 222px !important; }
	.center_page_afterlogin { width: 779px;}
	.p155l {
	    line-height: 25px;
	    margin-left: -150px;
	}	
</style>

  <div class="bg_tt_page"><div class="ac">Transaction History</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
      <div class="col_big">
      	<!-- Form -->
        <div class="box_makepayment ">        	
            <div class="cb"></div>
            <?php echo form_open_multipart(site_url('transaction-history/'.$phone), array('id'=>'transaction_form', 'name'=>'transaction_form'));?>
            
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
            <a class="bt_submit4 float_left" href="<?php echo site_url('transaction-history/'.$phone);?>" onclick="$('.loading').show();"><?php echo $this->lang->line('cancel');?></a>
            <?php echo form_close();?>           
            <div class="cb"></div>            
        </div>
        
        <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
        <!-- Records -->
        <div class="box_phonenumber p10l p10r p10t p10b">
	        <table width="100%" cellspacing="0" cellpadding="0" border="0">
	          <tbody>
	            <tr class="bg_table">
	              <td width="15%" align="center" class="boder_right"><strong>Transaction Date</strong></td>
	              <td width="5%" align="left" class="boder_right"><strong>Amount</strong></td>
	              <td width="15%" align="center" class="boder_right"><strong>Transaction Type</strong></td>
	            </tr>
	            <?php if(count($cdr_details) > 0){
	            	$i=1;
	            	foreach($cdr_details as $item){ ?>
		            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
		              <td align="center" class="boder_right"><?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?></td>
		              <td align="left" class="boder_right">$<?php echo ($item->charged_amount < 0)?-$item->charged_amount:$item->charged_amount;?></td>
		              <td align="center" class="boder_right"><?php echo ($item->CLD=='Refund')?'Refund':'Recharge';?></td>
		            </tr>	
	            <?php $i++; } ?>
	            	
	            <?php }else{ ?>
	            <tr>
	              <td colspan="6">No Data Match Your Query.</td>
	            </tr>
	            <?php }?>
	          </tbody>
	        </table>
        <div class="cb"></div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
</div>

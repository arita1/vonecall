<?php include APPPATH.'views/_header.php';?>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.box_makepayment .label1 {width: 200px;}
.btn_checkbox {margin-left:205px; width: 330px;}
.box_makepayment .label_short1 {width: 80px;}
.add_phone_text3 {width: 39px;}
</style>
<?php } else {?>
<style>
.box_makepayment .label1 {width: 160px;}
</style>
<?php } ?>
<style>
.refund_button{ 
	background-color: #039dcf;
    border: 1px solid #039dcf;
    border-radius: 4px;
    color: #fff;
    font-size: 12px;
    padding: 3px 6px; 
}
.refund_button:hover{
	background-color: #DEDEDE;
    border: 1px solid #4b4b4b;
    border-radius: 4px;
    color: #4b4b4b;
    text-decoration: none;
}
</style>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('pinless_activity');?></div></div>
  <div class="center_page_afterlogin">
    <dl id="system-message">
    	<dd class="message notemess"> <ul> <li>Sales Transaction must not be older than 5 days and the account should have full balance</li> </ul> </dd>
    </dl>
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <?php if (isset($error['email_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['email_error'];?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <div class="box_makepayment">
      	<!--
        <?php echo form_open_multipart(site_url('pinless-activity'), array('id'=>'pinless_activity_form', 'name'=>'pinless_activity_form'));?>
        
        <?php if (isset($error['phone_customer'])) {?><span class="red_color"><?php echo $error['phone_customer'];?></span><?php }?>
        <label class="label1">Phone Number:</label>
        <input name="phone_customer" value="<?php echo isset($phone_customer)?$phone_customer:'';?>" type="text" class="box_makepayment_txt w105 float_left"/>
        
        <a class="bt_submit4 float_left m10l" href="javascript:void(0);" onclick="$('#pinless_activity_form').submit();"><?php echo $this->lang->line('submit');?></a>
        <br class="cb">    
        <?php echo form_close();?>
       -->
      </div>        
      <!-- Display Results -->
      <?php if (isset($results)) {?>
      <div class="box_phonenumber p10l p10r p10t">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
              <td width="20%" align="left" class="boder_right"><strong><?php echo $this->lang->line('date');?></strong></td>
              <td width="12%" align="left" class="boder_right"><strong><?php echo $this->lang->line('product');?></strong></td>
              <td width="12%" align="left" class="boder_right"><strong>Product Type</strong></td>
              <td width="12%" align="right" class="boder_right"><strong><?php echo $this->lang->line('store_commission');?></strong></td>
              <td width="12%" align="right" class="boder_right"><strong><?php echo $this->lang->line('sale');?></strong></td>
              <td width="12%" align="right" class="boder_right"><strong>Number</strong></td>               
              <td width="12%" align="center" ><strong>Action</strong></td>            
            </tr>
            <?php if(count($results)>0) {?>
            <?php $i=1;$totalAmount=0; $totalStoreCommission=0; $totalCommission=0;
            foreach($results as $item) { 
	            $productType = $item->vproductCategory;
			?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td class="boder_right" align="left" ><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
              <td class="boder_right"><?php echo $item->vproductName?></td>
              <td class="boder_right" align="right"><?php echo $productType?></td>
              <td class="boder_right" align="right" class="boder_right"><?php echo format_price($item->storeCommission)?></td>
              <td align="right" class="boder_right"><?php echo $item->isRefund?'-':''?><?php echo format_price($item->chargedAmount)?></td>
              <td align="right" class="boder_right"><?php echo format_phone_number($item->phoneNumber)?></td>
              <td align="center" class="refund"> <?php if($item->isRefund){ echo 'Refunded'; }else{?> <a href="<?php echo site_url('pinless-refund/'.$item->customerID.'/'.$item->seqNo);?>" title="Refund" class="refund_button" >Refund</a> <?php }?></td>              
            </tr>
           <?php $totalAmount += $item->isRefund?0:(float)$item->chargedAmount;?>
	        <?php $totalStoreCommission += (float)$item->storeCommission;?>
	        <?php $totalCommission += (float)$item->accountRepCommission;?>
	        <?php $i++; }?>
	        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>" style="font-weight:bold;">
	          <td colspan="4"> &nbsp; </td>
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalAmount);?></td>
	          <!--
	          <td align="right">Total: &nbsp;&nbsp;&nbsp;<?php echo format_price($totalStoreCommission);?></td>-->
	          <td colspan="2"> &nbsp; </td>
	          
	        </tr>
            <?php } else {?>
            <tr>
              <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <div class="cb"></div>
      </div>
      <?php }?>
    </div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
 
<?php include APPPATH.'views/_footer.php';?>
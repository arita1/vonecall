<?php include '_header.php';?>
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

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('current_balance');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <?php if (isset($error['email_error'])) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $error['email_error'];?></li></ul></dd></dl><?php }?>
    <div class="col_big">
        <div class="box_makepayment ">
            <!-- Refund form -->
            <?php echo form_open_multipart(site_url('refund'), array('id'=>'refund_form', 'name'=>'refund_form'));?>
            
            <label class="label1"> Your current balance: </label>
            <span style="color: #6DC815; font-size: 14px; font-weight: bold; line-height: 30px;"> <?php echo format_price($info->balance);?> </span>
            <div class="cb"></div>   
            
            <?php if (isset($error['refundAmount'])) {?><span class="p155l red_color"><?php echo $error['refundAmount'];?></span><div class="cb"></div><?php }?>
            <label class="label1">Enter Amount for refund:<span class="red_color">*</span></label>
            <input name="refundAmount" value="<?php echo isset($refundAmount)?$refundAmount:'';?>" class="box_makepayment_txt w264 float_left" type="text"/>
            <div class="cb"></div>
            
            <?php if (isset($error['refundReason'])) {?><span class="p155l red_color"><?php echo $error['refundReason'];?></span><div class="cb"></div><?php }?>
            <label class="label1">Reason for refund:<span class="red_color">*</span></label>
            <textarea cols="35" rows="6" name="refundReason"><?php echo isset($refundReason)?$refundReason:'';?></textarea>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <div class="cb"></div>
            
            <label class="label1">&nbsp;</label>
            <a class="bt_submit float_left" href="javascript:void(0);" onclick="$('#refund_form').submit();"><?php echo $this->lang->line('submit');?></a>
            <div class="cb"></div>
            <?php echo form_close();?>         
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>
 
<?php include '_footer.php';?>
<?php include '_header.php';?>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('update_password');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
      <!--  
      <div class=" p13l p12t p10r">
        <div class="box_info">
          <table width="32%"   border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="51%" class="bd_r"><?php echo $this->lang->line('credit_balance');?>: <span class="ogr_color"><strong><?php echo format_price($info->balance);?></strong></span></td>
              <td width="49%" align="left" class="p20l"><?php echo $this->lang->line('store_code');?>: <span class="ogr_color"><strong><?php echo substr($info->securityCode, -7);?></strong></span></td>
            </tr>
          </table>
        </div>
        <br />
      </div>
      -->
      <div class="box_makepayment ">
        <?php echo form_open_multipart(site_url('update-password'), array('id'=>'profile_form', 'name'=>'profile_form'));?>
                
        <?php if (isset($error['currentPassword'])) {?><span class="p190l red_color"><?php echo $error['currentPassword'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('current_password');?>:</label>
        <input type="password" name="currentPassword" value="<?php echo isset($currentPassword)?$currentPassword:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['agentPassword'])) {?><span class="p190l red_color"><?php echo $error['agentPassword'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('new_password');?>:</label>
        <input type="password" name="agentPassword" value="<?php echo isset($agentPassword)?$agentPassword:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <?php if (isset($error['agentPasswordConfirm'])) {?><span class="p190l red_color"><?php echo $error['agentPasswordConfirm'];?></span><div class="cb"></div><?php }?>
        <label><?php echo $this->lang->line('confirm_password');?>:</label>
        <input type="password" name="agentPasswordConfirm" value="<?php echo isset($agentPasswordConfirm)?$agentPasswordConfirm:'';?>" class="box_makepayment_txt w264" />
        <br class="cb">
        
        <div class="p190l p5t p10b"> <a class="bt_submit" href="javascript:void(0);" onclick="$('#profile_form').submit();"><?php echo $this->lang->line('update');?></a></div>
        <?php echo form_close();?>
      </div>
    </div>
    <div class="cb"></div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
<?php include '_footer.php';?>
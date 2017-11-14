<?php include APPPATH.'views/_header.php';?>
<div class="p10b" id="main" style="padding-top: 0;">
  <div class="bg_tt_page"><div class="ac">Pinless Rates</div></div>
  <div class="center_page_afterlogin" style="border-left: 1px solid #B3D7E0; border-right: 1px solid #B3D7E0; border-bottom: 1px solid #B3D7E0; padding-bottom: 15px;">
    
      <div class="box_rate p10l p10r p10t">
        <div class="box_quicksearch" style="padding-left: 5px;">
          <?php echo form_open_multipart(site_url('pinless-rate'), array('id'=>'rate_form', 'name'=>'rate_form'));?>
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
                    <td width="25%" align="right" class="boder_right"> <?php if($item->rate < 1){echo ($item->rate*100).'&cent;';  }else{ echo '$ '.number_format($item->rate, 2); };?> <!-- ¢ --> </td>
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
<?php include APPPATH.'views/_footer.php';?>
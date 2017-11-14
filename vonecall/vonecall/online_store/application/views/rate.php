<?php include '_header.php';?>
<link href="<?php echo $this->config->item('base_url')?>public/css/scroll.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#scrollbar1').tinyscrollbar({ sizethumb: 40 });	
});
</script>
<div class="p5t p10b" id="main">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('rate');?></div></div>
  <div class="center_page_afterlogin">
    <div class="col_big">
      <div class="box_rate p10l p10r p10t">
        <div class="box_quicksearch">
          <?php echo form_open_multipart(site_url('rate'), array('id'=>'rate_form', 'name'=>'rate_form'));?>
          <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
          <table width="100%" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td width="6%"><label><?php echo $this->lang->line('country');?></label></td>
                <td width="24%"><?php echo form_dropdown('country', $option_country, (isset($country)?$country:''), 'class="w132"');?></td>
     	        <td width="9%"><label><?php echo $this->lang->line('country_code');?></label></td>
                <td width="16%"><input name="countryCode" value="<?php echo isset($countryCode)?$countryCode:'';?>" class="add_phone_text3" type="text"/></td>
                <td width="6%"><label><?php echo $this->lang->line('balance');?></label></td>
                <td width="24%"><?php echo form_dropdown('balance', $option_balance, (isset($balance)?$balance:''), 'class="w132"');?></td>
     	        <td width="15%"><a class="bt_submit4 m10b" href="javascript:void(0);" onclick="$('#rate_form').submit();"><?php echo $this->lang->line('search');?></a></td>
              </tr>
            </tbody>
          </table>
          <?php echo form_close();?>
        </div>
        <div class="box_scrolltitle">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr class="bg_title">
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('country');?></strong></td>
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('country_code');?></strong></td>
                <td width="25%" align="right" class="boder_right"><strong><?php echo $this->lang->line('rate');?></strong></td>
                <td width="25%" align="right"><strong><?php echo $this->lang->line('minutes');?></strong></td>                              
              </tr>
            </tbody>
          </table>
        </div>
        <div id="scrollbar1">
          <div class="scrollbar" style="height: 400px;">
            <div class="track" style="height: 400px;">
              <div class="thumb" style="top: 0px; height: 61.2792px;">
                <div class="end"></div>
              </div>
            </div>
          </div>
          <div class="viewport">             
            <div class="overview rate" style="top: 0px;">
              <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <?php $i=1;foreach($results as $item) {?>
                  <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
                    <td width="25%" class="boder_right"><?php echo $item->country;?></td>
                    <td width="25%" class="boder_right"><?php echo $item->countryCode;?></td>
                    <td width="25%" align="right" class="boder_right"><?php echo number_format($item->rate*100, 2);?> ¢ </td>
                    <td width="25%" align="right"><?php echo number_format($balance/$item->rate, 0);?></td>
                  </tr>
                  <?php $i++;}?>
                </tbody>
              </table>              
            </div>
          </div>
          <div class="cb"></div>
        </div>
        <div class="cb"></div>
        <?php echo isset($paging)?$paging:'';?>
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
<?php include '_footer.php';?>
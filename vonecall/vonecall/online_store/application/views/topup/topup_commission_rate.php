<?php include APPPATH.'views/_header.php';?>
<link href="<?php echo $this->config->item('base_url')?>public/css/scroll.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#scrollbar1').tinyscrollbar({ sizethumb: 40 });	
});
</script>
<div class="p5t p10b" id="main">
  <div class="bg_tt_page"><div class="ac">Topup Product</div></div>
  <div class="center_page_afterlogin">
    <div class="col_big">
	  <div class="box_makepayment">
	    <?php echo form_open_multipart(site_url('topup-commission-rate'), array('id'=>'topup_commission_rate', 'name'=>'topup_commission_rate'));?>
		<input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
        <label class="label_short1"><?php echo $this->lang->line('country');?>:</label>
        <?php echo form_dropdown('country', $option_country, (isset($country)?$country:''), 'class="w132 float_left"');?>
        <a class="bt_submit4 float_left m10l" href="javascript:void(0);" onclick="$('#topup_commission_rate').submit();"><?php echo $this->lang->line('search');?></a>
        <br class="cb">    
        <?php echo form_close();?>
		
	  </div>
      <div class="box_rate p10l p10r p10t">
        <div class="box_quicksearch">
          
        </div>
        <div class="box_scrolltitle">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr class="bg_title">
                <td width="20%" class="boder_right"><strong>Country Name</strong></td>
                <td width="20%" class="boder_right"><strong>Operator</strong></td>
                <td width="20%" align="right" class="boder_right"><strong>Top-Up Amount</strong></td>
                <td width="20%" align="right" class="boder_right"><strong>Local Value</strong></td>
                <td width="20%" align="right"><strong>Commission Rate</strong></td>                              
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
                    <td width="20%" class="boder_right"><?php echo $item->country;?></td>
                    <td width="20%" class="boder_right"><?php echo $item->operator;?></td>
                    <td width="20%" align="right" class="boder_right"><?php echo number_format($item->sellingPrice, 2);?> <?php echo $item->sellingCurrency;?> </td>
                    <td width="20%" align="right" class="boder_right"><?php echo number_format($item->localValue, 2);?> <?php echo $item->localCurrency;?> </td>
                    <td width="20%" align="right"><?php echo number_format($item->discount * $commissionRate/100, 2);?> %</td>
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
	$('#topup_commission_rate').submit();
}
</script>
<?php include APPPATH.'views/_footer.php';?>
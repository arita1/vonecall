<?php include '_header.php';?>
<link href="<?php echo $this->config->item('base_url')?>public/css/scroll.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo $this->config->item('base_url')?>public/js/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#scrollbar1').tinyscrollbar({ sizethumb: 40 });	
});
</script>
<div class="p5t p10b" id="main">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('access_number');?></div></div>
  <div class="center_page_afterlogin">
    <div class="col_big">
      <div class="box_rate p10l p10r p10t">
        <!--  
        <div class="box_quicksearch">
          <table width="100%" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td width="6%"><label>Country</label></td>
                <td width="31%">  <select name="" class="w268"></select></td>
     	        <td width="9%">Country Code</td>
                <td width="54%">  <select name="" class="w268"></select></td>
              </tr>
            </tbody>
          </table>
        </div>
        -->
        <div class="box_scrolltitle">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr class="bg_title">
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('state');?></strong></td>
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('city');?></strong></td>
                <td width="25%" class="boder_right"><strong><?php echo $this->lang->line('access_number');?> (English)</strong></td>
                <td width="25%"><strong><?php echo $this->lang->line('access_number');?> (Spanish)</strong></td>                              
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
                    <td width="25%" class="boder_right"><?php echo $item->State;?></td>
                    <td width="25%" class="boder_right"><?php echo $item->City;?></td>
                    <td width="25%" class="boder_right"><?php echo format_phone_number($item->AccessNumber);?></td>
                    <td width="25%"><?php echo format_phone_number($item->AccessNumber2);?></td>
                  </tr>
                  <?php $i++;}?>
                </tbody>
              </table>              
            </div>
          </div>
          <div class="cb"></div>
        </div>
        <div class="cb"></div>
      </div>
    </div>
    <div class="cb"></div>
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
<?php include '_footer.php';?>
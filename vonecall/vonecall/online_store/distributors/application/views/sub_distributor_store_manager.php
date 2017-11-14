<?php include '_header.php';?>
<div id="main" class="mh730">
      <div class="bg_title"> Sub-Distributor Store Management </div>
      <div class="p20l p20r p13b">
        
        <div class="form_addcustomer p15t">
          <div class="cb p10b"> </div>
          
          <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
          
          <?php echo form_open_multipart(site_url('sub-distributor-stores'), array('id'=>'account_dist_manager', 'name'=>'account_dist_manager'));?>
          <input type="hidden" id="search_key" name="search_key" value="<?php echo isset($search_key)?$search_key:'';?>"/>
          <input type="hidden" id="search_value" name="search_value" value="<?php echo isset($search_value)?$search_value:'';?>"/>
          <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
          <input type="hidden" id="excel" name="excel" value="0"/>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Search by Sub-distributor</td>             
            </tr>
            <tr>
              <td><?php echo form_dropdown('agent', $option_sub_dist, (isset($agent)?$agent:''), 'id="search_by_sub_dist" class="w162"');?></td>
            </tr>
            <tr>
              <td>
              	<!--<a href="javascript:void(0);" onclick="rep_search('search_by_sub_dist');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a>-->
              	<a style="margin-bottom: 7px;" onclick="$('#account_dist_manager').submit();" href="javascript:void(0);" class="bt_atatement_address">Search</a>
              </td>
            </tr>
          </table>
          <?php echo form_close();?>
        </div>
        <!--
        <p class="p10 bo_t_d m10t">
          <input type="hidden" id="search_by_all" value="1"/>
          <a class="bt_atatement_address" onclick="rep_search('search_by_all');">View Stores</a>
        </p>
        -->
        <div class="box_phonenumber p12t p13b">
          <?php if (isset($results)) {?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="white_color">
              <td align="center" class="bg_table boder_right">No</td>
              <td class="bg_table boder_right">Login ID</td>
              <td class="bg_table boder_right">First Name</td>
              <td class="bg_table boder_right">Last Name</td>
              <td class="bg_table boder_right">Account Rep Code</td>
              <td class="bg_table boder_right">Password</td>
              <td class="bg_table boder_right">Enroll Date</td>
            </tr>
            <?php if(count($results)>0) {?>
            <?php $i=1;?>
            <?php foreach($results as $item) {?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td align="center" class="boder_right"><?php echo $i;?></td>
              <td class="boder_right"><a href="<?php echo site_url('sub-store/profile/'.$item->agentID);?>"><strong><?php echo $item->loginID;?></strong></a></td>
              <td class="boder_right"><?php echo $item->firstName;?></td>
              <td class="boder_right"><?php echo $item->lastName;?></td>
              <td class="boder_right"><?php echo substr($item->securityCode, -7);?></td>
              <td class="boder_right"><?php echo $item->password;?></td>
              <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>              
            </tr>
            <?php $i++;?>
            <?php }?>
            <?php } else {?>
            <tr>
              <td colspan="9"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </table>
          <?php echo isset($paging)?$paging:'';?>
          <?php }?>
      </div>
      </div>
    </div>
<script>
function paging(num) {
	$('#excel').val(0);
	$('input[name=page]').val(num);
	$('#account_dist_manager').submit();
}
function rep_search(key) {
	$('#excel').val(0);
	value = $('#'+key).val();
	if ($.trim(value) != '') {
		$('#search_key').val(key);
		$('#search_value').val(value);
		$('input[name=page]').val('');
		$('#account_dist_manager').submit();
	} else {
		alert('Please input search key!');
	}
}
function disableRep(id) {	
	$.ajax({
		url: '<?php echo site_url('distributor/change-distributor-status');?>',
		type: 'POST',
		cache: false,
		data:{id:id},
		dataType: "json",
		success: function(data) {
			alert(data.text);
			if (data.success) {
				window.location.reload();
			}
		},
		error: function () {
			alert('Please try again!');
		}
	});
	
}
</script>
<?php include '_footer.php';?>
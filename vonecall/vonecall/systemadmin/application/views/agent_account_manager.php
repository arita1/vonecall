<?php include '_header.php';?>
<div id="main" class="mh730">
      <div class="bg_title">Store Management</div>
      <div class="p20l p20r p13b">
        
        <div class="form_addcustomer p15t">
          <div class="cb p10b"></div>
          
          <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
         
          <?php echo form_open_multipart(site_url('store-manager'), array('id'=>'store_account_manager', 'name'=>'store_account_manager'));?>
          <input type="hidden" id="search_key" name="search_key" value="<?php echo isset($search_key)?$search_key:'';?>"/>
          <input type="hidden" id="search_value" name="search_value" value="<?php echo isset($search_value)?$search_value:'';?>"/>
          <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
          <input type="hidden" id="excel" name="excel" value="0"/>
          <?php echo form_close();?>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Search by Store Code</td>
              <td>Search by Store Login ID</td>
              <td>Search by Store Last Name</td>
              <td>Search by Store Email</td>
            </tr>
            <tr>
              <td><input id="search_by_agent_code" value="<?php echo isset($search_by_agent_code)?$search_by_agent_code:'';?>" class="w208" type="text"/></td>
              <td><input id="search_by_agent_login_id" value="<?php echo isset($search_by_agent_login_id)?$search_by_agent_login_id:'';?>" class="w208" type="text"/></td>
              <td><input id="search_by_agent_lastName" value="<?php echo isset($search_by_agent_lastName)?$search_by_agent_lastName:'';?>" class="w208" type="text"/></td>
              <td><input id="search_by_agent_email" value="<?php echo isset($search_by_agent_email)?$search_by_agent_email:'';?>" class="w208" type="text"/></td>
            </tr>
            <tr>
              <td><a href="javascript:void(0);" onclick="agent_search('search_by_agent_code');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
              <td><a href="javascript:void(0);" onclick="agent_search('search_by_agent_login_id');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
              <td><a href="javascript:void(0);" onclick="agent_search('search_by_agent_lastName');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
              <td><a href="javascript:void(0);" onclick="agent_search('search_by_agent_email');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
            </tr>
          </table>
        </div>
        
        <p class="p10 bo_t_d m10t">
          <input type="hidden" id="search_by_all" value="1"/>
          <a class="bt_atatement_address" onclick="agent_search('search_by_all');">View All Stores</a>
          <a class="bt_atatement_address" href="<?php echo site_url('add-new-store');?>" >Add New Stores</a>
        </p>
        <div class="box_phonenumber p12t p13b">
          <?php if (isset($results)) {?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="white_color">
              <td align="center" class="bg_table boder_right">No</td>
              <td align="center" class="bg_table boder_right">Store ID</td>
              <td align="center" class="bg_table boder_right">Login ID</td>
              <td align="center" class="bg_table boder_right">First Name</td>
              <td align="center" class="bg_table boder_right">Last Name</td>
              <td align="center" class="bg_table boder_right">Store Code</td>
              <td align="center" class="bg_table boder_right">Password</td>
              <td align="center" class="bg_table boder_right">Enroll Date</td>
              <td align="center" class="bg_table boder_right">Enabled</td>
              <td align="center" class="bg_table" colspan="2" width="150">Action</td>
            </tr>
            
            <?php if(count($results)>0) {?>
            <?php $i=1;?>
            <?php foreach($results as $item) {?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td class="boder_right" align="center"><?php echo $i;?></td>
              <td class="boder_right"><a href="<?php echo site_url('store/profile/'.$item->agentID);?>"><strong><?php echo $item->agentID;?></strong></a></td>
              <td class="boder_right"><a href="<?php echo site_url('store/profile/'.$item->agentID);?>"><strong><?php echo $item->loginID;?></strong></a></td>
              <td class="boder_right"><?php echo $item->firstName;?></td>
              <td class="boder_right"><?php echo $item->lastName;?></td>
              <td class="boder_right"><?php echo substr($item->securityCode, -7);?></td>
              <td class="boder_right"><?php echo $item->password;?></td>
              <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
              <td class="boder_right" align="center"><?php echo $item->enabled?'Yes':'No';?></td>
              <td align="center" width="75">
              	<a class="bt_delete" onclick="deleteAgent(<?php echo $item->agentID;?>);">Delete</a>
              </td>
              <td align="center" width="75">
              	<?php if($this->session->userdata('usertype')==('super-admin')) {?>
              	<a class="bt_delete" onclick="redirect_store('<?php echo $item->loginID?>', '<?php echo $item->password?> ')">Account</a>
              	<?php }?>
              </td>
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
	$('#store_account_manager').submit();
}
function agent_search(key) {
	$('#excel').val(0);
	value = $('#'+key).val();
	if ($.trim(value) != '') {
		$('#search_key').val(key);
		$('#search_value').val(value);
		$('input[name=page]').val('');
		$('#store_account_manager').submit();
	} else {
		alert('Please input search key!');
	}
}
function deleteAgent(id) {
	if (confirm("Are you sure want to delete this store?")) {
		$.ajax({
			url: '<?php echo site_url('store-delete');?>',
			type: 'POST',
			cache: false,
			data:{id:id},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert("You have been delete store successfully!");
					window.location.reload();
				} else {
					alert('Please try again!');
				}
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}
function updateAgentEnabled(id, enabled) {
	var msg = "Are you sure want to disable this store?";
	var msg_success = "You have been disable store successfully!";
	if (enabled) {
		msg = "Are you sure want to enable this store?";
		msg_success = "You have been enable store successfully!";
	}
	if (confirm(msg)) {
		$.ajax({
			url: '<?php echo site_url('store-update-enabled');?>',
			type: 'POST',
			cache: false,
			data:{id:id, enabled:enabled},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert(msg_success);
					window.location.reload();
				} else {
					alert('Please try again!');
				}
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
}

// Function for redirect to store ======
function redirect_store(userName, password){
	var distUrl = '<?php echo $this->config->item('sms_url')?>/login?username='+userName+'&password='+password;
	window.location.href = distUrl;
}
</script>
<?php include '_footer.php';?>
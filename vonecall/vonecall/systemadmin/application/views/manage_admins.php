<?php include '_header.php';?>
<div id="main" class="mh730">
  <div class="bg_title">Manage Admin</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p15t">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Search by Admin Login ID:</td>
          <td>Search by Admin Last Name:</td>
        </tr>
        <tr>
          <td><input id="search_by_employee_login_id" value="<?php echo isset($search_by_employee_login_id)?$search_by_employee_login_id:'';?>" class="w162" type="text"/></td>
          <td><input id="search_by_employee_lastName" value="<?php echo isset($search_by_employee_lastName)?$search_by_employee_lastName:'';?>" class="w162" type="text"/></td>
        </tr>
        <tr>
          <td><a href="javascript:void(0);" onclick="employee_search('search_by_employee_login_id');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
          <td><a href="javascript:void(0);" onclick="employee_search('search_by_employee_lastName');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
        </tr>
      </table>
    </div>
    <p class="p10 bo_t_d m10t">
      <input type="hidden" id="search_by_all" value="1"/>
      <a class="bt_atatement_address" onclick="employee_search('search_by_all');">View All Admins</a>
      
      <?php if($this->session->userdata('admin_upload'))?>
      	<a class="bt_atatement_address" href="<?php echo site_urL('add-new-admin');?>" >Add New Admin</a>
    </p>
    <?php if(isset($results)) {?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td width="50" align="center" class="bg_table boder_right">No</td>
          <td align="center" class="bg_table boder_right">Login ID</td>
          <td align="center" class="bg_table boder_right">First Name</td>
          <td align="center" class="bg_table boder_right">Last Name</td>
          <td align="center" class="bg_table boder_right">Password</td>
          <td align="center" class="bg_table boder_right">Created Date</td>
          <td colspan="2" width="100" align="center" class="bg_table">Action</td>
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;?>
        <?php foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="center" class="boder_right"><?php echo $i;?></td>
          <td class="boder_right"> <a href="<?php echo site_urL('admin-profile/'.$item->adminID);?>"> <strong> <?php echo $item->username;?> </strong> </a> </td>
          <td class="boder_right"><?php echo $item->firstName;?></td>
          <td class="boder_right"><?php echo $item->lastName;?></td>
          <td class="boder_right"><?php echo $item->password;?></td>
          <td class="boder_right"><?php echo date('m/d/Y H:i:s A', strtotime($item->createdDate));?></td>
          <td align="center"><a class="bt_delete" onclick="changeStatus(<?php echo $item->adminID;?>);"><?php echo ($item->adminStatus) ? 'Disable' : 'Enable'?></a></td>
          <td align="center"><a class="bt_delete" onclick="deleteEmployee(<?php echo $item->adminID;?>);">Delete</a></td>          
        </tr>
        <?php $i++;?>
        <?php }?> 
        <?php } else {?>
        <tr>
          <td colspan="7"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
      <?php echo isset($paging)?$paging:'';?>             
    </div>
    <?php }?>
      <div class="form_addcustomer p15t">
	    <?php echo form_open_multipart(site_url('admin-manager'), array('id'=>'manage_admins', 'name'=>'manage_admins'));?>
	    <input type="hidden" id="search_key" name="search_key" value="<?php echo isset($search_key)?$search_key:'';?>"/>
	    <input type="hidden" id="search_value" name="search_value" value="<?php echo isset($search_value)?$search_value:'';?>"/>
	    <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
	    <input type="hidden" id="excel" name="excel" value="0"/>
	    <input type="hidden" id="submit_type" name="submit_type" value="0"/>        
	    <?php echo form_close();?>
	  </div> 
  </div>
</div>
<script>
function paging(num) {
	$('#excel').val(0);
	$('#submit_type').val(0);
	$('input[name=page]').val(num);
	$('#manage_employee').submit();
}
function employee_search(key) {
	$('#excel').val(0);
	$('#submit_type').val(0);
	value = $('#'+key).val();
	if ($.trim(value) != '') {
		$('#search_key').val(key);
		$('#search_value').val(value);
		$('input[name=page]').val('');
		$('#manage_admins').submit();
	} else {
		alert('Please input search key!');
	}
}
function deleteEmployee(id) {
	if (confirm("Are you sure want to delete this employee?")) {
		$.ajax({
			url: '<?php echo site_url('delete-admin');?>',
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
	} else {
		return false;
	}
}

function changeStatus(id) {
	$.ajax({
		url: '<?php echo site_url('change-admin-status');?>',
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
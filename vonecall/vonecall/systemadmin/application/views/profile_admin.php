<?php include '_header.php';?>
<style>	
.permission_checkbox {   
    margin-left: 16%;
    width: 100%;
}
.admin_permissions{
	display: none;
}
</style>
<div id="main" class="mh730">
  <div class="bg_title">Manage Admin</div>
  <div class="p20l p20r p13b">
    
    
    <div class="box_phonenumber p12t p13b">     
      
      <div class="bg_title_content">Add New Admin</div>
      <div class="form_addcustomer p15t">
        <?php echo form_open_multipart(site_url('admin-profile/'.$id), array('id'=>'update_admin', 'name'=>'update_admin'));?>
        <input type="hidden" name="id" value="<?php echo isset($id)?$id:''?>" />
        <label>Admin Type:</label>
        <?php echo form_dropdown('adminType', $option_admin_type, (isset($adminType)?$adminType:''), 'class="w327" id="adminType"');?>
        <?php if (isset($error['adminType'])) {?><span class="red_color"><?php echo $error['adminType'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>First Name:</label>
        <input name="firstName" value="<?php echo isset($firstName)?$firstName:'';?>" class="w325" type="text" />
        <?php if (isset($error['firstName'])) {?><span class="red_color"><?php echo $error['firstName'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Last Name:</label>
        <input name="lastName" value="<?php echo isset($lastName)?$lastName:'';?>" class="w325" type="text" />
        <?php if (isset($error['lastName'])) {?><span class="red_color"><?php echo $error['lastName'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Phone:</label>
        <input name="phone" value="<?php echo isset($phone)?$phone:'';?>" class="w325" type="text" />
        <?php if (isset($error['phone'])) {?><span class="red_color"><?php echo $error['phone'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Cell Phone:</label>
        <input name="cell_phone" value="<?php echo isset($cell_phone)?$cell_phone:'';?>" class="w325" type="text" />
        <?php if (isset($error['cell_phone'])) {?><span class="red_color"><?php echo $error['cell_phone'];?></span><?php }?>
        <div class="cb"></div>
       
       	<label>Email:</label>
        <input name="email" value="<?php echo isset($email)?$email:'';?>" class="w325" type="text" />
        <?php if (isset($error['email'])) {?><span class="red_color"><?php echo $error['email'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Login Name:</label>
        <input name="username" value="<?php echo isset($username)?$username:'';?>" class="w325" type="text" />
        <?php if (isset($error['username'])) {?><span class="red_color"><?php echo $error['username'];?></span><?php }?>
        <div class="cb"></div>
       
        <label>Password:</label>
        <input name="password" placeholder="<?php echo isset($password)?$password:'';?>" value="" class="w325" type="text" />
        <?php if (isset($error['password'])) {?><span class="red_color"><?php echo $error['password'];?></span><?php }?>
        <div class="cb"></div>
        
        <div class="admin_permissions">
	        <label>Admin Permissions:</label>
	        <div class="permission_checkbox"> <input type="checkbox" name="create_admin" value="1" /> <label style="width: 175px;"> Create Other Admins </label> </div>
		    <div class="permission_checkbox"> <input type="checkbox" name="manage_admin" value="1" /> <label style="width: 175px;"> Manage - Users - Admin </label> </div>        
	        <div class="permission_checkbox"> <input type="checkbox" name="manage_distributor" value="1" /> <label style="width: 175px;"> Manage - Users - Distributors </label> </div>
		    <div class="permission_checkbox"> <input type="checkbox" name="manage_store" value="1" /> <label style="width: 175px;"> Manage - Users - Stores </label> </div>
	        <div class="permission_checkbox"> <input type="checkbox" name="manage_product" value="1" /> <label style="width: 175px;"> Manage - Products </label> </div>
		    <div class="permission_checkbox"> <input type="checkbox" name="payment" value="1" /> <label style="width: 175px;"> Payment </label> </div>
	        <div class="permission_checkbox"> <input type="checkbox" name="reports" value="1" /> <label style="width: 175px;"> Reports </label> </div>
		    <div class="permission_checkbox"> <input type="checkbox" name="admin_password" value="1" /> <label style="width: 175px;"> Admin - Change Password </label> </div>
	        <div class="permission_checkbox"> <input type="checkbox" name="admin_log" value="1" /> <label style="width: 175px;"> Admin - Admin Logs </label> </div>
		    <div class="permission_checkbox"> <input type="checkbox" name="admin_upload" value="1" /> <label style="width: 175px;"> Admin - Uploads </label> </div>
	    </div>
	    
        <label>&nbsp;</label>
        <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#submit_type').val(1);$('#update_admin').submit();">Submit</a>
        <div class="cb"></div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('#adminType').on('change', function() {
      if ( this.value == '2') {
        $(".admin_permissions").show();
      } else {
        $(".admin_permissions").hide();
      }
    });
}); 

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
		$('#manage_employee').submit();
	} else {
		alert('Please input search key!');
	}
}
function deleteEmployee(id) {
	if (confirm("Are you sure want to delete this employee?")) {
		$.ajax({
			url: '<?php echo site_url('delete-employee');?>',
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

</script>
<?php include '_footer.php';?>
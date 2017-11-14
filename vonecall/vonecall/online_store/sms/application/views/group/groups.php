<div class="bg_title_content">All Groups</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('all-groups'), array('id'=>'group_form', 'name'=>'group_form'))?>
  <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
      <td colSpan="2"><?php echo $this->lang->line('fields_denoted');?></td>
    </tr>    
    <tr>
      <td>Group Name <span class="red_color">*</span></td>
      <td>
        <input type="text" name="group_name" value="<?php echo isset($group_name)?$group_name:'';?>" class="w325" />
        <?php if (isset($error['group_name'])) {?><span class="red_color"><?php echo $error['group_name'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>Group Description</td>
      <td>
        <input type="text" name="group_description" value="<?php echo isset($group_description)?$group_description:'';?>" class="w325" />
        <?php if (isset($error['group_description'])) {?><span class="red_color"><?php echo $error['group_description'];?></span><?php }?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#group_form').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>
<div class="box_phonenumber p12t p13b">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td class="bg_table boder_right">Group Name</td>
      <td class="bg_table boder_right">Group Description</td>
      <td class="bg_table boder_right">Contacts</td>     
      <td class="bg_table" colspan="2" width="200" align="center">Action</td>
    </tr>
    <?php if(count($allGroups)>0) { ?>
    <?php $i=1;?>
    <?php foreach($allGroups as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td class="boder_right"><?php echo $item->groupName;?></td>
      <td class="boder_right"><?php echo $item->groupDescription;?></td>
      <td class="boder_right"> <a href="<?php echo site_url('group-contacts/'.$item->groupID)?>"> <img alt="Group Contacts" src="<?php echo $this->config->item('base_url')?>public/images/contacts-icon.gif"> </a> </td>      
      <td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="updategroup(<?php echo $item->groupID;?>, '<?php echo $item->groupName;?>', '<?php echo $item->groupDescription;?>');">Edit</a></td>
      <td width="100" align="center"><a class="bt_delete" onclick="deleteGroup(<?php echo $item->groupID;?>);">Delete</a></td>
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="7"><?php echo $this->lang->line('empty_results');?></td>
    </tr>
    <?php }?>
  </table>
</div>

<script type="text/javascript">
function deleteGroup(id) {
	if (confirm("Are you sure want to delete this Group?")) {
		$.ajax({
			url: '<?php echo site_url('delete-group');?>',
			type: 'POST',
			cache: false,
			data:{id:id},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					window.location.reload();
				} else {
					alert(data.text);
				}
			},
			error: function (){
				alert('Please try again!');
			}
		});
	} else {
		return false;
	}
	
	return;
}
function updategroup(id, groupName, groupDescription) {
	$('html, body').animate({ scrollTop: 0 }, 'slow');
	$("input[name=edit]").val(id);	
	$("input[name=group_name]").val(groupName);	
	$("input[name=group_description]").val(groupDescription);
}

</script>
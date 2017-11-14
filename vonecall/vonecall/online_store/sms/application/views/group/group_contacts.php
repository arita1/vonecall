<div class="bg_title_content"><?php echo $groupDetails->groupName;?> Contacts</div>

<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('delete-group-contacts/'.$groupDetails->groupID), array('id'=>'delete_group_contact', 'name'=>'delete_group_contact'))?>
  
  <div class="box_phonenumber p12t p13b">
	  <table width="30%" border="0" cellspacing="0" cellpadding="0">
	    <tr class="white_color">
	      <td class="bg_table boder_right"></td>
	      <td class="bg_table boder_right">Contacts</td>     
	    </tr>
	    <?php if(count($groupContats)>0) { ?>
	    <?php $i=1;?>
	    <?php foreach($groupContats as $item) {?>
	    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	      <td class="boder_right" align="center"> <input type="checkbox" name="contactID[]" value="<?php echo $item->contactID;?>" /> </td>
	      <td><?php echo $item->contactNumber;?></td>
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
 	 <table width="30%" border="0" cellspacing="0" cellpadding="0">
	  	<tr>
	      <td style="width: 75px;">For Selected:</td>
	      <td align="left"><a class="contact_delete" href="javascript:void(0);" onclick="$('#delete_group_contact').submit();">Delete</a></td>
	    </tr>
	  </table>
  <?php echo form_close();?>
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
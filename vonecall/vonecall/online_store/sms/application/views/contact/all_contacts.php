<div class="bg_title_content">All Contacts</div>
<?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
<?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
<div class="box_phonenumber p12t p13b">
  <div style="padding: 1%">
    <label> Contact Filter By: </label>
    <?php echo form_dropdown('group', $option_groups, (isset($group)?$group:''), 'class="" id="group"');?> 
  </div>
  <div id="ContactList">	
	  <table width="50%" border="0" cellspacing="0" cellpadding="0">
	    <tr class="white_color">
	      <td class="bg_table boder_right">Group Name</td>
	      <td class="bg_table boder_right">Contact Number</td>           
	      <td class="bg_table" colspan="2" width="200" align="center">Action</td>
	    </tr>
	    <?php if(count($allContacts)>0) { ?>
	    <?php $i=1;?>
	    <?php foreach($allContacts as $item) {?>
	    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
	      <td class="boder_right"><?php echo $item->groupName;?></td>
	      <td class="boder_right"><?php echo $item->contactNumber;?></td>
	      <td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="editContact(<?php echo $item->contactID;?>)">Edit</a></td>
	      <td width="100" align="center"><a class="bt_delete" onclick="deleteContact(<?php echo $item->contactID;?>);">Delete</a></td>
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
</div>

<script type="text/javascript">
function deleteContact(id) {
	if (confirm("Are you sure want to delete this Contact?")) {
		$.ajax({
			url: '<?php echo site_url('delete-contact');?>',
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
function editContact(id){
	window.location.href = '<?php echo site_url('update-contact')?>/'+id;
}
function updategroup(id, groupName, groupDescription) {
	$('html, body').animate({ scrollTop: 0 }, 'slow');
	$("input[name=edit]").val(id);	
	$("input[name=group_name]").val(groupName);	
	$("input[name=group_description]").val(groupDescription);
}


$(document).ready(function(){
    $('#group').on('change', function() {
       var groupID = $(this).val();
		$.getJSON(
				"<?php echo site_url('group/filterContactsByGroups');?>",
				{
					groupID:groupID		
				},
				function(data){ 
					var html_content = '';
					if (data) {						
						html_content +='<table width="50%" border="0" cellspacing="0" cellpadding="0">';
							html_content +='<tr class="white_color">';
					          html_content +='<td class="bg_table boder_right">Group Name</td>';
					          html_content +='<td class="bg_table boder_right">Contact Number</td>';
					          html_content +='<td class="bg_table" colspan="2" width="200" align="center">Action</td>';					          
					        html_content +='</tr>'; 
					        var j=0;
						$.each(data, function(i, item) {
							if(j%2==1){
								var trClass = 'bg_light_blu';
							}else{
								var trClass = '';
							}
							
							html_content +='<tr class="'+trClass+'" >';
					          html_content +='<td class="boder_right">'+item.groupName+'</td>';
					          html_content +='<td class="boder_right">'+item.contactNumber+'</td>';					          
					          html_content +='<td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="editContact('+item.contactID+')">Edit</a></td>';
					          html_content +='<td width="100" align="center"><a class="bt_delete" onclick="deleteContact('+item.contactID+');">Delete</a></td> ';
					        html_content +='</tr>';	
					      j++;						
						});
						html_content +='</table>';					
					} else{						
				        html_content +='<table width="50%" border="0" cellspacing="0" cellpadding="0">';
							html_content +='<tr class="white_color">';
					          html_content +='<td class="bg_table boder_right">Group Name</td>';
					          html_content +='<td class="bg_table boder_right">Contact Number</td>';
					          html_content +='<td class="bg_table" colspan="2" width="200" align="center">Action</td>';					          
					        html_content +='</tr>'; 
							html_content +='<tr>';
					          html_content +='<td colspan="5"><?php echo $this->lang->line('empty_results');?></td>';
					        html_content +='</tr>';
						html_content +='</table>';
					}
					$("#ContactList").html(html_content);
					
		}); // $.getJson end
    });
});

</script>
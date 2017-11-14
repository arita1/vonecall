<div class="bg_title_content">Get Speed dial</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/get-speed-dial'), array('id'=>'form_get_speed_dial', 'name'=>'form_get_speed_dial'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Account ID</td>
		<td>
			<?php echo form_dropdown('id', $accountList, (isset($id)?$id:''), 'class="w327" id="id"');?>
			<?php if (isset($error['id'])) {?><span class="red_color"><?php echo $error['id'];?></span><?php }?>
		</td>
	</tr>
	
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_get_speed_dial').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($speed_dials)) {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">Dial ID</td>
      <td class="bg_table boder_right">Contact Name</td>
      <td class="bg_table boder_right">Contact Number</td>
      <td class="bg_table boder_right">Contact Type</td>
      <td class="bg_table boder_right">Action</td>      
    </tr>
    <?php if(count($speed_dials)>0) {?>
    <?php $i=1;?>
    <?php foreach($speed_dials as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $item->dial_id;?> </td>
      <td class="boder_right"> <?php echo $item->name;?> </td>
      <td class="boder_right"> <?php echo $item->phone_number;?> </td>
      <td class="boder_right"> <?php echo $item->phone_type;?> </td> 
      <td class="boder_right"> <a class="bt_delete" onclick="deleteSpeeddial(<?php echo $item->i_account_phonebook;?>);">Delete</a> </td>   
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="8">Record Not Found</td>
    </tr>
    <?php }?>
  </table>
<?php }?>

<script>
function deleteSpeeddial(id) {
	if (confirm("Are you sure want to delete this Speed dial?")) {
		$.ajax({
			url: '<?php echo site_url('portaone/speed-dial-delete');?>',
			type: 'POST',
			cache: false,
			data:{id:id},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					alert("You have been delete speed dial successfully!");
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
</script>

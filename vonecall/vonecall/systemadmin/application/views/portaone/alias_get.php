<div class="bg_title_content">Get Alias</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/get-alias'), array('id'=>'form_get_alias', 'name'=>'form_get_alias'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
    <tr>
		<td>Parent Account ID</td>
		<td>
			<?php echo form_dropdown('parentAccount', $accountList, (isset($parentAccount)?$parentAccount:''), 'class="w327" id="id"');?>
			<?php if (isset($error['parentAccount'])) {?><span class="red_color"><?php echo $error['parentAccount'];?></span><?php }?>
		</td>
	</tr>	  
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_get_alias').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($alias_list)) {?>
  <table width="40%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">No</td>
      <td align="center" class="bg_table boder_right">Alias ID</td>      
      <td align="center" class="bg_table">Action</td>      
    </tr>
    <?php if(count($alias_list)>0) {?>
    <?php $i=1;?>
    <?php foreach($alias_list as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $i;?> </td>
      <td align="center" class="boder_right"> <?php echo $item->id;?> </td>      
      <td align="center" > <a class="bt_delete" onclick="deleteAlias(<?php echo $item->i_account;?>, <?php echo isset($parentAccount)?$parentAccount:''?>, <?php echo $item->id?>);">Delete</a> </td>     
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
function deleteAlias(alias_account, parentAccount, alias_id) {
	if (confirm("Are you sure want to delete this record?")) {
		$.ajax({
			url: '<?php echo site_url('portaone/delete-alias');?>',
			type: 'POST',
			cache: false,
			data:{id:alias_account, parentAccount:parentAccount, aliasID: alias_id},
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

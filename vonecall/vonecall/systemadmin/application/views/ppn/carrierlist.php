<div class="bg_title_content">Carrier List</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
</div>

<div class="box_phonenumber p12t p13b">
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">ID</td>
      <td class="bg_table boder_right">Carrier ID</td>
      <td class="bg_table boder_right">Carrier Name</td>
      <td class="bg_table boder_right">Category</td>
    </tr>
    <?php if(count($carrierList)>0) {?>
    <?php $i=1;?>
    <?php foreach($carrierList as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $i?> </td>
      <td class="boder_right"> <?php echo $item->carrierId;?> </td>
      <td class="boder_right"> <?php echo $item->carrierName;?> </td>
      <td class="boder_right"> <?php echo $item->category;?> </td> 
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="8">Record Not Found</td>
    </tr>
    <?php }?>
  </table>


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

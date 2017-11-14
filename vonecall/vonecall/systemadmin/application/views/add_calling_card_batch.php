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
<script>    		
	// Check Uploaded Image
	function showimagepreview(input) { 
		if (input.files && input.files[0]) {
			var filerdr = new FileReader();
			filerdr.onload = function(e) {
				$('#imgprvw').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
	}
</script>
<div id="main" class="mh730">
  <div class="bg_title">Calling Card Batch</div>
  <div class="p20l p20r p13b">   
    
    <div class="box_phonenumber p12t p13b">     
      
      <div class="bg_title_content">Add New Batch</div>
      <div class="form_addcustomer p15t">
        <?php echo form_open_multipart(site_url('card-batch'), array('id'=>'add_new_batch', 'name'=>'add_new_batch'));?>
        <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
        <label>Batch Name:</label>
        <input name="batchName" value="<?php echo isset($batchName)?$batchName:'';?>" class="w325" type="text" />
        <?php if (isset($error['batchName'])) {?><span class="red_color"><?php echo $error['batchName'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Batch Amount:</label>
        <input name="batchAmount" value="<?php echo isset($batchAmount)?$batchAmount:'';?>" class="w325" type="text" />
        <?php if (isset($error['batchAmount'])) {?><span class="red_color"><?php echo $error['batchAmount'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Batch Card:</label>
        <input name="batchCardImage" class="w325" type="file" onchange="showimagepreview(this)" />
        <div class="cb"></div>
        <label> &nbsp;</label>
        <img id="imgprvw" class="help-block" alt="uploaded image preview" width="190" style="padding: 5px 0;"/>
        <?php if (isset($error['batchCardImage'])) {?><span class="red_color"><?php echo $error['batchCardImage'];?></span><?php }?>
        <div class="cb"></div><div class="cb"></div>
        	    
        <label>&nbsp;</label>
        <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#submit_type').val(1);$('#add_new_batch').submit();">Submit</a>
        <div class="cb"></div>
        <?php echo form_close();?>
      </div>
      
      <div class="box_phonenumber p12t p13b" id="tableGrid">
      <table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Batch Name </td>
          <td align="center" class="bg_table boder_right"> Batch Amount </td>
          <td align="center" class="bg_table boder_right"> Batch Card Image </td>
          <td colspan="2" align="center" class="bg_table"> Action</td>
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->batchName;?></td>
          <td class="boder_right">$<?php echo $item->batchAmount;?></td>
          <td class="boder_right"><img src="<?php echo $this->config->item('base_url')?>public/uploads/calling_cards/<?php echo $item->batchCardImage;?>" class="help-block" alt="<?php echo $item->batchCardImage?$item->batchName:'NA';?>" width="190" style="padding: 5px 0;" /></td>
          <td width="100" align="center" class="boder_right">
          	<a class="bt_edit" onclick="updateBatch( <?php echo $item->batchID;?>, '<?php echo $item->batchName;?>', '<?php echo $item->batchAmount;?>', '<?php echo $item->batchCardImage;?>' )">Edit</a></td>
          <td width="100" align="center"><a class="bt_delete" onclick="deleteBatch(<?php echo $item->batchID;?>);">Delete</a></td>
        </tr>
        <?php  $i++;}?>
        <?php } else {?>
        <tr>
          <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
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

function updateBatch(batchID, batchName, batchAmount, imageSrc) {
	$("input[name=edit]").val(batchID);
	$("input[name=batchName]").val(batchName);
	$("input[name=batchAmount]").val(batchAmount);
	$('#imgprvw').attr('src', '<?php echo $this->config->item('base_url')?>public/uploads/calling_cards/'+imageSrc);
}

function deleteBatch(id) {
	if (confirm("Are you sure want to delete this batch?")) {
		$.ajax({
			url: '<?php echo site_url('delete-card-batch');?>',
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

<style>
	.active{
		background-color: #6A7F85;
	}
</style>

<div class="bg_title_content">Distributor Commission</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
</div>

<div class="bg_title_content">Commission Table <?php //echo $agentID?></div>
  
<div class="box_phonenumber p12t p13b">
	
	<table width="" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
  	<tr class="">
  	  <td class="bg_title_content"> Reset Commission:</td>	
      <td align="center"><a class="bt_save" href="javascript:void(0);" onclick="resetCommission('<?php echo $agentID;?>')">Reset</a></td>      
    </tr>
  </table>
  
  <table width="" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
  	<tr class="">
      <td align="center" class="boder_right <?php echo ($productCat!='rtr' && $productCat!='pin' && $productCat!='calling-card')?'active':''?>"><a class="bt_save" href="javascript:void(0);" onclick="window.location='<?php echo site_url('store/commission/'.$agentID.'/pinless')?>'">Pinless</a></td>
      <td align="center" class="boder_right <?php echo ($productCat=='rtr')?'active':''?>"><a class="bt_save" href="javascript:void(0);" onclick="window.location='<?php echo site_url('store/commission/'.$agentID.'/rtr')?>'">RTR</a></td>
      <td align="center" class="<?php echo ($productCat=='pin')?'active':''?>"><a class="bt_save" href="javascript:void(0);" onclick="window.location='<?php echo site_url('store/commission/'.$agentID.'/pin')?>'">PIN</a></td>      
      <td align="center" class="<?php echo ($productCat=='calling-card')?'active':''?>"><a class="bt_save" href="javascript:void(0);" onclick="window.location='<?php echo site_url('store/commission/'.$agentID.'/calling-card')?>'">Calling Card</a></td>
    </tr>
  </table>
  
  <div class="cb"></div>
  
  <!-- Pagination Form -->
  <?php echo form_open_multipart(site_url('store/commission/'.$agentID.'/'.$productCat), array('id'=>'pagination_form', 'name'=>'pagination_form'))?>
  <input type="hidden" name="page" value="1" />  
  <?php echo form_close();?>  
  <!-- Pagination Form END -->
  
  	
  <?php echo form_open_multipart(site_url('store/commission/'.$agentID.'/'.$productCat), array('id'=>'commission_form', 'name'=>'commission_form'))?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td class="bg_table boder_right">Product Name</td>
      <td class="bg_table boder_right">Product SKU Name</td>
      <td class="bg_table boder_right">Product List</td>
      <td class="bg_table boder_right">Product Type</td>
      <td class="bg_table boder_right">Product Category</td>
      <td class="bg_table boder_right">Total Product Commission % Rate</td>
      <td class="bg_table boder_right">Store Commission % Rate</td>
      <!-- <td class="bg_table" colspan="2" width="200" align="center">Action</td> -->
    </tr>
    <?php if(count($getAllProducts)>0) { 
		?>
    	
    <?php $i=1; $k=0; //echo '<pre>';print_r($getAllProducts);die;?>
    <?php foreach($getAllProducts as $item) {
    	$totalCommission = $item->vproducttotalCommission - $item->vproductAdminCommission;	
		
		for($a=0; $a<count($results); $a++){
	    	if($results[$a]->vproductID == $item->vproductID)
				$k = $a;    	
	    }
    ?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td class="boder_right"><?php echo $item->vproductName;?></td>
      <td class="boder_right"><?php echo $item->vproductSkuName;?></td>
      <td class="boder_right"><?php echo $item->productName;?></td>
      <td class="boder_right"><?php echo $item->vproductType;?></td>
      <td class="boder_right"><?php echo $item->vproductCategory;?></td>
      <td class="boder_right"><?php echo $totalCommission;?>%</td>
      <td class="boder_right">
      	<?php 
      	$max = 0; $min = 0;
      	if($item->vproductMaxStoreCommission && $item->vproductMinStoreCommission){
      		$max = $item->vproductMaxStoreCommission;
			$min = $item->vproductMinStoreCommission;
      	} 
      	?>
      	<select name="storeCommission[<?php echo $item->vproductID?>]">      		
      		<?php for($j=$min; $j<=$max; $j++ ){?>      			
      			<option <?php if(isset($results[$k]) && $results[$k]->commissionRate==$j){ echo 'selected="selected"'; }else{ }?> value="<?php echo $j?>"><?php echo $j?></option>
      		<?php }?>
      	</select>
      	<input type="hidden" name="productList[<?php echo $item->vproductID?>]" value="<?php echo $item->productID?>" />
      </td>
      <!--
      <td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="updateCommission(<?php echo $item->ID;?>, '<?php echo $item->productID;?>', '<?php echo $item->commissionRate;?>', '<?php echo $item->note;?>');">Edit</a></td>
      <td width="100" align="center"><a class="bt_delete" onclick="deleteCommission(<?php echo $item->ID;?>);">Delete</a></td>
      -->
    </tr>
    <?php $i++; $k++;?>
    <?php }?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  	<tr>  		
	  		<td colspan="7" align="right">
	  			<a class="bt_save" href="javascript:void(0);" onclick="$('#commission_form').submit();">Submit</a>
	  			&nbsp;
	  			<a onclick="window.location='<?php echo site_url('store/commission/'.$agentID)?>';" href="javascript:void(0);" class="bt_save">Cancel</a>
	  		</td>
	    </tr>
	</table>
	<?php echo isset($paging)?$paging:'';?>
    <?php } else {?>
    <tr>
      <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
    </tr>
    <?php }?>
  </table>  
  <?php echo form_close();?>
</div>

<script type="text/javascript">

//Pagination 
function paging(num) {
	$('input[name=page]').val(num);
	$('#pagination_form').submit();
}

function deleteCommission(id) {
	if (confirm("Are you sure want to delete this Commission?")) {
		$.ajax({
			url: '<?php echo site_url('store/delete-commission/'.$agentID);?>',
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
function updateCommission(id, productID, commissionRate, note) {
	$.ajax({
		url: '<?php echo site_url('agent/get_max_store_commission/'.$info->parentAgentID);?>/'+productID,
		type: 'POST',
		cache: false,
		data:{},
		success: function(data) {
			$("select[name=commissionRate]").html(data);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$("input[name=edit]").val(id);
			$("select[name=product]").val(productID);
			$("select[name=commissionRate]").val(commissionRate);
			$("input[name=note]").val(note);
			$("select[name=productName]").focus();
		}
	});
	
}
function select_product(value) {
	$.ajax({
		url: '<?php echo site_url('agent/get_max_store_commission/'.$info->parentAgentID);?>/'+value,
		type: 'POST',
		cache: false,
		data:{},
		success: function(data) {
			$("select[name=commissionRate]").html(data);
		}
	});
	
}

// Commission ===============
function resetCommission(id) {
	if (confirm(" Are you sure want to reset store's commission?")) {
		$.ajax({
			url: '<?php echo site_url('store/reset-commission');?>',
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
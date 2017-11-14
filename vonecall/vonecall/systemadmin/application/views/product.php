<?php include '_header.php';?>
<style>
	.box_phonenumber td {
	    padding: 7px 6px;
	}
	.search_by_key{		
	    float: right;
	    margin-right: 190px;
	    width: 40%;	
	}
	.search_by_list{
		float: left;
    	width: 40%;
	}
</style>
<div id="main">
  <div class="bg_title">Products</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">
    <div class="bg_title_content" style="float: left; width: 50%;">Add / Edit Product</div>
    <div class="bg_title_content" style="float: left; width: 50%;"> <!-- Search Product By --> </div>
    <div class="form_addcustomer p15t">
    	<div style="float: left; width: 80%;">
    		<?php echo form_open_multipart(site_url('product'), array('id'=>'product_form', 'name'=>'product_form'));?>
		      <input type="hidden" name="edit" value="<?php echo isset($edit)?$edit:'';?>"/>
		      <table border="0" cellspacing="0" cellpadding="0">
		      	<tr>
			      <td>Prepay Nation Product ID:</td>
			      <td><input name="ppnProductID" value="<?php echo isset($ppnProductID)?$ppnProductID:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['ppnProductID'])) {?><span class="red_color"><?php echo $error['ppnProductID'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>SKU ID (If Product Type is TopUp):</td>
			      <td><input name="sku" value="<?php echo isset($sku)?$sku:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['sku'])) {?><span class="red_color"><?php echo $error['sku'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>SKU Name:</td>
			      <td><input name="skuName" value="<?php echo isset($skuName)?$skuName:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['skuName'])) {?><span class="red_color"><?php echo $error['skuName'];?></span><?php }?></td>
			    </tr>
				<tr>
			      <td>Product Name:</td>
			      <td><input name="productName" value="<?php echo isset($productName)?$productName:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productName'])) {?><span class="red_color"><?php echo $error['productName'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product List:</td>
			      <td><?php echo form_dropdown('productList', $product_lists, (isset($productList)?$productList:''), 'class="w162" id="productType"');?></td>
			      <td><?php if (isset($error['productList'])) {?><span class="red_color"><?php echo $error['productList'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Type :</td>
			      <td><?php echo form_dropdown('productType', $product_types, (isset($productType)?$productType:''), 'class="w162" id="productType"');?></td> 
			      <td><?php if (isset($error['productType'])) {?><span class="red_color"><?php echo $error['productType'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Category :</td>
			      <td><input name="productCategory" value="<?php echo isset($productCategory)?$productCategory:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productCategory'])) {?><span class="red_color"><?php echo $error['productCategory'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Vendor :</td>
			      <td><input name="productVender" value="<?php echo isset($productVender)?$productVender:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productVender'])) {?><span class="red_color"><?php echo $error['productVender'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Face Value :</td>
			      <td><input name="faceValue" value="<?php echo isset($faceValue)?$faceValue:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['faceValue'])) {?><span class="red_color"><?php echo $error['faceValue'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Minimum Amount :</td>
			      <td><input name="productMinAmount" value="<?php echo isset($productMinAmount)?$productMinAmount:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productMinAmount'])) {?><span class="red_color"><?php echo $error['productMinAmount'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Product Maximum Amount :</td>
			      <td><input name="productMaxAmount" value="<?php echo isset($productMaxAmount)?$productMaxAmount:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productMaxAmount'])) {?><span class="red_color"><?php echo $error['productMaxAmount'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Local Number Length :</td>
			      <td><input name="localNumberLength" value="<?php echo isset($localNumberLength)?$localNumberLength:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['localNumberLength'])) {?><span class="red_color"><?php echo $error['localNumberLength'];?></span><?php }?></td>
			    </tr>			   
			    <tr>
			      <td>Total Commissions (%) :</td>
			      <td><input name="productCommission" value="<?php echo isset($productCommission)?$productCommission:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['productCommission'])) {?><span class="red_color"><?php echo $error['productCommission'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Admin Commissions (%) :</td>
			      <td><input name="adminCommission" value="<?php echo isset($adminCommission)?$adminCommission:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['adminCommission'])) {?><span class="red_color"><?php echo $error['adminCommission'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Distributor Commissions (%) :</td>
			      <td><input name="distributorCommission" value="<?php echo isset($distributorCommission)?$distributorCommission:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['distributorCommission'])) {?><span class="red_color"><?php echo $error['distributorCommission'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Max Store Commissions (%) :</td>
			      <td><input name="storeMaxCommission" value="<?php echo isset($storeMaxCommission)?$storeMaxCommission:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['storeMaxCommission'])) {?><span class="red_color"><?php echo $error['storeMaxCommission'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Min Store Commissions (%) :</td>
			      <td><input name="storeMinCommission" value="<?php echo isset($storeMinCommission)?$storeMinCommission:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['storeMinCommission'])) {?><span class="red_color"><?php echo $error['storeMinCommission'];?></span><?php }?></td>
			    </tr>
			    
			    <tr>
			      <td>Country (Required! If Product Type is TopUp):</td>
			      <td><?php echo form_dropdown('countryList', $optionCountry, (isset($countryList)?$countryList:''), 'class="w162" id="productType"');?></td>
			      <td><?php if (isset($error['countryList'])) {?><span class="red_color"><?php echo $error['countryList'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>Note:</td>
			      <td><input name="note" value="<?php echo isset($note)?$note:'';?>" type="text" class="w162"/></td>
			      <td><?php if (isset($error['note'])) {?><span class="red_color"><?php echo $error['note'];?></span><?php }?></td>
			    </tr>
			    <tr>
			      <td>&nbsp;</td>
			      <td>
			        <a class="bt_check" href="javascript:void(0);" onclick="$('#product_form').submit();">Submit</a>
			        <a class="bt_check" href="javascript:void(0);" onclick="window.location='<?php echo site_url('product');?>';">Cancel</a>
			      </td>
			      <td></td>
			    </tr>
			  </table>
		      <?php echo form_close();?>
    	</div>
    	
    	<div style="float: right; width: 20%;"> 
    	  <!--	    		   	  
	      <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td>Product List:</td>
		      <td><?php echo form_dropdown('productTypeSearch', $product_lists, (isset($productTypeSearch)?$productTypeSearch:''), 'class="" id="productType"');?></td>
		      <td><?php if (isset($error['productTypeSearch'])) {?><span class="red_color"><?php echo $error['productTypeSearch'];?></span><?php }?></td>
		    </tr>	    
		  </table>	      
	      -->
	      <?php echo form_close();?>
    	</div>
      
      <div class="cb"></div>
    </div>
    
    <div class="bg_title_content">List Products</div>
    <div class="box_phonenumber p12t p13b search_by_key" id="tableGrid">
      <?php echo form_open_multipart(site_url('search-product'), array('id'=>'product_search_form', 'name'=>'product_search_form'));?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="">
          <td align="left" class=" "> Search Product(Enter search key): </td>
          <td align="left" class=" "> <input type="text" name="searchKey" value="<?php echo isset($searchKey)?$searchKey:''?>" /> </td>
          <td align="left" class=" "> <a class="bt_check" href="javascript:void(0);" onclick="$('#product_search_form').submit();">Search
          	</a> </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
    
    <div class="box_phonenumber p12t p13b search_by_list" id="tableGrid">
      <?php echo form_open_multipart(site_url('search-product-by-list'), array('id'=>'product_search_list_form', 'name'=>'product_search_list_form'));?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="">
          <td align="left" class=" "> Get Products by List: </td>
          <td align="left" class=" "> <?php echo form_dropdown('productSearchByList', $product_lists, (isset($productSearchByList)?$productSearchByList:''), 'class="" id="productSearchByList"');?> </td>
          <td align="left" class=" "> <a class="bt_check" href="javascript:void(0);" onclick="$('#product_search_list_form').submit();">Submit
          	</a> </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>
    <div class="box_phonenumber p12t p13b" id="tableGrid">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="center" class="bg_table boder_right"> Product Name </td>
          <td align="center" class="bg_table boder_right"> Product List </td>
          <td align="center" class="bg_table boder_right"> Product Type </td>
          <td align="center" class="bg_table boder_right"> Product Category </td>
          <td align="center" class="bg_table boder_right"> Product Vendor </td>
          <td align="center" class="bg_table boder_right"> Total Commissions(%) </td>
          <td align="center" class="bg_table boder_right"> Admin Commission(%) </td>
          <td align="center" class="bg_table boder_right"> Destributor Commission(%) </td>
          <td align="center" class="bg_table boder_right"> Max Store Commission(%) </td>
          <td align="center" class="bg_table boder_right"> Min Store Commission(%) </td>
          <td align="center" class="bg_table boder_right"> SKU ID </td>
          <td align="center" class="bg_table boder_right"> Country </td>
          <td colspan="2" align="center" class="bg_table"> Action</td>
        </tr>
        <?php if(count($results)>0) { ?>
        <?php $i=1;foreach($results as $item) {?>
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td class="boder_right"><?php echo $item->vproductName;?></td>
          <td class="boder_right"><?php echo $item->productName;?></td>
          <td class="boder_right"><?php echo $item->vproductType;?></td>
          <td class="boder_right"><?php echo $item->vproductCategory;?></td>
          <td class="boder_right"><?php echo $item->vproductVendor;?></td> 
          <td class="boder_right"><?php echo $item->vproducttotalCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductAdminCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductDistCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductMaxStoreCommission;?></td> 
          <td class="boder_right"><?php echo $item->vproductMinStoreCommission;?></td>
          <td class="boder_right"><?php echo $item->vproductSkuID ? $item->vproductSkuID : 'NA';?></td>
          <td class="boder_right"><?php echo $item->vproductCountryCode ? $item->CountryName : 'NA';?></td>
          <td width="100" align="center" class="boder_right">
          	<a class="bt_edit" onclick="updateProduct(	<?php echo $item->vproductID;?>, 
          							'<?php echo $item->vproductName;?>', 
          							'<?php echo $item->productTypeID;?>', 
          	 						'<?php echo $item->vproductType;?>',
          	 						'<?php echo $item->vproductCategory;?>',
          	 						'<?php echo $item->vproductVendor;?>',
          	 						'<?php echo $item->vproducttotalCommission;?>',
          	 						'<?php echo $item->vproductAdminCommission;?>',
          	 						'<?php echo $item->vproductDistCommission;?>',
          	 						'<?php echo $item->vproductMaxStoreCommission;?>',
          	 						'<?php echo $item->vproductMinStoreCommission;?>',
          	 						'<?php echo $item->vproductSkuID;?>',
          	 						'<?php echo $item->note;?>',
          	 						'<?php echo $item->vproductCountryCode;?>',
          	 						'<?php echo $item->vproductDenomination;?>',
          	 						'<?php echo $item->vproductMinAmount;?>',
          	 						'<?php echo $item->vproductmaxAmount;?>',
          	 						'<?php echo $item->vLocalPhoneNumberLength;?>',
          	 						'<?php echo $item->vproductSkuName;?>',
          	 						'<?php echo $item->ppnProductID;?>'
          	 						
          	 						)">Edit</a></td>
          <td width="100" align="center"><a class="bt_delete" onclick="deleteProduct(<?php echo $item->vproductID;?>, '<?php echo $item->vproductName;?>');">Delete</a></td>
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

<script type="text/javascript">
function deleteProduct(productID, productName) {
	if (confirm("Are you sure want to delete this Product?")) {
		$.ajax({
			url: '<?php echo site_url('admin/delete_product');?>',
			type: 'POST',
			cache: false,
			data:{productID:productID, productName:productName},
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
function updateProduct(productID, productName, productTypeID, productType, productCategory, productVendor, totalCommissions, adminCommission, distCommission, storeMaxCommission, storeMinCommission, sku, note, country, faceValue, productMinAmount, productMaxAmount, localNumberLength, skuName, ppnProdctID) {
	$("input[name=edit]").val(productID);
	$("input[name=productName]").val(productName);
	$("select[name=productList]").val(productTypeID);
	$("select[name=productType]").val(productType);
	$("input[name=productCategory]").val(productCategory);
	$("input[name=productVender]").val(productVendor);
	$("input[name=productCommission]").val(totalCommissions);
	$("input[name=adminCommission]").val(adminCommission);
	$("input[name=distributorCommission]").val(distCommission);
	$("input[name=storeMaxCommission]").val(storeMaxCommission);
	$("input[name=storeMinCommission]").val(storeMinCommission);
	$("input[name=sku]").val(sku);
	$("input[name=note]").val(note);
	$("select[name=countryList]").val(country);
	$("input[name=productName]").focus();
	$("input[name=faceValue]").val(faceValue);
	$("input[name=productMinAmount]").val(productMinAmount);
	$("input[name=productMaxAmount]").val(productMaxAmount);
	$("input[name=localNumberLength]").val(localNumberLength);
	$("input[name=skuName]").val(skuName);
	$("input[name=ppnProductID]").val(ppnProdctID);
}

/***** Search product by Product Type *****/
$( "select[name=productTypeSearch]" ).change(function() {
	var productID = $(this).val();
	
	$.getJSON(
			"<?php echo site_url('admin/search_products');?>",
			{
				id:productID				
			},
			function(data){ 
				 $('html, body').animate({scrollTop:$("#tableGrid").offset().top}, 1000);
				var html_content = '';
				if (data.success!=0) {					
					html_content +='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
						html_content +='<tr class="white_color">';
				          html_content +='<td width="50" align="center" class="bg_table boder_right"> ID </td>';
				          html_content +='<td align="center" class="bg_table boder_right"> Product Name </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product List </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Type </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Category </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Vendor </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Total Commissions(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Admin Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Destributor Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Max Store Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Min Store Commission(%) </td>'; 
				          html_content +='<td colspan="2" align="center" class="bg_table"> Action</td>'; 
				        html_content +='</tr>'; 
				        var j=1;
					$.each(data, function(i, item) {
						if(j%2==1){
							var trClass = 'bg_light_blu';
						}else{
							var trClass = '';
						}
						
						var ID       		 = item.vproductID;
						var productName     = "'"+item.vproductName+"'";
						var productList     = "'"+item.productName+"'";
						var productType     = "'"+item.vproductType+"'";
						var category	    = "'"+item.vproductCategory+"'";
						var productVendor   = "'"+item.vproductVendor+"'";
						var totalCommission = "'"+item.vproducttotalCommission+"'";
						var adminCommission = "'"+item.vproductAdminCommission+"'";
						var distCommission  = "'"+item.vproductDistCommission+"'";
						var storeMaxCommission = "'"+item.vproductMaxStoreCommission+"'";
						var storeMinCommission = "'"+item.vproductMinStoreCommission+"'";
						var sku	 			    = "'"+item.vproductSkuID+"'";
						
						html_content +='<tr class="'+trClass+'" >';
						  html_content +='<td align="center" class="boder_right">'+j+'</td>';
				          html_content +='<td align="center" class="boder_right">'+item.vproductName+'</td>';
				          html_content +='<td class="boder_right">'+item.productName+'</td>';
				          html_content +='<td class="boder_right">'+item.vproductType+'</td>';
				          html_content +='<td class="boder_right">'+item.vproductCategory+'</td>';
				          html_content +='<td class="boder_right">'+item.vproductVendor+'</td>';
				          html_content +='<td class="boder_right">'+item.vproducttotalCommission+'</td>';
				          html_content +='<td class="boder_right">'+item.vproductAdminCommission+'%</td>';
				          html_content +='<td class="boder_right">'+item.vproductDistCommission+'%</td>';
				          html_content +='<td class="boder_right">'+item.vproductMaxStoreCommission+'%</td>';
				          html_content +='<td class="boder_right">'+item.vproductMinStoreCommission+'%</td>';
				          html_content +='<td width="100" align="center" class="boder_right"><a class="bt_edit" onclick="updateProduct('+ID+', '+productName+', '+item.productTypeID+','+productType+', '+category+', '+productVendor+', '+totalCommission+', '+adminCommission+', '+distCommission+', '+storeMaxCommission+', '+storeMinCommission+', '+sku+');">Edit</a></td>';
				          html_content +='<td width="100" align="center"><a class="bt_delete" onclick="deleteProduct('+ID+', '+productName+');">Delete</a></td> ';
				        html_content +='</tr>';
					j++;	
					});
					html_content +='</table>';				
				} else{
					html_content +='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
						html_content +='<tr class="white_color">';
				          html_content +='<td width="50" align="center" class="bg_table boder_right"> ID </td>';
				          html_content +='<td align="center" class="bg_table boder_right"> Product Name </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product List </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Type </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Category </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Product Vendor </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Total Commissions(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Admin Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Destributor Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Max Store Commission(%) </td>'; 
				          html_content +='<td align="center" class="bg_table boder_right"> Min Store Commission(%) </td>'; 
				          html_content +='<td colspan="2" align="center" class="bg_table"> Action</td>'; 
				        html_content +='</tr>'; 
						html_content +='<tr>';
			          		html_content +='<td colspan="5">No Record Found</td>';
			        	html_content +='</tr>';
			        html_content +='</table>';	
				}
				$(".box_phonenumber").html(html_content);
				
	}); // $.getJson end
});
</script>
<?php include '_footer.php';?>
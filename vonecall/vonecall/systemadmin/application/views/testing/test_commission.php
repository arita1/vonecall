<?php include APPPATH.'views/_header.php';?>

<div id="main" class="mh730">
  <div class="bg_title">Testing</div>
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <div class="p20l p20r p13b">   
    <div class="box_phonenumber p12t p13b">     
      <div class="bg_title_content">Commission Testing</div>
      <div class="form_addcustomer p15t">
        <?php echo form_open_multipart(site_url('test-commission'), array('id'=>'test_commission', 'name'=>'test_commission'));?>
        
        <label>Product List:</label>
        <?php echo form_dropdown('productList', $product_list, (isset($productList)?$productList:''), 'class="w327"');?>
        <?php if (isset($error['productList'])) {?><span class="red_color"><?php echo $error['productList'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Product:</label>
        <?php echo form_dropdown('product', $allProduct, (isset($product)?$product:''), 'class="w327"');?>
        <?php if (isset($error['product'])) {?><span class="red_color"><?php echo $error['product'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Store:</label>
        <?php echo form_dropdown('store', $allStore, (isset($store)?$store:''), 'class="w327"');?>
        <?php if (isset($error['store'])) {?><span class="red_color"><?php echo $error['store'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>Amount:</label>
        <input name="amount" value="<?php echo isset($amount)?$amount:'';?>" class="w325" type="text" />
        <?php if (isset($error['amount'])) {?><span class="red_color"><?php echo $error['amount'];?></span><?php }?>
        <div class="cb"></div>
        
        <label>&nbsp;</label>
        <a class="bt_save float_left" href="javascript:void(0);" onclick="$('#test_commission').submit();">Submit</a>
        <div class="cb"></div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
<script>
	$( "select[name=productList]" ).change(function() {
		$.ajax({
		url: '<?php echo site_url('admin/get_all_product_by_list/');?>/'+this.value,
		type: 'POST',
		cache: false,
		data:{},
		success: function(data) {
			$("select[name=product]").html(data);
		}
	});
	});
</script>
<?php include APPPATH.'views/_footer.php';?>
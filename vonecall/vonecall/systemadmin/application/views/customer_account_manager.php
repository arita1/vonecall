<?php include '_header.php';?>
<div id="main" class="mh730">
      <div class="bg_title">Customer manager</div>
      <div class="p20l p20r p13b">
        
        <div class="form_addcustomer p15t">
          <div class="cb p10b"></div>
          <?php echo form_open_multipart(site_url('customer-manager'), array('id'=>'form_customer_search', 'name'=>'form_customer_search'))?>
          <input type="hidden" id="search_key" name="search_key" value="<?php echo isset($search_key)?$search_key:'';?>"/>
          <input type="hidden" id="search_value" name="search_value" value="<?php echo isset($search_value)?$search_value:'';?>"/>
          <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:'';?>"/>
          <input type="hidden" id="excel" name="excel" value="0"/>
          <?php echo form_close();?>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Search by Store:</td>
              <td>Search by Product:</td>
              <td>Search by Phone Number:</td>
            </tr>
            <tr>
              <!--
              <td><input id="search_by_customer_login_id" value="<?php echo isset($search_by_customer_login_id)?$search_by_customer_login_id:'';?>" class="w208" type="text"/></td>
              <td><input id="search_by_customer_lastName" value="<?php echo isset($search_by_customer_lastName)?$search_by_customer_lastName:'';?>" class="w208" type="text"/></td>
              <td><input id="search_by_customer_phone_number" value="<?php echo isset($search_by_customer_phone_number)?$search_by_customer_phone_number:'';?>" class="w208" type="text"/></td>
              -->
              <td><?php echo form_dropdown('search_by_store', $option_store, (isset($search_by_store)?$search_by_store:''), 'class="w132 float_left" id="search_by_store" style="width: 180px;"');?></td>
              <td><?php echo form_dropdown('search_by_product', $option_product, (isset($search_by_product)?$search_by_product:''), 'class="w132 float_left" id="search_by_product" style="width: 180px;"');?></td>
              <td><input id="search_by_customer_phone_number" value="<?php echo isset($search_by_customer_phone_number)?$search_by_customer_phone_number:'';?>" class="w208" type="text"/></td>
              
            </tr>
            <tr>
              <td><a href="javascript:void(0);" onclick="customer_search('search_by_store');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
              <td><a href="javascript:void(0);" onclick="customer_search('search_by_product');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
              <td><a href="javascript:void(0);" onclick="customer_search('search_by_customer_phone_number');"><img src="<?php echo $this->config->item('base_url')?>public/images/bt_view.jpg" /></a></td>
            </tr>
          </table>
        </div>
        <?php if ($this->session->userdata('usertype')=='admin') {?>
        <p class="p10 bo_t_d m10t">
          <input type="hidden" id="search_by_all" value="1"/>
          <a class="bt_atatement_address" onclick="customer_search('search_by_all');">View All Customers</a>
        </p>
        <?php }?>
        <div class="box_phonenumber p12t p13b">
          <?php if (isset($results)) { ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="white_color">
              <td align="center" class="bg_table boder_right">No</td>
              <td class="bg_table boder_right">Cust ID</td>
              <td class="bg_table boder_right">Login ID</td>
              <td class="bg_table boder_right">Product</td>
              <td class="bg_table boder_right">Phone Number</td>
              <td class="bg_table boder_right">Enroll Date</td>
              <td class="bg_table boder_right">AgentID</td>
              <td align="center" class="bg_table">Action</td>
            </tr>
            <?php if(count($results)>0) { ?>
            <?php $i=1;?>
            <?php foreach($results as $item) {?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td align="center" class="boder_right"><?php echo $i;?></td>
              <td class="boder_right"><strong><?php echo $item->customerID;?></strong></td>
              <td class="boder_right"><strong><?php echo $item->loginID;?></strong></td>
              <td class="boder_right"><?php echo $item->customerProduct;?></td>
              <td class="boder_right"><?php echo format_phone_number($item->phone);?></td>
              <td class="boder_right"><?php echo date('m/d/Y H:i:s', strtotime($item->createdDate));?></td>
              <td class="boder_right"><?php echo $item->agentID;?></td>
              <td align="center"><a class="bt_delete" onclick="deleteCustomer(<?php echo $item->customerID;?>);">Delete</a></td>
            </tr>
            <?php $i++;?>
            <?php }?>
            <?php } else {?>
            <tr>
              <td colspan="8"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </table>
          <?php echo isset($paging)?$paging:'';?>
          <?php }?>
      </div>
      </div>
    </div>
<script>
function paging(num) {
	$('#excel').val(0);
	$('input[name=page]').val(num);
	$('#form_customer_search').submit();
}
function customer_search(key) {
	$('#excel').val(0);
	value = $('#'+key).val();
	if ($.trim(value) != '') {
		$('#search_key').val(key);
		$('#search_value').val(value);
		$('input[name=page]').val('');
		$('#form_customer_search').submit();
	} else {
		alert('Please input search key!');
	}
}

// Customer ===============
function deleteCustomer(id) {
	if (confirm("Are you sure want to delete this customer?")) {
		$.ajax({
			url: '<?php echo site_url('delete-customer');?>',
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
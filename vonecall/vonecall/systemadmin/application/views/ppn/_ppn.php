<?php include APPPATH.'views/_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
      <div class="bg_title">Prepy Nation Testing</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='carrierlist') echo 'class="current"';?>><a href="<?php echo site_url('ppn/carrier-list');?>">Carrier List</a></li>
              <li <?php if($sub_current_page=='productlist') echo 'class="current"';?>><a href="<?php echo site_url('ppn/product-list');?>">Product List  </a></li>
              <li <?php if($sub_current_page=='topup_recharge') echo 'class="current"';?>><a href="<?php echo site_url('ppn/topup-recharge');?>">Topup Recharge RTR  </a></li>
              <li <?php if($sub_current_page=='topup_pin') echo 'class="current"';?>><a href="<?php echo site_url('ppn/topup-pin');?>">Topup Recharge PIN  </a></li>
              <li <?php if($sub_current_page=='admin_balance') echo 'class="current"';?>><a href="<?php echo site_url('ppn/admin-balance');?>">Admin balance  </a></li>
              <li <?php if($sub_current_page=='export') echo 'class="current"';?>><a href="<?php echo site_url('ppn/export');?>">Export All Products  </a></li>
            </ul>
          	</div>
          </td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
              <?php include $sub_current_page.'.php';?>
          </td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<?php include APPPATH.'views/_footer.php';?>
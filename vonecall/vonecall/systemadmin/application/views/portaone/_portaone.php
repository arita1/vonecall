<?php include APPPATH.'views/_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
      <div class="bg_title">Portaone Testing</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='addcustomer') echo 'class="current"';?>><a href="<?php echo site_url('portaone/new-customer');?>">New Customer </a></li>
              <li <?php if($sub_current_page=='addaccount') echo 'class="current"';?>><a href="<?php echo site_url('portaone/new-account');?>">New Account  </a></li>
              <li <?php if($sub_current_page=='addline') echo 'class="current"';?>><a href="<?php echo site_url('portaone/add-line');?>">Add Line (alias) </a></li>
              <li <?php if($sub_current_page=='getaccountinfo') echo 'class="current"';?>><a href="<?php echo site_url('portaone/account-info');?>">Get Account Info</a></li>
              <li <?php if($sub_current_page=='alias_get') echo 'class="current"';?>><a href="<?php echo site_url('portaone/get-alias');?>">Get Alias</a></li>
              <li <?php if($sub_current_page=='getbalance') echo 'class="current"';?>><a href="<?php echo site_url('portaone/account-balance');?>">Get Balance </a></li>
              <li <?php if($sub_current_page=='get_speed_dial') echo 'class="current"';?>><a href="<?php echo site_url('portaone/get-speed-dial');?>">Get Speed dial </a></li>
              <li <?php if($sub_current_page=='xdr') echo 'class="current"';?>><a href="<?php echo site_url('portaone/get-xdr');?>">Get xDRs </a></li>
              <li <?php if($sub_current_page=='rechargeaccount') echo 'class="current"';?>><a href="<?php echo site_url('portaone/recharge-account');?>">Recharge Account </a></li>
              <li <?php if($sub_current_page=='rechargehistory') echo 'class="current"';?>><a href="<?php echo site_url('portaone/recharge-history');?>">Recharge History </a></li>
              <li <?php if($sub_current_page=='refund') echo 'class="current"';?>><a href="<?php echo site_url('portaone/refund');?>"> Refund </a></li>
              <li <?php if($sub_current_page=='speed_dial') echo 'class="current"';?>><a href="<?php echo site_url('portaone/speed-dial');?>">Speed dial </a></li>
              <li <?php if($sub_current_page=='terminateAccount') echo 'class="current"';?>><a href="<?php echo site_url('portaone/terminate-account');?>"> Terminate Account  </a></li>
              <li <?php if($sub_current_page=='get_product_list') echo 'class="current"';?>><a href="<?php echo site_url('portaone/product-list');?>"> Product List  </a></li>
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
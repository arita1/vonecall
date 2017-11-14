<?php include APPPATH.'views/_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
<div id="main">
      <div class="bg_title">Reports</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='sales_report') echo 'class="current"';?>><a href="<?php echo site_url('reports/sales');?>"> Sales Report </a></li>
              <li <?php if($sub_current_page=='commission_report') echo 'class="current"';?>><a href="<?php echo site_url('reports/commission-report');?>" > Commissions Report </a></li>
              <li <?php if($sub_current_page=='sub_distributor_report') echo 'class="current"';?>><a href="<?php echo site_url('reports/sub-distributor-report');?>"> Sub-distributor Reports </a></li>
              
              <li <?php if($sub_current_page=='store_payment') echo 'class="current"';?>><a href="<?php echo site_url('reports/store-payment');?>"> Store Payment/Funds </a></li>
              <li <?php if($sub_current_page=='distributor_payment') echo 'class="current"';?>><a href="<?php echo site_url('reports/distributor-payment');?>"> Distributor Payment/Funds </a></li>
              <li <?php if($sub_current_page=='store_balance_report') echo 'class="current"';?>><a href="<?php echo site_url('reports/store-balance');?>"> Store Balances </a></li>
              <li <?php if($sub_current_page=='distributor_balance_report') echo 'class="current"';?>><a href="<?php echo site_url('reports/distributor-balance');?>"> Distributor Balances </a></li>                            
            </ul>            
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              <div class="bg_title_content2"></div>              
              <?php include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<?php include APPPATH.'views/_footer.php';?>
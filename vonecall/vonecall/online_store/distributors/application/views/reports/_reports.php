<?php include APPPATH.'views/_header.php';?>

<script>
$(document).ready(function() {
	$( "#access_numbers" ).click(function() {
		$.colorbox({ href: "<?php echo site_url('popup-access-number');?>", width:"80%", height:"80%", iframe: true, scrolling: true });
	});
	
	$( "#rates" ).click(function() {
		$.colorbox({ href: "<?php echo site_url('popup-rate');?>", width:"80%", height:"80%", iframe: true, scrolling: true });
	});
});
</script>

<div id="main">
      <div class="bg_title">Reports</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='sales_report') echo 'class="current"';?>><a href="<?php echo site_url('reports');?>"> Sales Report </a></li>
              
              <?php if($repinfo->agentTypeID != 4) {?>
              	<li <?php if($sub_current_page=='commission_report') echo 'class="current"';?>><a href="<?php echo site_url('commission-report');?>" > Commissions Report </a></li>
              <?php } ?>
              
              <li <?php if($sub_current_page=='balance_report') echo 'class="current"';?>><a href="<?php echo site_url('balance-report');?>">Store Balance Report</a></li>
              <li> <a href="javascript:void(0);" id="access_numbers"> Pinless Access Numbers </a></li>
              <li> <a href="javascript:void(0);" id="rates"> Pinless Rate Sheet </a></li>
              <?php if($this->session->userdata('rep_usertype') != 'Sub-Distributor'){ ?>
              	<li <?php if($sub_current_page=='subdist_report') echo 'class="current"';?> ><a href="<?php echo site_url('subdist-report');?>"> Sub Distributor Reports </a></li>
              <?php }?>
              <?php if($repinfo->agentTypeID != 4){ ?>
              	<li <?php if($sub_current_page=='commission_rates') echo 'class="current"';?>> <a href="<?php echo site_url('product-list');?>" id="commission_rates"> Product List </a></li>
			  <?php } ?>
            </ul>
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              <div class="bg_title_content2"></div>   
              <?php 
              echo $sub_current_page;
              include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<?php include APPPATH.'views/_footer.php';?>
<?php include APPPATH.'views/_header.php';?>
<script>
$(document).ready(function() {
	$('input[name=\'from_date\']').datetimepicker( {format: "m/d/Y",timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
	$('input[name=\'to_date\']').datetimepicker({format: "m/d/Y", timepicker:false}).on('change', function(e){ $(this).datetimepicker('hide'); });
});
</script>
	<div id="main" class="p5t p10b">
      <div class="bg_title"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='pinless_alias') echo 'class="current"';?>><a onclick="$('.loading').show();" href="<?php echo site_url('pinless-manage/'.$phone);?>">Associated Numbers</a></li>
              <li <?php if($sub_current_page=='pinless_speed_dial') echo 'class="current"';?>><a onclick="$('.loading').show();" href="<?php echo site_url('speed-dial/'.$phone);?>">Speed Dial</a></li>
              <li <?php if($sub_current_page=='calling_history') echo 'class="current"';?>><a onclick="$('.loading').show();" href="<?php echo site_url('calling-history/'.$phone);?>">Calling History</a></li>
              <li <?php if($sub_current_page=='pinless_account_recharge') echo 'class="current"';?>><a onclick="$('.loading').show();" href="<?php echo site_url('pinless-recharge/'.$phone);?>"><?php echo $this->lang->line('recharge');?></a></li>
              <li <?php if($sub_current_page=='pinless_transaction_history') echo 'class="current"';?>><a onclick="$('.loading').show();" href="<?php echo site_url('transaction-history/'.$phone);?>">Transaction History</a></li>
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
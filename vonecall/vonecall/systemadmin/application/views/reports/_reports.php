<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">Admin Reports</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='reports') echo 'class="current"';?>><a href="<?php echo site_url('admin-logs');?>"> View the logs </a></li>
              <li <?php if($sub_current_page=='print') echo 'class="current"';?>><a href="javascript:void(0)" id="print_log"> Print </a></li>
              <li <?php if($sub_current_page=='export') echo 'class="current"';?>><a href="<?php echo site_url('export-report');?>"> Export Report </a></li>
              <li <?php if($sub_current_page=='log_management') echo 'class="current"';?>><a href="<?php echo site_url('log-management');?>"> Log Management </a></li>              
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
<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">Portaone Reports</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='report_account') echo 'class="current"';?>><a href="<?php echo site_url('portaone/accounts-reports');?>">Get All Accounts </a></li>
              <li <?php if($sub_current_page=='report_cdr') echo 'class="current"';?>><a href="<?php echo site_url('portaone/cdr-reports');?>">Get All CDR </a></li>             
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
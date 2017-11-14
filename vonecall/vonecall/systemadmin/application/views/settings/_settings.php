<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">General Settings</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='general_settings') echo 'class="current"';?>><a href="<?php echo site_url('ryd-admin');?>"> RYD Admin </a></li>
              <li <?php if($sub_current_page=='banner_message') echo 'class="current"';?>> <a href="<?php echo site_url('banner-message/Dist-Banner');?>"> Banner Text </a> </li>
              <li <?php if($sub_current_page=='ppn_settings') echo 'class="current"';?>> <a href="<?php echo site_url('ppn-mode');?>"> Topup Mode </a> </li>
              <li <?php if($sub_current_page=='pinless_settings') echo 'class="current"';?>> <a href="<?php echo site_url('pinless-mode');?>"> Pinless Mode </a> </li>
              <li <?php if($sub_current_page=='text_message_settings') echo 'class="current"';?>> <a href="<?php echo site_url('text-message');?>"> Text Message </a> </li>
              <li <?php if($sub_current_page=='store_header') echo 'class="current"';?>> <a href="<?php echo site_url('store-header');?>"> Store Header </a> </li> 
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
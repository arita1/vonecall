<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">Distributor manager</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='profile') echo 'class="current"';?>><a href="<?php echo site_url('destributor/profile/'.$agentID);?>">Distributor Profile</a></li>              
              <li <?php if($sub_current_page=='commission') echo 'class="current"';?>><a href="<?php echo site_url('destributor/commission/'.$agentID);?>">Distributor Commission</a></li>
            </ul>
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              <div class="bg_title_content2">Distributor Information</div>
              <div class="box_submenu p12t p13b">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="20%" class="boder_right">First Name: <?php echo $info->firstName;?></td>
                    <td width="20%" class="boder_right">Last Name: <?php echo $info->lastName;?></td>
                    <td width="20%" class="boder_right">Login ID: <?php echo $info->loginID;?></td>
                    <td width="20%"></td>
                    <td width="20%"></td>
                  </tr>                 
                </table>
              </div>
              <?php include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<?php include APPPATH.'views/_footer.php';?>
<?php include '_header.php';?>

<div id="main">   
  <div class="bg_title">Account Summary</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              
            </ul>
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              <div class="bg_title_content2">User Information</div>
              <div class="box_submenu p12t p13b">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="20%" class="boder_right">First Name: <?php echo $info->firstName;?></td>
                    <td width="20%" class="boder_right">Last Name: <?php echo $info->lastName;?></td>
                    <td width="20%" class="boder_right">Login ID: <?php echo $info->username;?></td>
                    <td width="20%"></td>
                    <td width="20%"></td>
                  </tr>                 
                </table>
              </div>
              <?php //include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
</div>

<?php include '_footer.php';?>
<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">Contacts</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='all_contacts' || $sub_current_page=='update_contact') echo 'class="current"';?>><a href="<?php echo site_url('contacts');?>">All Contacts</a></li>
              <li <?php if($sub_current_page=='contacts') echo 'class="current"';?>><a href="<?php echo site_url('add-contacts');?>">Add Contact</a></li>
              <li <?php if($sub_current_page=='optout_contacts') echo 'class="current"';?>><a href="<?php echo site_url('optout-contacts');?>">Opt-out Contacts</a></li>
              <li <?php if($sub_current_page=='import_contact') echo 'class="current"';?>><a href="<?php echo site_url('contact-import');?>">Import</a></li>              
            </ul>
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              
              <?php include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<?php include APPPATH.'views/_footer.php';?>
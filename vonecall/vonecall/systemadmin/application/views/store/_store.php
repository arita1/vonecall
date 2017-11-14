<?php include APPPATH.'views/_header.php';?>
<div id="main">
      <div class="bg_title">Store Account manager</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="170" valign="top"class="col_left" ><div class="nav_left">
            <ul>
              <li <?php if($sub_current_page=='profile') echo 'class="current"';?>><a href="<?php echo site_url('store/profile/'.$agentID);?>">Store Profile</a></li>              
           	  <li <?php if($sub_current_page=='commission') echo 'class="current"';?>><a href="<?php echo site_url('store/commission/'.$agentID);?>">Store Commission</a></li>
            </ul>
          </div></td>
          <td align="left" valign="top"><div class="p20l p20r p13b">
          <?php if (isset($warning)) {?><br/><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
              <div class="bg_title_content2">Store Information</div>
              <div class="box_submenu p12t p13b">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="25%" class="boder_right">First Name: <?php echo $info->firstName;?></td>
                    <td width="25%" class="boder_right">Last Name: <?php echo $info->lastName;?></td>
                    <td width="25%" class="boder_right">Login ID: <?php echo $info->loginID;?></td>
                    <td width="25%" class="boder_right">Phone Number: <?php echo format_phone_number($info->phone);?></td>
                  </tr>                  
                </table>
              </div>
              <?php include $sub_current_page.'.php';?>
            </div></td>
        </tr>
      </table>
      <div class="cb"></div>
    </div>
<script>
function calculate_agent(time_period) {
	$.ajax({
		url: '<?php echo site_url('agent/calculate_agent/'.$agentID);?>',
		type: 'POST',
		cache: false,
		data:{time_period:time_period},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				$("#agent_sale").html(data.Sale);
				$("#agent_payment").html(data.Payment);
				$("#agent_commission").html(data.Commission);
			} else {
				alert('error!');
			}
		},
		error: function (){
			alert('Please try again!');
		}
	});
	return;
}
//calculate_agent('today');
</script>
<?php include APPPATH.'views/_footer.php';?>
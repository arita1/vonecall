<div class="bg_title_content">Get Recharge History</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
  <?php echo form_open_multipart(site_url('portaone/recharge-history'), array('id'=>'form_recharge_history', 'name'=>'form_recharge_history'))?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="font8">
   
    <input type="hidden" name="service" value="2" />
    <tr>
		<td>Account ID (i_account)</td>
		<td>
			<?php echo form_dropdown('id', $accountList, (isset($id)?$id:''), 'class="w327" id="id"');?>
			<?php if (isset($error['id'])) {?><span class="red_color"><?php echo $error['id'];?></span><?php }?>
		</td>
	</tr>
	<tr>
		<td>From Date</td>
		<td>
			<input name="from_date" value="<?php echo isset($from_date)?$from_date:date('m/d/Y');?>" type="text" class="w162" readonly/>
			<?php if (isset($error['from_date'])) {?><span class="red_color"><?php echo $error['from_date'];?></span><?php }?>
		</td>
	</tr>
	<tr>
		<td>To Date</td>
		<td>
			<input name="to_date" value="<?php echo isset($to_date)?$to_date:date('m/d/Y');?>" type="text" class="w162" readonly/>
			<?php if (isset($error['to_date'])) {?><span class="red_color"><?php echo $error['to_date'];?></span><?php }?>
		</td>
	</tr>     
    <tr>
      <td>&nbsp;</td>
      <td><a class="bt_save" href="javascript:void(0);" onclick="$('#form_recharge_history').submit();">Submit</a></td>
    </tr>
  </table>
  <?php echo form_close();?>
</div>

<?php /*if(isset($cdr_details)) {?>
<div class="bg_title_content">Cdr Details</div>
<div class="form_addcustomer p15t">
  <h2 style="width: 20%;">CDR Details: </h2>
  <?php if($cdr_details){
  			echo '<pre>';print_r($cdr_details); 
  		} else {
  			echo '<h2>No record found</h2>';
		}	
  ?>
</div>
<?php }*/?>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($recharge_history)) {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">Payment Type</td>
      <td class="bg_table boder_right">Amount</td>
      <td class="bg_table boder_right">Description</td>
      <td class="bg_table boder_right">Connect Time</td>       
    </tr>
    <?php if(count($recharge_history)>0) {?>
    <?php $i=1;?>
    <?php foreach($recharge_history as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $item->CLD;?> </td>
      <td class="boder_right"> <?php echo ($item->charged_amount < 0) ? -$item->charged_amount : $item->charged_amount;?> </td>
      <td class="boder_right"> <?php echo $item->description;?> </td>
      <td class="boder_right"> <?php echo date('m-d-Y H:i A', strtotime($item->connect_time));?> </td>    
    </tr>
    <?php $i++;?>
    <?php }?>
    <?php } else {?>
    <tr>
      <td colspan="8">Record Not Found</td>
    </tr>
    <?php }?>
  </table>
<?php }?>


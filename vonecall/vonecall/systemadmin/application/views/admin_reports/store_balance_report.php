<div class="bg_title_content">Store Balance Report</div>
<div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('reports/store-balance'), array('id'=>'balance_report', 'name'=>'balance_report'));?>
      <input type="hidden" name="submit_type" value=""/>
      <table border="0" cellspacing="0" cellpadding="0">
      	<tr>
          <td>Store</td>
          <td></td>
        </tr>
        <tr>
          <td><?php echo form_dropdown('store', $option_store, (isset($store)?$store:''), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="$('input[name=\'submit_type\']').val('summary'); $('#balance_report').submit();" style="margin-bottom: 7px;">Get Report</a>
          </td>
        </tr>
        <tr>
      	  <td>&nbsp;</td>
          <td>&nbsp;</td>
      	  <td><?php if (isset($error['store'])) {?><span class="red_color"><?php echo $error['store'];?></span><?php }?></td>
      	  <td> &nbsp; </td>
      	</tr>
      </table>
      <?php echo form_close();?>
</div>

<?php if (isset($details)) { ?>
    <div class="box_phonenumber p12t p13b">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="white_color">
          <td align="right" class="bg_table boder_right">No.</td>
          <td class="bg_table boder_right">Store Name</td>
          <td align="right" class="bg_table boder_right">Current Balance</td>
          <td align="right" class="bg_table boder_right">Store Owner</td>
          <td align="right" class="bg_table boder_right">Phone</td>
          <td align="right" class="bg_table boder_right">Cellphone</td>          
        </tr>
        <?php if(count($details)>0) {?>
        <?php $i=1;?>
        <?php foreach($details as $item) {?>      
        <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
          <td align="right" class="boder_right"><?php echo $i ?></td>
          <td class="boder_right"><?php echo $item->company_name;?></td>
          <td align="right" class="boder_right"><?php echo '<b>('.format_price($item->balance).')</b>';?></td>
          <td align="right" class="boder_right"><?php echo $item->firstName.' '.$item->lastName ?></td>
          <td align="right" class="boder_right"><?php echo $item->phone ?></td>
          <td align="right" class="boder_right"><?php echo $item->cellPhone ?></td>          
        </tr>
        <?php $i++;?>
        <?php }?>
        <?php } else {?>
        <tr>
          <td colspan="6"><?php echo $this->lang->line('empty_results');?></td>
        </tr>
        <?php }?>
      </table>
    </div>
<?php }?>


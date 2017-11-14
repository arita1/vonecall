<div id="main">
  <div class="bg_title_content">Stores</div>
  <div class="p20l p20r p13b">
    <div class="form_addcustomer p12t p5b">
      <?php echo form_open_multipart(site_url('sales-report'), array('id'=>'sale_report', 'name'=>'sale_report'));?>
      <input type="hidden" name="button_type" value=""/>
      <input type="hidden" name="submit_type" value=""/>
      <?php if (isset($error['error_date'])) {?><span class="red_color"><?php echo $error['form'];?></span><?php }?>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td>Store</td>
          <td></td>
        </tr>
        <tr>
         <td><?php echo form_dropdown('agent', $option_agent, (isset($agent)?$agent:''), 'class="w162"');?></td>
          <td>
            <a class="bt_atatement_address" href="javascript:void(0);" onclick="" style="margin-bottom: 7px;">Submit</a>
          </td>
        </tr>
      </table>
      <?php echo form_close();?>
    </div>

  </div>
  <div class="cb"></div>
</div>

<div class="bg_title_content">Product List</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
</div>

<div class="box_phonenumber p12t p13b">
  <?php if (isset($results)) {?>
  <table width="40%" border="0" cellspacing="0" cellpadding="0">
    <tr class="white_color">
      <td align="center" class="bg_table boder_right">Product ID</td>
      <td class="bg_table">Product Name</td> 
    </tr>
    <?php if(count($results)>0) {?>
    <?php $i=1;?>
    <?php foreach($results as $item) {?>
    <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
      <td align="center" class="boder_right"> <?php echo $item->i_product;?> </td>
      <td> <?php echo $item->name;?> </td>
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


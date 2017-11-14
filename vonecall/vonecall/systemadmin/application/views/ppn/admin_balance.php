<div class="bg_title_content">Account Balance</div>
<div class="form_addcustomer p15t">
  <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
  <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
</div>

<?php if(isset($result)) {?>
<div class="form_addcustomer p15t">
  <h2 style="float: left; width: 20%;">Your Account Balance: </h2><h2 style="color: #FD9012"> $<?php echo $result;?> </h2>
</div>
<?php }?>
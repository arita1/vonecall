<?php include APPPATH.'views/_header.php';?>
<style>
.operatorBox {
	margin: 5px;
    vertical-align: top;
    width: 163px;
}
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac">Products of Operator <?php echo $results[0]->countryName?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    
    <!-- Loading Image -->
    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
   
    <div class="col_big">       
        <!-- Display Logos -->
        <div class="operatorLogos">
        	<?php foreach($results as $item){?>
        	<div class="operatorBox" >
			   <a href="<?php echo site_url('topup-recharge/'.$item->ppnProductID)?>">
		         <img src="<?php echo $this->config->item('base_url')?>/systemadmin/public/uploads/product_logo/<?php echo $item->logoName ?>" alt="<?php echo $item->vproductVendor ?>" width="150" height="90" />
		    	 <span> <?php echo $item->vproductVendor ?> </span>
		       </a>	
		    </div>
		    <?php }?>
        </div>        
        <!-- END -->     
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
          
  </div>
</div>
<?php include APPPATH.'views/_footer.php';?>
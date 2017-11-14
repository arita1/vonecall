<?php include APPPATH.'views/_header.php';?>
<style>
.operatorBox {
	margin: 5px;
    vertical-align: top;
    width: 163px;
}
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('topup');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    
    <!-- Loading Image -->
    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
   
    <div class="col_big">
    	<div class="operatorLogos">
    		<?php //echo '<pre>';print_r($countries);?>
			<?php foreach ($countries as $item) { ?>
    		<div class="operatorBox" >
			   <a href="<?php echo site_url('country-product/'.$item->CountryCodeIso);?>" title="<?php echo $item->CountryName;?>">
		         <img src="<?php echo $this->config->item('base_url')?>systemadmin/public/uploads/country_flag/<?php echo $item->countryFlag;?>" alt=""/>
		    	 <span>  <?php echo $item->CountryName;?> </span>
		       </a>	
		    </div>
		    <?php }?>
    	</div>    
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
          
  </div>
</div>
<?php include APPPATH.'views/_footer.php';?>
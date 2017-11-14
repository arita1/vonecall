<style>
	.footer_logo{
		display: block;
	    float: left;
	    margin-left: 30px;
	    padding: 5px;
	    width: 30px;
	}
	.footer_tag{
		float: left;
	    margin-right: 0;
	    padding: 18px 5px 5px;
	}
</style>

    <div id="footer">
      
      <div class="footer_logo" >
      <img src="<?php echo $this->config->item('base_url')?>public/images/logo_footer.png" style="width:100%;" />
      </div>
      <div class="float_left p12t p10l footer_tag" > <?php echo $this->config->item('footer')?> </div>
      
    </div>
  </div>
</div>
</body>
</html>
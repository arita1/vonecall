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

.sslSeal {
    float: left;
    margin-left: 75px;
    padding: 18px 40px 0;
    text-align: center;
    width: 180px;
}
div.AuthorizeNetSeal {
    float: left;
    padding: 0 40px;
    width: 180px;
    margin: 0 0 0 230px !important;
}

</style>

    <div id="footer">
      
      <div class="footer_logo" >
      <img src="<?php echo $this->config->item('base_url')?>public/images/logo_footer.png" style="width:100%;" />
      </div>
      <div class="float_left p12t p10l footer_tag" > <?php echo $this->config->item('footer')?> </div>
      
      <!-- (c) 2005, 2014. Authorize.Net is a registered trademark of CyberSource Corporation --> 
      <div class="AuthorizeNetSeal"> 
      	<script type="text/javascript" language="javascript">var ANS_customer_id="a2a844f4-87a6-4445-b057-27be96ca0e27";</script> 
      	<script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>      	
      </div> 
      <div class="sslSeal">
      	<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=NmEuf1SiSwZD0WVBrsPoHXGMeeHyVuRVRWzP5q3x9vFH8UAsmyeu"></script></span>
      </div>
      
    </div>
  </div>
</div>
</body>
</html>
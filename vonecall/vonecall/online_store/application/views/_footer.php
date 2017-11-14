  </div>
  </div>
  <div id="footer">
    <div id="footer_comment">
      <span class="left float_left"><?php echo $this->lang->line('footer_title');?></span>
      
      <!-- (c) 2005, 2014. Authorize.Net is a registered trademark of CyberSource Corporation --> 
      <div class="AuthorizeNetSeal"> 
      	<script type="text/javascript" language="javascript">var ANS_customer_id="a2a844f4-87a6-4445-b057-27be96ca0e27";</script> 
      	<script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>      	
      </div>
      <div class="sslSeal">
      	<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=NmEuf1SiSwZD0WVBrsPoHXGMeeHyVuRVRWzP5q3x9vFH8UAsmyeu"></script></span>
      </div>
      <span class="right float_right"><a href="<?php echo site_url('terms-and-conditions');?>"><?php echo $this->lang->line('terms_and_conditions');?></a> | <a href="<?php echo site_url('privacy-policy');?>"><?php echo $this->lang->line('privacy_policy');?></a></span>
      <br class="cb" />
    </div>
  </div>
</div>

<style>
.left {
    padding-top: 30px;
}
.right {
    padding-top: 30px;
}
.sslSeal {
    float: left;
    padding: 18px 40px 0;
    text-align: center;
    width: 180px;
}
div.AuthorizeNetSeal {
    float: left;
    padding: 0 40px;
    width: 180px;
    margin: 0 0 0 25px !important;
}
#footer{ height: 130px; bottom: auto; }
</style>

</body>
</html>
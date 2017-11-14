<?php $this->load->view('online_store/inc_header');?>
<style>
.services-page-tabs {width:100%; margin:0 auto;}
.homeDestinations {padding:30px; background:url("../img/servicess-bg.png"); float: left; background-size: cover;}
.homeDestinations h3{color:#fff; text-align:center; margin-bottom:30px; border-bottom: 1px solid rgb(255, 255, 255); border-bottom-style: dotted; line-height: 60px;}
.homeDestinations ul li {display:inline-block; width:40%; margin: 0 20px;}
.homeDestinations ul li  a{color:#fff;}
.homeDestinations ul li em{float: right;}
.services-page-tabs .nav-tabs {width: 70%; margin: 0 auto; }
/* .homeDestinations  ul{display:inline-block;} */
.homeDestinations .homeSearchBox-napp-fet li{display:block; color:#fff; width: 100%;}
.homeDestinations .banner-social-buttons li {display:block; margin:20px 70px;}
.testimonial {text-align:center;}
.testimonial p{color:#fff; font-style:italic;}	
	
</style>

	<!-- slider -->
			<div id="main-slider" class="slider">
	    		
				  <div><img src="img/Doodlebugs_Service_Banner.jpg" title="" /></div>
				
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
<div class="box-table">
	<div class="top-up">
		<ul>
			<?php //https://www.vonecall.com/systemadmin/public/uploads/product_logo/att.gif 
			foreach ($products as $value) {
				?>
			<li>
				<a href="#"><img src="<?php if($value->logoName==""){ echo 'vonecall'; }else{ echo base_url().'systemadmin/public/uploads/product_logo/'.$value->logoName; } ?>" />
				<div class="block-title"><h4 class="label-primary"><?php if($value->vproductVendor==""){ echo 'vonecall'; }else{ if(strlen($value->vproductVendor)< 8){ echo $value->vproductVendor; } else{ echo substr($value->vproductVendor,0,8);  }  }  ?></h4></div>
				</a>
			</li>
				<?php 
			}
			?>
			
					
		
		</ul>
	
	
	</div>
	
	
</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>



